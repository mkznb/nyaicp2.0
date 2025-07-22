<?php
require_once __DIR__ . '/init.php';

if (isset($_GET["token"])) {
    $token = urldecode($_GET["token"]);
    // 读取 'pending_email_verification.json'等待邮箱验证
    $pendingVerification = json_decode(file_get_contents(__DIR__ . "/Assets/data/json/pending_email_verification.json"), true);
    // 找到对应的记录并检查Token
    foreach ($pendingVerification as $key => $entry) {
        if ($entry["token"] === $token) {

            // 将数据移到 审核中的id详细信息 'pending.json'
            $pending = json_decode(file_get_contents(__DIR__ . "/Assets/data/json/pending.json"), true);
            $pending[] = [
                "id" => $entry["id"],
                "domain" => $entry["domain"],
                "description" => $entry["description"],
                "master" => $entry["master"],
                "email" => $entry["email"],
                "join_date" => $date = date("F j, Y, g:i a")
            ];
            file_put_contents(__DIR__ . "/Assets/data/json/pending.json", json_encode($pending));
            // 从 '等待邮箱验证' 中删除该记录
            unset($pendingVerification[$key]);
            file_put_contents(
                __DIR__ . "/Assets/data/json/pending_email_verification.json",
                json_encode(array_values($pendingVerification))
            );
            // 读取 'pendingid.json' 并追加新的ID
            $pendingId = json_decode(file_get_contents(__DIR__ . "/Assets/data/json/pendingid.json"), true);
            // 生成新的键值对，键值对的键为递增的最大整数，值为该 ID
            $newKey = (!empty($pendingId)) ? (string)(max(array_keys($pendingId)) + 1) : "0";
            $pendingId[$newKey] = $entry["id"];
            // 将更新后的数据写入 'pendingid.json'
            file_put_contents(__DIR__ . "/Assets/data/json/pendingid.json", json_encode($pendingId));

            if (checkDomain($entry["domain"])) {
                echo '<center><h1>恭喜，邮箱验证通过请等待管理员通过！</h1></center>';
            } else {
                echo '<center><h1>审核不通过。请检查您的网站底部是否悬挂 NyaICP 标识。 <br> 如果您认为这是系统错误请联系管理员手动通过。</h1></center>';
            }
            exit;
        }
    }
    http_response_code(404);
} else {
    http_response_code(404);
}
