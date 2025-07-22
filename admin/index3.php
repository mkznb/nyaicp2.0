<?php
include __DIR__ . "/../password.php";

if (!empty($_GET["password"]) && $_GET["password"] == $password) {
    require_once __DIR__ . '/index3_function.php';
?>
    <!DOCTYPE html>
    <html lang="zh-CN">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>编辑公告の后台</title>
        <link rel="stylesheet" href="/Assets/css/mdui.min.css">
        <link href="https://cdn.bootcdn.net/ajax/libs/nprogress/0.2.0/nprogress.min.css" rel="stylesheet">
        <script src="https://cdn.bootcdn.net/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
        <script src="https://cdn.bootcdn.net/ajax/libs/wangeditor5/5.1.23/index.min.js"></script>
    </head>

    <body class="mdui-theme-primary-indigo mdui-appbar-with-toolbar">
        <?php include __DIR__ . "/nav.php"; ?>
        <div class="mdui-container mdui-typo">
            <style>
                #editor—wrapper {
                    border: 1px solid #ccc;
                    z-index: 99999999999999999999;
                }

                #toolbar-container {
                    border-bottom: 1px solid #ccc;
                }

                #editor-container {
                    height: 500px;
                }
            </style>
            <link href="https://cdn.bootcdn.net/ajax/libs/wangeditor5/5.1.23/css/style.min.css" rel="stylesheet" />
            <script src="/Assets/js/jquery.min.js"></script>
            <h1 class="mdui-typo-display-1">
                编辑公告の后台
            </h1>
            <div class="mdui-divider"></div>
            <?php if (isset($_GET['success'])): ?>
                <div class="mdui-alert mdui-alert-<?php echo $_GET['success'] === 'true' ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($_GET['msg'] ?? ($_GET['success'] === 'true' ? '操作成功！' : '操作失败！')); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="mdui-typo">
                <div id="editor—wrapper">
                    <div id="toolbar-container"></div>
                    <div id="editor-container"></div>
                </div>
                <input type="hidden" id="editor" name="notice_content" value="<?= htmlspecialchars(file_get_contents("$global_assets_data_dir/notice.md")); ?>">
                <button type="submit" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent mdui-m-t-1">保存</button>
            </form>

            <!-- 备份文件表格 -->
            <div class="mdui-table-fluid mdui-m-t-4" style="margin-bottom: 50px;">
                <table class="mdui-table mdui-table-hoverable">
                    <thead>
                        <tr>
                            <th>时间</th>
                            <th>大小</th>
                            <th>内容预览</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $backupFiles = glob("$global_assets_data_dir/notice_backup_*.md");
                        rsort($backupFiles); // 按时间倒序排列

                        if (empty($backupFiles)) {
                            echo '<tr><td colspan="5" class="mdui-text-center">暂无备份文件</td></tr>';
                        } else {
                            foreach ($backupFiles as $backupFile) {
                                $filename = basename($backupFile);
                                $filetime = date('Y-m-d H:i:s', filemtime($backupFile));
                                $filesize = round(filesize($backupFile) / 1024, 2) . ' KB';
                                $content = htmlspecialchars(file_get_contents($backupFile));
                                $preview = strlen($content) > 50 ? substr($content, 0, 50) . '...' : $content;
                                echo "
                                <tr>
                                    <td>{$filetime}</td>
                                    <td>{$filesize}</td>
                                    <td>{$preview}</td>
                                    <td>
                                        <a href='?password={$_GET['password']}&restore=1&backup_file={$filename}' 
                                            class='mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent'
                                            onclick='return confirm(\"确定要恢复 {$preview}？当前内容将被覆盖。\")'>
                                            恢复
                                        </a>
                                        <a href='javascript:void(0)' 
                                            class='mdui-btn mdui-btn-raised mdui-ripple'
                                            onclick='previewBackup(`" . addslashes($content) . "`)'>
                                            预览
                                        </a>
                                    </td>
                                </tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- 预览模态框 -->
            <div class="mdui-dialog" id="preview-dialog">
                <div class="mdui-dialog-title">备份内容预览</div>
                <div class="mdui-dialog-content" id="preview-content" style="padding: 20px;"></div>
                <div class="mdui-dialog-actions">
                    <button class="mdui-btn mdui-ripple" mdui-dialog-close>关闭</button>
                </div>
            </div>

            <script>
                if (typeof window.editorInitialized === 'undefined') {
                    const {
                        createEditor,
                        createToolbar
                    } = window.wangEditor

                    // 编辑器配置
                    const editorConfig = {
                        placeholder: '请输入公告内容...',
                        onChange(editor) {
                            const html = editor.getHtml()
                            $('#editor').val(html) // 同步到隐藏的textarea
                        },
                        MENU_CONF: {
                            uploadImage: {
                                server: window.location.href,
                                fieldName: 'editor-file',
                                maxFileSize: 10 * 1024 * 1024, // 10M
                                allowedFileTypes: ['image/*'],
                                meta: {
                                    password: '<?= htmlspecialchars($_GET["password"] ?? "") ?>'
                                },
                                customInsert(res, insertFn) {
                                    if (res.errno === 0) {
                                        insertFn(res.data.url, res.data.alt, res.data.href)
                                    }
                                }
                            },
                            uploadVideo: {
                                server: window.location.href,
                                fieldName: 'editor-file',
                                maxFileSize: 50 * 1024 * 1024, // 50M
                                allowedFileTypes: ['video/*'],
                                meta: {
                                    password: '<?= htmlspecialchars($_GET["password"] ?? "") ?>'
                                },
                                customInsert(res, insertFn) {
                                    if (res.errno === 0) {
                                        insertFn(res.data.url)
                                    }
                                }
                            },
                            uploadFile: {
                                server: window.location.href,
                                fieldName: 'editor-file',
                                maxFileSize: 50 * 1024 * 1024, // 50M
                                allowedFileTypes: ['*/*'],
                                meta: {
                                    password: '<?= htmlspecialchars($_GET["password"] ?? "") ?>'
                                },
                                customInsert(res, insertFn) {
                                    if (res.errno === 0) {
                                        insertFn(res.data.url, res.data.alt || '下载文件')
                                    }
                                }
                            }
                        }
                    }

                    // 创建编辑器
                    const editor = createEditor({
                        selector: '#editor-container',
                        html: `<?= addslashes(file_get_contents("$global_assets_data_dir/notice.md")); ?>`,
                        config: editorConfig,
                        mode: 'default'
                    })

                    // 创建工具栏
                    const toolbar = createToolbar({
                        editor,
                        selector: '#toolbar-container',
                        config: {},
                        mode: 'default'
                    })
                }
                // 备份预览功能
                function previewBackup(content) {
                    var previewContent = document.getElementById('preview-content');
                    previewContent.innerHTML = marked.parse(content);
                    new mdui.Dialog('#preview-dialog').open();
                }
            </script>
        </div>

        <!-- 引入必要的JS库 -->
        <script src="https://lf6-cdn-tos.bytecdntp.com/cdn/expire-1-M/mdui/1.0.2/js/mdui.min.js"></script>
        <script src="https://lf6-cdn-tos.bytecdntp.com/cdn/expire-1-M/marked/4.0.2/marked.min.js"></script>
        <script src="https://cdn.bootcdn.net/ajax/libs/jquery.pjax/2.0.1/jquery.pjax.min.js"></script>
        <?php require_once __DIR__ . '/common.php' ?>
    </body>

    </html>
<?php
} else {
    http_response_code(404);
}
