<?php
require_once __DIR__ . '/Assets/module/head.php';
error_reporting(0);
?>
<p class="mdui-typo-display-1 mdui-m-l-2"><?php echo htmlspecialchars($global_site_title); ?>查询</p>

<?php
$info = json_decode($global_data_idinfo, true);
$id = $_GET["id"] ?? null;

if ($id !== null) {
    $idList = json_decode($global_data_id, true);
    $pendingIdList = json_decode($global_data_pendingid, true);

    if (in_array($id, $idList)) {
        $array = searchArray($info, $id);
        echo <<<HTML
        <h4>域名: {$array[0]["domain"]}</h4>
        <h4>介绍: {$array[0]["description"]}</h4>
        <h4>站长: {$array[0]["master"]}</h4>
        <h4>邮箱: {$array[0]["email"]}</h4>
        <h4>加入时间: {$array[0]["join_date"]}</h4>
        <h4>当前状态: {checkDomain($array[0]["domain"])}</h4>
        <big><a class="ol" href="{$array[0]["domain"]}">点击我前往网站喵</a></big>｜
        <big><a class="ol" href="https://who.cx/{$array[0]["domain"]}">Whois查询一下域名喵</a></big><br>
        <big><a class="ol" href="https://mping.chinaz.com/{$array[0]["domain"]}">Ping一下域名喵</a></big>｜
        <big><a class="ol" href="https://micp.chinaz.com/{$array[0]["domain"]}">查一下备案喵</a></big>
        HTML;
    } elseif (in_array($id, $pendingIdList)) {
        echo <<<HTML
        <p class="mdui-typo-headline mdui-m-l-3">{$id}我们正在审核喵!或者我们在忙,请您耐心等待喵!</p>
        HTML;
    } else {
        echo <<<HTML
        <p class="mdui-typo-headline mdui-m-l-3">{$id}这个编号是不是搞错了喵?不存在诶!如果您的编号被删除了请联系QQ3589067134喵!</p>
        HTML;
    }
} else {
    $lastElement = end($info);
?>
    <form method="GET">
        <div class="mdui-textfield">
            <input class="mdui-textfield-input" type="text" name="id" placeholder="输入一下你的编号喵~" style="max-width: 58%;    margin-bottom: 10px;">
            <div>
                <input type="submit" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-teal" value="查询一下喵">
                <a href="join.php">
                    <button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-teal">
                        立即加入喵
                    </button>
                </a>
            </div>
        </div>
    </form>
<?php
    echo "<p>最后一个注册: " . htmlspecialchars($lastElement["id"]) . " - " . htmlspecialchars($lastElement["domain"]) . " - " . htmlspecialchars($lastElement["description"]) . "</p>";
}
?>

<?php
require_once __DIR__ . '/Assets/module/footer.php'
?>