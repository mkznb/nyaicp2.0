<?php
header('Content-Type: application/json');

file_put_contents("fail.json", "[]");
include __DIR__ . "/../password.php";
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../Assets/helpers.php";
require_once __DIR__ . "/helpers.php";

if (!empty($_GET["password"]) && $_GET["password"] == $password) {
    $id = $_POST["id"] ?? null;
    $action = $_POST["action"] ?? null;

    switch ($action) {
        case 'pass':
            searchAndMoveValue(__DIR__ . "/../Assets/data/json/pending.json", __DIR__ . "/../Assets/data/json/idinfo.json", "id", $id);
            searchAndMoveValue2(__DIR__ . "/../Assets/data/json/pendingid.json", __DIR__ . "/../Assets/data/json/id.json", $id);

            // 获取审核通过的用户信息
            $userInfo = json_decode(file_get_contents(__DIR__ . "/../Assets/data/json/idinfo.json"), true);
            $userEmail = ""; // 初始化用户邮箱变量

            // 找到对应的用户信息
            foreach ($userInfo as $user) {
                if ($user['id'] === $id) {
                    $userEmail = $user['email'];
                    break;
                }
            }

            // 如果找到了用户的邮箱，发送审核通过邮件
            if (!empty($userEmail)) {
                $subject = "您的申请已通过审核";
                $body = "尊敬的用户，您的申请（ID: $id ）已经通过审核，感谢您的耐心等待！";
                sendMail($userEmail, $subject, $body);
            }
            break;
        case 'fail':
            searchAndMoveValue(__DIR__ . "/../Assets/data/json/pending.json", "fail.json", "id", $id);
            searchAndMoveValue2(__DIR__ . "/../Assets/data/json/pendingid.json", "fail.json", $id);
            break;
    }

    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    http_response_code(404);
}
