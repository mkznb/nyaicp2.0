<?php
include __DIR__ . "/../password.php";

if (!empty($_GET["password"]) && $_GET["password"] == $password) {
    // 读取数据文件
    $pendingFile = __DIR__ . "/../Assets/data/json/pending.json";
    $approvedFile = __DIR__ . "/../Assets/data/json/idinfo.json";

    $pendingCount = file_exists($pendingFile) ? count(json_decode(file_get_contents($pendingFile), true)) : 0;
    $approvedCount = file_exists($approvedFile) ? count(json_decode(file_get_contents($approvedFile), true)) : 0;
?>
    <!DOCTYPE html>
    <html lang="zh-CN">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>喵喵后台主页</title>
        <link rel="stylesheet" href="/Assets/css/mdui.min.css">
        <link href="https://cdn.bootcdn.net/ajax/libs/nprogress/0.2.0/nprogress.min.css" rel="stylesheet">
        <script src="https://cdn.bootcdn.net/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
        <script src="https://cdn.bootcdn.net/ajax/libs/wangeditor5/5.1.23/index.min.js"></script>
    </head>

    <body class="mdui-theme-primary-indigo mdui-appbar-with-toolbar">
        <?php include __DIR__ . "/nav.php"; ?>
        <div class="mdui-container mdui-typo">
            <style>
                .dashboard-container {
                    margin-top: 50px;
                    display: flex;
                    flex-wrap: wrap;
                    gap: 20px;
                    justify-content: center;
                }

                .mdui-card {
                    width: 300px;
                }

                .mdui-card-primary {
                    padding-bottom: 0;
                }

                .mdui-card-content {
                    padding-top: 0;
                }

                .stats {
                    margin-top: 10px;
                    font-size: 14px;
                    color: rgba(0, 0, 0, 0.54);
                }

                .mdui-typo-display-1 {
                    margin-bottom: 24px;
                }
            </style>
            <h1 class="mdui-typo-display-1">喵喵备案后台主页</h1>
            <div class="mdui-divider"></div>

            <div class="dashboard-container">
                <!-- 等待审核列表卡片 -->
                <div class="mdui-card mdui-hoverable">
                    <a href="index1.php?password=<?php echo htmlspecialchars($_GET["password"]); ?>">
                        <div class="mdui-card-primary">
                            <div class="mdui-card-primary-title">等待审核列表</div>
                            <div class="mdui-card-primary-subtitle">查看并管理待审核的域名申请</div>
                        </div>
                        <div class="mdui-card-content">
                            <div class="stats">等待审核数量：<?php echo $pendingCount; ?></div>
                        </div>
                    </a>
                </div>

                <!-- 主列表卡片 -->
                <div class="mdui-card mdui-hoverable">
                    <a href="index2.php?password=<?php echo htmlspecialchars($_GET["password"]); ?>">
                        <div class="mdui-card-primary">
                            <div class="mdui-card-primary-title">主列表</div>
                            <div class="mdui-card-primary-subtitle">管理所有已审核的域名记录</div>
                        </div>
                        <div class="mdui-card-content">
                            <div class="stats">已通过数量：<?php echo $approvedCount; ?></div>
                        </div>
                    </a>
                </div>

                <!-- 编辑公告卡片 -->
                <div class="mdui-card mdui-hoverable">
                    <a href="index3.php?password=<?php echo htmlspecialchars($_GET["password"]); ?>">
                        <div class="mdui-card-primary">
                            <div class="mdui-card-primary-title">编辑公告</div>
                            <div class="mdui-card-primary-subtitle">发布或修改公告内容</div>
                        </div>
                    </a>
                </div>

                <!-- 批量发送邮件卡片 -->
                <div class="mdui-card mdui-hoverable">
                    <a href="index4.php?password=<?php echo htmlspecialchars($_GET["password"]); ?>">
                        <div class="mdui-card-primary">
                            <div class="mdui-card-primary-title">批量发送邮件</div>
                            <div class="mdui-card-primary-subtitle">向用户发送批量邮件通知</div>
                        </div>
                    </a>
                </div>
            </div>
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
