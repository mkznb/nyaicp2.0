<?php
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../Assets/helpers.php";
require_once __DIR__ . "/helpers.php";
require_once __DIR__ . "/../init.php";

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_mail'])) {
    $recipients = [];
    if (isset($_POST['recipients']) && is_array($_POST['recipients'])) {
        $recipients = $_POST['recipients'];
    }

    $subject = $_POST['subject'] ?? '';
    $body = $_POST['body'] ?? '';
    $altBody = $_POST['altBody'] ?? '';

    // 处理附件上传
    $attachments = [];
    if (!empty($_FILES['attachments']['name'][0])) {
        $totalFiles = count($_FILES['attachments']['name']);
        for ($i = 0; $i < $totalFiles; $i++) {
            if ($_FILES['attachments']['error'][$i] === UPLOAD_ERR_OK) {
                $tmpPath = $_FILES['attachments']['tmp_name'][$i];
                $fileName = $_FILES['attachments']['name'][$i];
                $newPath = __DIR__ . '/../Assets/data/tmp/' . uniqid() . '_' . $fileName;

                if (move_uploaded_file($tmpPath, $newPath)) {
                    $attachments[] = [
                        'path' => $newPath,
                        'name' => $fileName
                    ];
                }
            }
        }
    }

    // 发送邮件
    $result = sendMail($recipients, $subject, $body, $altBody, $attachments);

    // 清理临时附件
    foreach ($attachments as $attachment) {
        if (file_exists($attachment['path'])) {
            unlink($attachment['path']);
        }
    }

    // 显示结果
    if ($result === true) {
        echo '<div class="result-message success">邮件发送成功！</div>';
    } else if (is_array($result)) {
        echo '<div class="result-message partial">';
        echo '<h4>部分邮件发送失败：</h4>';
        echo '<ul>';
        foreach ($result as $email => $error) {
            echo '<li>' . htmlspecialchars($email) . ': ' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul>';
        echo '</div>';
    } else {
        echo '<div class="result-message error">邮件发送失败！</div>';
    }
}

// 获取所有用户的邮箱
$allUsers = json_decode(file_get_contents(__DIR__ . "/../Assets/data/json/idinfo.json"), true);
$emails = array_column($allUsers, 'email', 'id');