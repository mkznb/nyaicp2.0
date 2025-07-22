<?php
include __DIR__ . "/../password.php";
if (!empty($_GET["password"]) && $_GET["password"] == $password) {
?>
    <!DOCTYPE html>
    <html lang="zh-CN">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>域名列表の后台 - 邮件发送</title>
        <link rel="stylesheet" href="/Assets/css/mdui.min.css">
        <link href="https://cdn.bootcdn.net/ajax/libs/nprogress/0.2.0/nprogress.min.css" rel="stylesheet">
        <script src="https://cdn.bootcdn.net/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
        <script src="https://cdn.bootcdn.net/ajax/libs/wangeditor5/5.1.23/index.min.js"></script>
    </head>

    <body class="mdui-theme-primary-indigo mdui-appbar-with-toolbar">
        <?php include __DIR__ . "/nav.php"; ?>
        <div class="mdui-container mdui-typo">
            <style>
                .result-message {
                    margin-top: 20px;
                    padding: 15px;
                    border-radius: 4px;
                }

                .success {
                    background-color: #e8f5e9;
                    color: #2e7d32;
                }

                .error {
                    background-color: #ffebee;
                    color: #c62828;
                }

                .partial {
                    background-color: #fff8e1;
                    color: #f57f17;
                }

                .recipients-table {
                    margin: 16px 0;
                    max-height: 400px;
                    overflow-y: auto;
                }
            </style>
            <header>
                <h1 class="mdui-typo-display-1">
                    域名列表の后台 - 邮件发送
                </h1>
                <div class="mdui-divider"></div>
            </header>

            <main class="mail-container">
                <?php require_once __DIR__ . '/index4_function.php'; ?>

                <form method="post" class="mail-form" enctype="multipart/form-data">
                    <input type="hidden" name="password" value="<?php echo htmlspecialchars($_GET["password"]); ?>">

                    <div class="mdui-textfield">
                        <label class="mdui-textfield-label">收件人</label>
                        <div class="recipients-table">
                            <table class="mdui-table mdui-table-selectable mdui-table-hoverable">
                                <thead style="position: sticky; top: 0; z-index: 9" class="mdui-color-grey-50">
                                    <tr>
                                        <th>域名</th>
                                        <th>申请人</th>
                                        <th>邮箱</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($allUsers as $user): ?>
                                        <tr class="">
                                            <td><?php echo htmlspecialchars($user['domain']); ?></td>
                                            <td><?php echo htmlspecialchars($user['master']); ?></td>
                                            <td>
                                                <input type="checkbox" name="recipients[]" value="<?php echo htmlspecialchars($user['email']); ?>" checked style="display:none">
                                                <?php echo htmlspecialchars($user['email']); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mdui-textfield">
                        <label class="mdui-textfield-label">邮件主题</label>
                        <input class="mdui-textfield-input" type="text" name="subject" required>
                    </div>

                    <div class="mdui-textfield">
                        <label class="mdui-textfield-label">邮件内容 (HTML)</label>
                        <textarea class="mdui-textfield-input" name="body" rows="6" required></textarea>
                    </div>

                    <div class="mdui-textfield">
                        <label class="mdui-textfield-label">纯文本内容 (可选)</label>
                        <textarea class="mdui-textfield-input" name="altBody" rows="3"></textarea>
                    </div>

                    <div class="mdui-textfield">
                        <label class="mdui-textfield-label">附件 (可选)</label>
                        <input type="file" name="attachments[]" multiple class="mdui-btn mdui-btn-block">
                    </div>

                    <button type="submit" name="send_mail" class="mdui-btn mdui-btn-block mdui-ripple mdui-color-theme-accent mdui-m-t-4">发送邮件</button>
                </form>
            </main>

            <script>
                // 行点击事件
                const table = document.querySelector('.mdui-table');
                if (table) {
                    table.addEventListener('click', function(e) {
                        const row = e.target.closest('tr');
                        if (row && row.parentNode.tagName === 'TBODY') {
                            const checkbox = row.querySelector('input[type="checkbox"]');
                            if (checkbox) {
                                checkbox.checked = !checkbox.checked;
                                if (checkbox.checked) {
                                    row.classList.add('mdui-table-row-selected');
                                } else {
                                    row.classList.remove('mdui-table-row-selected');
                                }
                            }
                        }
                    });
                }

                // 自动隐藏消息
                setTimeout(function() {
                    var messages = document.querySelectorAll('.result-message');
                    messages.forEach(function(msg) {
                        msg.style.opacity = '0';
                        setTimeout(function() {
                            msg.style.display = 'none';
                        }, 500);
                    });
                }, 5000);
            </script>
        </div>

        <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.bootcdn.net/ajax/libs/jquery.pjax/2.0.1/jquery.pjax.min.js"></script>
        <script src="https://cdn.bootcdn.net/ajax/libs/mdui/1.0.2/js/mdui.min.js"></script>
        <?php require_once __DIR__ . '/common.php' ?>
    </body>

    </html>
<?php
} else {
    http_response_code(404);
}
