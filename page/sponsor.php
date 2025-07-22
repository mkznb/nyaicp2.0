<?php
require_once __DIR__ . '/../Assets/module/head.php'
?>
<div class="mdui-card liuyanban-head box-shadow">
    <div class="mdui-card-primary">
        <div class="mdui-card-primary-title">
            <h4 class="mdui-text-center"><?php echo htmlspecialchars($global_site_title); ?>捐赠</h3>
        </div>
    </div>
</div>

<div class="mdui-container">
    <div class="mdui-card-content">
        <div id="main-content" class="mdui-typo">
            <h3>本站目前使用的是免费主机，而且还套着CDN</h3>
            <p>如果您慷慨一点麻烦捐赠一下我们吧!<br>
                <br>哪怕是<big>五元</big>还是<big>两元</big>
                <br>我们<big>都能接受！</big>
                <br>因为站主真的穷的<big>连裤衩子都不剩了</big>
            </p>
            <p>
                <img src="https://img.znzx.cc/upload/8e9665bef118764068f4626e7161d8d7" weigh=300px height=500px>
                <img src="https://img.znzx.cc/upload/dad04ac25b7c785c0e2ef5bda7aa219a" weigh=300px height=500px>
            </p>
            <h2>如果您捐赠我们了，</h2>
            <br>
            <p>请告诉我们，我们的联系邮箱是<big>cafe@merox.im</big><br>谢谢！</p>
            <style type="text/css">
                #pmp {
                    font-family: "微软雅黑";
                    font-size: 20px;
                    color: #00FFFF;
                    text-decoration: none;
                    height: 50px;
                    width: 350px;
                    border: 5px ridge #999;
                    background-color: #222;
                    padding-top: 8px;
                    padding-right: 5px;
                    padding-left: 5px;
                }
            </style>
            <div id="pmp">
                <marquee scrollamount="5" scrolldelay="40" direction="left" onmouseover="this.stop()" onmouseout="this.start()">当前暂时没有捐赠的人员啊</marquee>
            </div>
        </div>
    </div>
</div>
<?php
require_once __DIR__ . '/../Assets/module/footer.php'
?>