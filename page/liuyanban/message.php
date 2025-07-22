<?php
header('Content-Type: application/json');
// 强制设置时区为国内
date_default_timezone_set("Asia/Shanghai");

$file = 'list.json';

// 确保文件存在，如果不存在则创建一个空文件
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $avatar = htmlspecialchars($_POST['avatar']);
    $message = htmlspecialchars($_POST['message']);
    $date = date("F j, Y, g:i a");
    $timestamp = time();

    $list = json_decode(file_get_contents($file), true);
    if (!is_array($list)) {
        $list = [];
    }

    $list[] = [
        "name" => $name,
        "avatar" => $avatar,
        "message" => $message,
        "timestamp" =>  $timestamp,
        "date" => $date
    ];

    file_put_contents($file, json_encode($list, JSON_PRETTY_PRINT));

    echo json_encode([
        "status" => "success",
        "data" => $list
    ]);
} else {
    $data = json_decode(file_get_contents($file), true);
    if (!is_array($data)) {
        $data = [];
    }

    // 反向排序
    usort($data, function ($a, $b) {
        return $b['timestamp'] <=> $a['timestamp'];
    });

    echo json_encode($data);
}
