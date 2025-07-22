<?php
require_once __DIR__ . '/../Assets/module/head.php'
?>

<div class="mdui-card" id="main-head">
    <div class="mdui-card-primary">
        <div class="mdui-card-primary-title">
            <h4 class="mdui-text-center"><?php echo htmlspecialchars($global_site_title); ?>公告</h3>
        </div>
    </div>
</div>

<div class="mdui-container">
    <div class="mdui-card-content">
        <div id="main-content" class="mdui-typo">
            <?php
            $Parsedown = new Parsedown();

            echo $Parsedown->text($global_site_notice);
            ?>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../Assets/module/footer.php'
?>