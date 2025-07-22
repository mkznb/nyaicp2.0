</div><!-- id="pjax" end -->
</div><!-- class="content-container" end -->
<!-- 页脚 -->
<footer class="content-container">
    <h3 tyle="font-size: 14px; color: #00796b; margin-bottom: 5px;">
        &copy; 2025 喵喵兀系统V2.1.0 All Rights Reserved & 技术支持： 小枫_QWQ
        <br>
        <br>
        <span id="runtime"></span>
    </h3>
    <h3 style="font-size: 14px; color: #777; margin-bottom: 15px;">
        <script charset="UTF-8" id="LA_COLLECT" src="//sdk.51.la/js-sdk-pro.min.js?id=3JR17SEYZeeiFJ60&ck=3JR17SEYZeeiFJ60&autoTrack=true&hashMode=true"></script>
    </h3>
    <h3 style="font-size: 14px; color: #777; margin-bottom: 15px;">
    </h3>
    <div style="display: flex; justify-content: center; align-items: center; flex-wrap: wrap; margin-bottom: 15px; gap: 10px;">
        <div class="footer-link">
            <img src="https://moe.one/view/img/ico64.png" alt="MoeICP" class="footer-icon">
            <a href="https://icp.gov.moe/?keyword=20240065" target="_blank" class="footer-anchor">MoeICP</a>
        </div>
        <div class="footer-divider">丨</div>
        <div class="footer-link">
            <img src="https://icp.gs/assets/images/icp.png" width="20">
            <a href="https://icp.gs/?id=20240791" class="footer-anchor">岸号</a>
        </div>
        <div class="footer-divider">丨</div>
        <div class="footer-link">
            <img src="https://icp.n3v.cn/uploads/allimg/20240413/1-240409203122D3.png" width="20">
            <a href="https://icp.n3v.cn" class="footer-anchor">易备</a>
        </div>
        <div class="footer-divider">丨</div>
        <div class="footer-link">
            <img src="https://icp.id.cd/static/picture/icplogoi.png" width="20">
            <a href="https://icp.id.cd/beian/ICP-2024100059.html" class="footer-anchor">IDCD联盟</a>
        </div>
    </div>
</footer>

<script type="text/javascript">
    function runtime() {
        const startTime = new Date("2024-04-10T16:50:00");
        const currentTime = new Date();
        const elapsedTime = currentTime - startTime;

        const days = Math.floor(elapsedTime / (1000 * 60 * 60 * 24));
        const hours = Math.floor((elapsedTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((elapsedTime % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((elapsedTime % (1000 * 60)) / 1000);

        document.getElementById('runtime').textContent = `本站已运行了 ${days} 天 ${hours} 时 ${minutes} 分 ${seconds} 秒了喵~`;
    }
    setInterval(runtime, 1000);
</script>

<style>
    .footer-link {
        display: flex;
        align-items: center;
    }

    .footer-icon {
        height: 20px;
        margin-right: 5px;
    }

    .footer-anchor {
        text-decoration: none;
        color: #00796b;
    }

    .footer-divider {
        margin: 0 10px;
        color: #777;
    }
</style>

<script src="https://cdn.bootcdn.net/ajax/libs/jquery.pjax/2.0.1/jquery.pjax.min.js"></script>
<script src="https://cdn.staticfile.org/aplayer/1.10.1/APlayer.min.js"></script>
<!-- 音乐播放器 - 来自小枫_QWQ -->
<script src="/Assets/js/music-player/main.js?v=0.1.14"></script>
<script>
    const musicPlayer = new MusicPlayerSettings();
    musicPlayer.main();

    // Pjax 初始化以及相关配置
    $(document).pjax('a:not(a[target="_blank"],a[no-pjax])', {
        container: '#pjax',
        fragment: '#pjax',
        timeout: 20000
    });

    // Pjax 请求发送时显示进度条
    $(document).on('pjax:send', function() {
        NProgress.start();
    });

    // Pjax 请求结束时隐藏进度条并重新绑定表单事件
    $(document).on('pjax:end', function() {
        NProgress.done();
    });

    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/";
    }

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    function checkNotice() {
        const noticeAcknowledged = getCookie('noticeAcknowledged');
        if (!noticeAcknowledged) {
            new mdui.Dialog('#notice').open();
        }
    }

    document.getElementById('acknowledgeBtn').addEventListener('click', function() {
        setCookie('noticeAcknowledged', 'true', 1); // 设置 cookie，有效期1天
    });

    // 页面加载时检查是否需要显示公告
    checkNotice();
</script>
</body>

</html>