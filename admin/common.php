<div id="music-player-container"></div>

<script src="https://cdn.staticfile.org/aplayer/1.10.1/APlayer.min.js"></script>
<!-- 音乐播放器 - 来自小枫_QWQ -->
<script src="/Assets/js/music-player/main.js?v=0.1.14"></script>
<script>
    const musicPlayer = new MusicPlayerSettings();
    musicPlayer.main();
    // 当前页面导航栏高亮
    const currentPath = window.location.pathname;
    const currentPage = currentPath.split('/').pop() || 'index.php'; // 默认值
    document.querySelectorAll('.mdui-list-item').forEach(item => {
        if (!item.hasAttribute('href')) return; // 跳过没有href的项
        item.classList.remove('mdui-list-item-active');
        const itemHref = item.getAttribute('href');
        // 比较逻辑优化
        if (itemHref && (
                itemHref.includes(currentPage) ||
                (currentPage === 'index.php' && itemHref.includes('index.php'))
            )) {
            item.classList.add('mdui-list-item-active');
        }
    });
    // 添加侧边栏切换功能
    $(document).pjax('a:not(a[target="_blank"],a[no-pjax])', {
        container: '.mdui-container',
        fragment: '.mdui-container',
        timeout: 20000
    });
    $(document).on('pjax:end', function() {
        const currentPath = window.location.pathname;
        const currentPage = currentPath.split('/').pop() || 'index.php'; // 默认值
        document.querySelectorAll('.mdui-list-item').forEach(item => {
            if (!item.hasAttribute('href')) return; // 跳过没有href的项
            item.classList.remove('mdui-list-item-active');
            const itemHref = item.getAttribute('href');
            // 比较逻辑优化
            if (itemHref && (
                    itemHref.includes(currentPage) ||
                    (currentPage === 'index.php' && itemHref.includes('index.php'))
                )) {
                item.classList.add('mdui-list-item-active');
            }
        });
        // 初始化MDUI组件
        mdui.mutation();
    });
    // 初始化MDUI组件
    mdui.mutation();
</script>