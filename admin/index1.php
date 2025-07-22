<?php
include __DIR__ . "/../password.php";

if (!empty($_GET["password"]) && $_GET["password"] == $password) {
    require_once __DIR__ . '/index1_function.php';
?>
    <!DOCTYPE html>
    <html lang="zh-CN">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- 引入MDUI的CSS -->
        <link rel="stylesheet" href="/Assets/css/mdui.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>等待审核の后台</title>
        <link rel="stylesheet" href="/Assets/css/mdui.min.css">
        <link href="https://cdn.bootcdn.net/ajax/libs/nprogress/0.2.0/nprogress.min.css" rel="stylesheet">
        <script src="https://cdn.bootcdn.net/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
        <script src="https://cdn.bootcdn.net/ajax/libs/wangeditor5/5.1.23/index.min.js"></script>
    </head>

    <body class="mdui-theme-primary-indigo mdui-appbar-with-toolbar">
        <?php include __DIR__ . "/nav.php"; ?>
        <div class="mdui-container mdui-typo">
            <h1 class="mdui-typo-display-1">
                等待审核の后台
            </h1>
            <div class="mdui-divider"></div>
            <div id="item-list">
                <?php
                // 显示所有待审核数据
                $data = json_decode(file_get_contents(__DIR__ . "/../Assets/data/json/pending.json"), true);

                // 分页设置
                $totalItems = count($data);
                $itemsPerPage = 10;  // 每页显示的项目数量
                $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $totalPages = ceil($totalItems / $itemsPerPage);

                // 计算当前页显示的项目
                $startIndex = ($currentPage - 1) * $itemsPerPage;
                $currentPageData = array_slice($data, $startIndex, $itemsPerPage);

                if (!empty($currentPageData)) {
                    // 如果有待审核数据，则显示这些数据
                    echo <<<HTML
                    <div class="mdui-table-fluid">
                        <table class="mdui-table mdui-table-hoverable">
                            <thead>
                                <tr><th>ID</th><th>域名</th><th>介绍</th><th>站长</th><th>邮箱</th><th>加入时间</th><th>操作</th></tr>
                            </thead>
                        <tbody>
                    HTML;
                    $counter = $startIndex + 1;
                    foreach ($currentPageData as $item) {
                        echo renderPendingRow($item, $counter);
                        $counter++;
                    }

                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';

                    // 分页导航
                    renderPagination($currentPage, $totalPages, 'index1.php', ['password' => $password]);
                } else {
                    // 如果没有待审核数据，则显示提示信息
                    echo '<div class="mdui-m-y-4 mdui-center">';
                    echo '<h3 class="mdui-typo-display-2 mdui-text-color-grey-500">暂无待审核项目</h3>';
                    echo '</div>';
                }
                ?>
            </div>

            <script>
                function handleAction(id, action) {
                    $.ajax({
                        url: 'index1_action.php?password=<?php echo htmlspecialchars($_GET["password"]); ?>',
                        type: 'POST',
                        data: {
                            id: id,
                            action: action,
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                // 添加渐隐动画并在完成后删除元素
                                $('#item-' + id).fadeOut(500, function() {
                                    $(this).remove();
                                });
                                mdui.snackbar({
                                    message: `${action} ${id} 成功`,
                                    position: "left-bottom",
                                });
                            } else {
                                alert('操作失败: ' + response);
                            }
                        },
                        error: function() {
                            alert('请求失败，请稍后再试。');
                        }
                    });
                }
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
