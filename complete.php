<?php
session_start();

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/Assets/helpers.php";

/**
 * id.json: 所有记录的id
 * idinfo.jsom: 所有记录的id和详细信息
 * pending.json: 所有审核中的id和详细信息
 * pendingid.json: 所有审核中的id
 */

use Gregwar\Captcha\PhraseBuilder;

switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        if (isset($_SESSION['captcha']) && PhraseBuilder::comparePhrases($_SESSION['captcha'], $_POST['captcha'])) {

            if (!empty($_POST["id"]) && !empty($_POST["domain"]) && !empty($_POST["description"]) && !empty($_POST["master"]) && !empty($_POST["email"])) {
                // 检查ID是否已经存在于 'id.json' 已被注册、'pending.json'正在等待管理员通过 或 'pending_email_verification.json' 正在等待邮箱验证
                $existingIds = json_decode(file_get_contents(__DIR__ . "/Assets/data/json/idinfo.json"), true);
                $pendingid = json_decode(file_get_contents(__DIR__ . "/Assets/data/json/pending.json"), true);
                $waitingVerification = json_decode(file_get_contents(__DIR__ . "/Assets/data/json/pending_email_verification.json"), true);

                $allIds = array_column($existingIds, 'id');
                $allPendingIds = array_column($pendingid, 'id');
                $allWaitingIds = array_column($waitingVerification, 'id');

                if (in_array($_POST["id"], $allIds)) {
                    echo "<center><h1>ID 已存在，请使用其他 ID！</h1></center>";
                    exit();
                } elseif (in_array($_POST["id"], $allPendingIds)) {
                    echo "<center><h1>ID 已经在审核中，请耐心等待！</h1></center>";
                    exit();
                } elseif (in_array($_POST["id"], $allWaitingIds)) {
                    echo "<center><h1>已提交邮箱验证，请检查您的邮箱或垃圾邮件！</h1></center>";
                    exit();
                }

                // 生成64位Token
                $token = bin2hex(random_bytes(32));

                // 发送验证邮件
                $subject = "请验证您的邮箱";
                $verificationLink = "https://icp.z6o.de/verify.php?token=" . urlencode($token);
                $body = "
                请点击以下链接验证您的邮箱：<a href=\"$verificationLink\">验证邮箱</a> 如果您未请求此邮件请忽略！
                <br>
                如果以上链接打不开请复制：$verificationLink<br>
                如果您未请求此邮件请忽略！<br>
                喵喵ICP备案技术支持：<a href=\"https://zicheng.icu\">小枫_QWQ</a><br>
                ";
                $altBody = "请复制此链接到浏览器进行验证：$verificationLink 如果您未请求此邮件请忽略！";

                if (sendMail($_POST["email"], $subject, $body, $altBody)) {
                    // 仅在邮箱发送成功后才把数据保存到 'pending_email_verification.json' 等待邮箱验证
                    $entry = [
                        "id" => $_POST["id"],
                        "domain" => $_POST["domain"],
                        "description" => $_POST["description"],
                        "master" => $_POST["master"],
                        "email" => $_POST["email"],
                        "token" => $token,
                        "created_at" => time() // 记录生成Token的时间
                    ];
                    $waitingVerification[] = $entry;
                    file_put_contents(__DIR__ . "/Assets/data/json/pending_email_verification.json", json_encode($waitingVerification));
                    exit("<center><h1>太棒了！验证链接已发送到您的邮箱中！请激活以让管理员审核</h1></center>");
                } else {
                    echo "<center><h1>邮件发送失败，请联系管理员！</h1></center>";
                }
            } else {
                echo "<center><h1>你没有填写完整</h1></center>";
            }
        } else {
            echo "<center><h1>验证码错误！</h1></center>";
        }
        break;
    default:
        http_response_code(404);
        break;
}