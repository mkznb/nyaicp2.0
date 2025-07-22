<?php
require_once __DIR__ . '/init.php';

function renderError()
{
    echo <<<HTML
        <h2 class="mdui-typo-display-1 mdui-m-l-2">是不是搞错了喵？</h2>
        <p class="mdui-typo-headline mdui-m-l-3 mdui-typo">该ID已被注册了诶！<a href=?step=1>点我重新选号</a></p>
    HTML;
}

function getRandomMDUIColor()
{
    $colors = [
        'red', 'pink', 'purple', 'deep-purple', 'indigo', 'blue', 
        'light-blue', 'cyan', 'teal', 'lime', 'yellow', 'amber', 
        'orange', 'deep-orange', 'brown', 'blue-grey'
    ];
    return $colors[array_rand($colors)];
}

function renderNumberSelection($global_data_id, $global_data_pendingid)
{
    $data = json_decode($global_data_id, true);
    $pendingid = json_decode($global_data_pendingid, true);
    $year = date("Y");

    $numbers = [];
    for ($j = 0; $j < 10000; $j++) {
        $numbers[] = $year . sprintf("%04d", $j);
    }

    $perPage = 100;
    $currentPage = isset($_GET["page"]) ? max(1, intval($_GET["page"])) : 1;
    $totalPages = ceil(count($numbers) / $perPage);
    $currentPage = min($currentPage, $totalPages);
    $startIndex = ($currentPage - 1) * $perPage;
    $currentPageNumbers = array_slice($numbers, $startIndex, $perPage);

    echo <<<HTML
        <div style="font-family:Roboto;">
            <h2 class="mdui-typo-display-1 mdui-m-l-2">来选一选你心爱的编号喵~</h2>
            <div class="mdui-container-fluid mdui-m-l-2">
    HTML;

    foreach ($currentPageNumbers as $number) {
        if (!in_array($number, $data) && !in_array($number, $pendingid)) {
            $color = getRandomMDUIColor();
            echo <<<HTML
                <div class="mdui-chip mdui-m-y-2 mdui-color-$color" style="margin: 10px 10px;">
                    <span class="mdui-chip-title">$number</span>
                    <a href="join.php?step=2&id=$number" class="mdui-btn mdui-ripple mdui-color-theme-accent">选好喵</a>
                </div>
            HTML;
        }
    }

    echo <<<HTML
            </div>
    HTML;

    renderPagination($currentPage, $totalPages, 'join.php', ['step' => 1]);

    echo <<<HTML
        </div>
    HTML;
}

function renderCodeSetup($id, $global_site_domain, $global_site_title)
{
    echo <<<HTML
        <h2 class="mdui-typo-display-1 mdui-m-l-2">请设置您的代码喵！</h2>
        <p class="mdui-typo-headline mdui-m-l-3">请在您的页脚添加下面的代码喵~</p>
        <big>
            <p>
                &lt;img style="width:20px;height:20px;margin-bottom:-4px" src="http://xnn.asia/icptb/"&gt;
                &lt;a href="https://$global_site_domain/?id=$id" target="_blank"&gt;喵呜 $id 号~&lt;/a&gt;
            </p>
        </big>
        <a href="?id=$id&step=3">
            <button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-teal">设置完了喵~</button>
        </a>
    HTML;
}

function renderForm($id)
{
    echo <<<HTML
        <h2 class="mdui-typo-display-1 mdui-m-l-2">提交信息喵</h2>
        <form action="complete.php" method="POST">
            <div class="mdui-textfield">
                <input class="mdui-textfield-input" type="text" name="id" placeholder="ID" value="$id" readonly />
            </div>
            <div class="mdui-textfield">
                <input class="mdui-textfield-input" type="text" name="domain" placeholder="网站域名" required />
            </div>
            <div class="mdui-textfield">
                <input class="mdui-textfield-input" type="text" name="description" placeholder="网站介绍" required />
            </div>
            <div class="mdui-textfield">
                <input class="mdui-textfield-input" type="text" name="master" placeholder="网站站主" required />
            </div>
            <div class="mdui-textfield">
                <input class="mdui-textfield-input" type="email" name="email" placeholder="邮箱" required />
            </div>
            <div style="display: flex;" class="mdui-textfield">
                <img src="/Assets/image/captcha.php" alt="验证码" />
                <input class="mdui-textfield-input" type="text" name="captcha" placeholder="请输入验证码" required />
            </div>
            <input type="submit" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-teal" value="完成喵~">
        </form>
    HTML;
}

function renderStart($global_site_title)
{
    echo <<<HTML
        <h2 class="mdui-typo-display-1 mdui-m-l-2">$global_site_title 申请</h2>
        <div class="mdui-typo-headline mdui-m-l-3">要求</div>
        <p>
            <big>
                <p style="font-size: 2vh;color: #f00;">网站内容不涉及商业/政治/色情/灰色/版权/破解/企业类</p>
            </big>
            <big>非空壳网站，能长期存活和更新，无违反道德公序良俗，会按要求完成与喵喵备正确的对接！</big><br>
            <big>申请约花费5分钟，审核约花费2-3天，请空闲时申请</big><br>
            <big>决定了，那就来选个号吧！</big>
        </p>
        <a href="?step=1">
            <button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-teal">点我开始加入喵~</button>
        </a>
    HTML;
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    if (in_array($id, json_decode($global_data_id, true), true) || in_array($id, json_decode($global_data_pendingid, true), true)) {
        header("Location: ?error=1");
        exit();
    }
}

require_once __DIR__ . '/Assets/module/head.php';

if (isset($_GET["error"]) && $_GET["error"] == 1) {
    renderError();
} else {
    if (isset($_GET["step"])) {
        switch ($_GET["step"]) {
            case 1:
                renderNumberSelection($global_data_id, $global_data_pendingid);
                break;
            case 2:
                if (isset($_GET["id"])) {
                    renderCodeSetup(htmlspecialchars($_GET["id"]), $global_site_domain, $global_site_title);
                }
                break;
            case 3:
                if (isset($_GET["id"])) {
                    renderForm(htmlspecialchars($_GET["id"]));
                }
                break;
        }
    } else {
        renderStart($global_site_title);
    }
}

require_once __DIR__ . '/Assets/module/footer.php';