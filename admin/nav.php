<div class="mdui-appbar mdui-appbar-fixed">
    <div class="mdui-toolbar mdui-color-theme">
        <!-- 侧边栏切换按钮 -->
        <button class="mdui-btn mdui-btn-icon" mdui-drawer="{target: '#sidebar'}">
            <i class="mdui-icon material-icons">menu</i>
        </button>
        <span class="mdui-typo-title">后台管理系统</span>
    </div>
</div>

<div class="mdui-drawer mdui-drawer-close" id="sidebar" style="height: 400px;">
    <div class="mdui-list" style="padding-top: 20px;">
        <a href="index.php?password=<?php echo htmlspecialchars($_GET["password"]); ?>" style="padding-bottom: 18px;" class="mdui-list-item mdui-ripple">
            <i class="mdui-list-item-icon mdui-icon material-icons">home</i>
            <div class="mdui-list-item-content">返回后台首页</div>
        </a>
        <div class="mdui-divider"></div>
        <!-- 菜单项 -->
        <a href="index1.php?password=<?php echo htmlspecialchars($_GET["password"]); ?>"
            class="mdui-list-item mdui-ripple mdui-list-item-active">
            <i class="mdui-list-item-icon mdui-icon material-icons">hourglass_empty</i>
            <div class="mdui-list-item-content">等待审核列表</div>
        </a>

        <a href="index2.php?password=<?php echo htmlspecialchars($_GET["password"]); ?>"
            class="mdui-list-item mdui-ripple">
            <i class="mdui-list-item-icon mdui-icon material-icons">list_alt</i>
            <div class="mdui-list-item-content">主列表</div>
        </a>

        <a href="index3.php?password=<?php echo htmlspecialchars($_GET["password"]); ?>"
            class="mdui-list-item mdui-ripple">
            <i class="mdui-list-item-icon mdui-icon material-icons">announcement</i>
            <div class="mdui-list-item-content">编辑公告</div>
        </a>

        <a href="index4.php?password=<?php echo htmlspecialchars($_GET["password"]); ?>"
            class="mdui-list-item mdui-ripple">
            <i class="mdui-list-item-icon mdui-icon material-icons">email</i>
            <div class="mdui-list-item-content">批量发送邮件</div>
        </a>
    </div>
    <a href="#mdui-dialog" class="mdui-btn mdui-list-item mdui-ripple" mdui-dialog="{target: '#music-player-settings'}">
        打开音乐播放器设置
    </a>
</div>