<?php
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../Assets/helpers.php";
require_once __DIR__ . "/helpers.php";
require_once __DIR__ . "/../init.php";

// 添加上传处理逻辑
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['editor-file'])) {
    header('Content-Type: application/json');

    $uploadDir = __DIR__ . '/../Assets/uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $file = $_FILES['editor-file'];
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $ext;
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $url = '/Assets/uploads/' . $filename;
        echo json_encode([
            'errno' => 0,
            'data' => [
                'url' => $url,
                'alt' => $file['name'],
                'href' => $url
            ]
        ]);
    } else {
        echo json_encode(['errno' => 1, 'message' => '上传失败']);
    }
    exit;
}

// 处理备份恢复请求
if (isset($_GET['restore']) && !empty($_GET['backup_file'])) {
    $backupFile = basename($_GET['backup_file']);
    $backupPath = "$global_assets_data_dir/$backupFile";
    $currentFile = "$global_assets_data_dir/notice.md";

    if (file_exists($backupPath)) {
        // 创建恢复前的备份
        $restoreBackupPath = dirname($currentFile) . '/notice_restore_backup_' . date('Ymd_His') . '.md';
        copy($currentFile, $restoreBackupPath);

        // 执行恢复
        if (copy($backupPath, $currentFile)) {
            header("Location: ?password={$_GET['password']}&success=true&msg=恢复成功");
            exit;
        } else {
            header("Location: ?password={$_GET['password']}&success=false&msg=恢复失败");
            exit;
        }
    } else {
        header("Location: ?password={$_GET['password']}&success=false&msg=备份文件不存在");
        exit;
    }
}

// 处理公告保存
if (!empty($_POST["notice_content"])) {
    $filePath = "$global_assets_data_dir/notice.md";

    // 备份旧的公告文件
    if (file_exists($filePath)) {
        $backupFilePath = dirname($filePath) . '/notice_backup_' . date('Ymd_His') . '.md';
        if (!copy($filePath, $backupFilePath)) {
            echo ('公告文件备份失败，但不影响更新公告');
        }
    }

    file_put_contents($filePath, $_POST['notice_content']);

    header("Location: ?password={$_GET['password']}&success=true&msg=保存成功");
    exit;
}