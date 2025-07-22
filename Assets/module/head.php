<?php
require_once __DIR__ . '/../../init.php';
?>
<!-- 
    阿伟，又在扒源码啊，休息一下好不好
-->
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($global_site_title); ?></title>
    <link rel="shortcut icon" href="https://xnn.asia/icptb/" />
    <link rel="stylesheet" href="/Assets/css/mdui.min.css">
    <link rel="stylesheet" href="/Assets/css/custom.css?v=0.2.6">
    <link href="https://cdn.bootcdn.net/ajax/libs/nprogress/0.2.0/nprogress.min.css" rel="stylesheet">
    <script src="https://cdn.bootcdn.net/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/Assets/js/mdui.min.js"></script>
    <script>
        NProgress.configure({
            minimum: 0.08,
            showSpinner: true,
            parent: '#pjax'
        })
        // 监听页面加载事件
        window.addEventListener('load', () => {
            NProgress.done();
            mdui.mutation();
        });
        // 监听资源加载事件
        document.addEventListener('readystatechange', () => {
            if (document.readyState === 'interactive') {
                NProgress.start();
                mdui.mutation();
            } else if (document.readyState === 'complete') {
                NProgress.done();
                mdui.mutation();
            }
        });
    </script>
    <style>
        button {
            display: inline-block;
        }

        .ol {
            color: white ! important;
            text-decoration: none ! important;
            transition: color 0.2s ease-in-out ! important;
        }

        .ol:hover {
            color: skyblue ! important;
        }
    </style>
</head>

<body class="mdui-theme-layout-auto">
    <!-- 音乐播放器 - 来自小枫_QWQ -->
    <div id="music-player-container"></div>
    <!-- 公告弹窗 -->
    <div class="mdui-dialog" id="notice">
        <div class="mdui-dialog-title"><?= $global_site_title ?>公告</div>
        <div class="mdui-dialog-content mdui-typo">
            <?php
            $Parsedown = new Parsedown();
            echo $Parsedown->text($global_site_notice);
            ?>
        </div>
        <div class="mdui-dialog-actions">
            <button class="mdui-btn mdui-ripple" mdui-dialog-close id="acknowledgeBtn">我看到了喵～</button>
        </div>
    </div>
    <div class="content-container">
        <div class="mdui-appbar mdui-appbar-fixed">
            <div class="mdui-toolbar mdui-color-pink-400">
                <a href="/">
                    <img src="https://img.moehu.org/pic.php?id=gcmm&cdn=cf&size=mw1024" alt="Profile Image" class="profile-img">
                </a>
                <button class="mdui-btn mdui-color-theme-accent" mdui-dialog="{target: '#notice'}">站点公告</button>
                <div class="mdui-toolbar-spacer"></div>
                <button class="mdui-btn mdui-color-theme-accent" mdui-menu="{target: '#appbarLink'}">更多喵~</button>
                <ul class="mdui-menu" id="appbarLink">
                    <li class="mdui-menu-item">
                        <a href="/page/notice.php">站点公告</a>
                    </li>
                    <li class="mdui-menu-item">
                        <a href="/page/liuyanban">留个言喵~</a>
                    </li>
                    <li class="mdui-menu-item">
                        <a href="/page/about.php">关于我们备案喵</a>
                    </li>
                    <li class="mdui-menu-item">
                        <a href="/page/sponsor.php">捐赠一下喵~</a>
                    </li>
                    <li class="mdui-menu-item">
                        <a href="/page/questions.php">部分问题解决</a>
                    </li>
                    <li class="mdui-menu-item">
                        <button class="mdui-btn mdui-ripple" mdui-dialog="{target: '#music-player-settings'}">
                            打开音乐播放器设置
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <div id="pjax" class="mdui-card mdui-m-x-2 mdui-p-x-2 mdui-p-b-2 box-shadow" style="margin-top: 80px;">