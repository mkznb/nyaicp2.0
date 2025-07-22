<?php
include __DIR__ . "/../password.php";
if (!empty($_GET["password"]) && $_GET["password"] == $password) {
    require_once __DIR__ . '/index2_function.php'
?>
    <!DOCTYPE html>
    <html lang="zh-CN">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>域名列表の后台</title>
        <link rel="stylesheet" href="/Assets/css/mdui.min.css">
        <link href="https://cdn.bootcdn.net/ajax/libs/nprogress/0.2.0/nprogress.min.css" rel="stylesheet">
        <script src="https://cdn.bootcdn.net/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
        <script src="https://cdn.bootcdn.net/ajax/libs/wangeditor5/5.1.23/index.min.js"></script>
    </head>

    <body class="mdui-theme-primary-indigo mdui-appbar-with-toolbar">
        <?php include __DIR__ . "/nav.php"; ?>
        <div class="mdui-container mdui-typo">
            <style>
                .pagination-container {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 8px;
                    margin: 20px 0;
                }

                .page-info {
                    margin: 0 15px;
                    color: #666;
                }

                .page-jump {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    margin-left: 15px;
                }

                .page-jump input {
                    width: 60px;
                    text-align: center;
                }

                .edit-dialog .mdui-textfield {
                    margin-bottom: 16px;
                }

                .search-filter-container {
                    display: flex;
                    gap: 16px;
                    align-items: center;
                }

                .search-box {
                    flex-grow: 1;
                    min-width: 200px;
                }

                .sort-options {
                    display: flex;
                    gap: 8px;
                    flex-wrap: wrap;
                }

                .sort-btn {
                    margin: 0;
                }

                .active-sort {
                    background-color: rgba(0, 0, 0, 0.1);
                }
            </style>
            <!-- 编辑对话框 -->
            <div class="mdui-dialog" id="editDialog">
                <div class="mdui-dialog-title">编辑记录</div>
                <div class="mdui-dialog-content">
                    <form id="editForm">
                        <input type="hidden" id="editId" name="id">
                        <div class="mdui-textfield">
                            <label class="mdui-textfield-label">域名</label>
                            <input class="mdui-textfield-input" type="text" id="editDomain" name="domain" required />
                        </div>
                        <div class="mdui-textfield">
                            <label class="mdui-textfield-label">介绍</label>
                            <input class="mdui-textfield-input" type="text" id="editDescription" name="description" required />
                        </div>
                        <div class="mdui-textfield">
                            <label class="mdui-textfield-label">站长</label>
                            <input class="mdui-textfield-input" type="text" id="editMaster" name="master" required />
                        </div>
                        <div class="mdui-textfield">
                            <label class="mdui-textfield-label">邮箱</label>
                            <input class="mdui-textfield-input" type="email" id="editEmail" name="email" required />
                        </div>
                        <div class="mdui-textfield">
                            <label class="mdui-textfield-label">加入时间</label>
                            <input class="mdui-textfield-input" type="text" id="editJoinDate" name="join_date" required />
                        </div>
                    </form>
                </div>
                <div class="mdui-dialog-actions">
                    <button class="mdui-btn mdui-ripple" mdui-dialog-cancel>取消</button>
                    <button class="mdui-btn mdui-ripple mdui-color-theme-accent" onclick="saveEdit()">保存</button>
                </div>
            </div>


            <h1 class="mdui-typo-display-1">
                域名列表の后台
            </h1>
            <div class="mdui-divider"></div>

            <!-- 搜索和筛选区域 -->
            <div class="search-filter-container">
                <form method="GET" action="index2.php" class="search-box">
                    <input type="hidden" name="password" value="<?php echo htmlspecialchars($_GET["password"]); ?>">
                    <div class="mdui-textfield mdui-textfield-floating-label">
                        <label class="mdui-textfield-label">全局搜索</label>
                        <input class="mdui-textfield-input" type="text" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button type="submit" class="mdui-btn mdui-btn-icon mdui-ripple" style="position: absolute; right: 0; top: 35px;">
                            <i class="mdui-icon material-icons">search</i>
                        </button>
                    </div>
                </form>

                <div class="sort-options">
                    <span class="mdui-typo-subheading">排序:</span>
                    <button class="mdui-btn mdui-btn-dense sort-btn <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'domain' ? 'active-sort' : ''); ?>"
                        onclick="sortTable('domain', '<?php echo isset($_GET['sort']) && $_GET['sort'] === 'domain' && isset($_GET['direction']) && $_GET['direction'] === 'asc' ? 'desc' : 'asc'; ?>')">
                        域名 <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'domain' ? (isset($_GET['direction']) && $_GET['direction'] === 'asc' ? '↑' : '↓') : ''); ?>
                    </button>
                    <button class="mdui-btn mdui-btn-dense sort-btn <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'master' ? 'active-sort' : ''); ?>"
                        onclick="sortTable('master', '<?php echo isset($_GET['sort']) && $_GET['sort'] === 'master' && isset($_GET['direction']) && $_GET['direction'] === 'asc' ? 'desc' : 'asc'; ?>')">
                        站长 <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'master' ? (isset($_GET['direction']) && $_GET['direction'] === 'asc' ? '↑' : '↓') : ''); ?>
                    </button>
                    <button class="mdui-btn mdui-btn-dense sort-btn <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'join_date' ? 'active-sort' : ''); ?>"
                        onclick="sortTable('join_date', '<?php echo isset($_GET['sort']) && $_GET['sort'] === 'join_date' && isset($_GET['direction']) && $_GET['direction'] === 'asc' ? 'desc' : 'asc'; ?>')">
                        加入时间 <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'join_date' ? (isset($_GET['direction']) && $_GET['direction'] === 'asc' ? '↑' : '↓') : ''); ?>
                    </button>
                    <?php if (isset($_GET['search']) || isset($_GET['sort'])): ?>
                        <a href="index2.php?password=<?php echo htmlspecialchars($_GET["password"]); ?>" class="mdui-btn mdui-btn-dense mdui-color-red">
                            重置筛选
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div id="item-list">
                <?php
                if (!empty($currentPageData)) {
                    echo <<<HTML
                    <div class="mdui-table-fluid">
                        <table class="mdui-table mdui-table-hoverable">
                            <thead>
                                <tr><th>#</th><th>ID</th><th>域名</th><th>介绍</th><th>站长</th><th>邮箱</th><th>加入时间</th><th>操作</th></tr>
                            </thead>
                            <tbody>
                    HTML;
                    $counter = $startIndex + 1;
                    foreach ($currentPageData as $item) {
                        echo renderTableRow($item, $counter);
                        $counter++;
                    }
                    echo '</tbody>';
                    echo '</table>';
                    echo '<div class="pagination-container">';
                    renderPagination($currentPage, $totalPages, 'index2.php', array_merge(['password' => $password], isset($_GET['search']) ? ['search' => $_GET['search']] : [], isset($_GET['sort']) ? ['sort' => $_GET['sort'], 'direction' => $_GET['direction']] : []));
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo '<div class="mdui-m-y-4 mdui-center">';
                    echo '<h3 class="mdui-typo-display-2 mdui-text-color-grey-500">暂无域名记录</h3>';
                    echo '</div>';
                }
                ?>
            </div>
            <script src="https://cdn.bootcdn.net/ajax/libs/mdui/1.0.2/js/mdui.min.js"></script>
            <script>
                let editDialog = new mdui.Dialog('#editDialog');
                let currentEditId = null;

                function confirmDelete(id) {
                    if (confirm(`确认删除ID为：${id}?`)) {
                        deleteItem(id);
                    }
                }

                function deleteItem(id) {
                    $.ajax({
                        url: 'index2.php?password=<?php echo htmlspecialchars($_GET["password"]); ?>',
                        type: 'POST',
                        data: {
                            fail: id,
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                $('#item-' + id).fadeOut(500, function() {
                                    $(this).remove();
                                });
                                mdui.snackbar({
                                    message: `删除 ${id} 成功`,
                                    position: 'left-bottom',
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

                function showEditDialog(id) {
                    currentEditId = id;

                    // 获取记录数据
                    $.ajax({
                        url: 'index2.php?password=<?php echo htmlspecialchars($_GET["password"]); ?>&get_record=true&id=' + id,
                        type: 'GET',
                        success: function(response) {
                            if (response.status === 'success') {
                                const data = response.data;
                                $('#editId').val(data.id);
                                $('#editDomain').val(data.domain);
                                $('#editDescription').val(data.description);
                                $('#editMaster').val(data.master);
                                $('#editEmail').val(data.email);
                                $('#editJoinDate').val(data.join_date || '');

                                editDialog.open();
                            } else {
                                mdui.snackbar({
                                    message: '获取记录失败: ' + response.message,
                                    position: 'left-bottom',
                                });
                            }
                        },
                        error: function() {
                            mdui.snackbar({
                                message: '请求失败，请稍后再试',
                                position: 'left-bottom',
                            });
                        }
                    });
                }

                function saveEdit() {
                    const formData = {
                        edit: true,
                        id: $('#editId').val(),
                        domain: $('#editDomain').val(),
                        description: $('#editDescription').val(),
                        master: $('#editMaster').val(),
                        email: $('#editEmail').val(),
                        join_date: $('#editJoinDate').val()
                    };

                    if (!formData.domain || !formData.description || !formData.master || !formData.email) {
                        mdui.snackbar({
                            message: '请填写所有必填字段',
                            position: 'left-bottom',
                        });
                        return;
                    }

                    $.ajax({
                        url: 'index2.php?password=<?php echo htmlspecialchars($_GET["password"]); ?>',
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            if (response.status === 'success') {
                                editDialog.close();
                                mdui.snackbar({
                                    message: '修改成功',
                                    position: 'left-bottom',
                                });

                                // 刷新当前页
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            } else {
                                mdui.snackbar({
                                    message: '修改失败: ' + (response.message || '未知错误'),
                                    position: 'left-bottom',
                                });
                            }
                        },
                        error: function() {
                            mdui.snackbar({
                                message: '请求失败，请稍后再试',
                                position: 'left-bottom',
                            });
                        }
                    });
                }

                function goToPage() {
                    const pageInput = document.getElementById('pageInput');
                    const page = parseInt(pageInput.value);
                    const totalPages = <?php echo $totalPages; ?>;

                    if (isNaN(page) || page < 1 || page > totalPages) {
                        mdui.snackbar({
                            message: '请输入有效的页码 (1-' + totalPages + ')',
                            position: 'left-bottom',
                        });
                        pageInput.focus();
                        return;
                    }

                    let url = `?page=${page}&password=<?php echo htmlspecialchars($_GET["password"]); ?>`;

                    <?php if (isset($_GET['search'])): ?>
                        url += `&search=<?php echo urlencode($_GET['search']); ?>`;
                    <?php endif; ?>

                    <?php if (isset($_GET['sort'])): ?>
                        url += `&sort=<?php echo $_GET['sort']; ?>&direction=<?php echo $_GET['direction']; ?>`;
                    <?php endif; ?>

                    window.location.href = url;
                }

                // 监听回车键
                document.getElementById('pageInput').addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        goToPage();
                    }
                });

                function sortTable(field, direction) {
                    let url = `?password=<?php echo htmlspecialchars($_GET["password"]); ?>&sort=${field}&direction=${direction}`;

                    <?php if (isset($_GET['search'])): ?>
                        url += `&search=<?php echo urlencode($_GET['search']); ?>`;
                    <?php endif; ?>

                    window.location.href = url;
                }
            </script>
        </div>
        <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.bootcdn.net/ajax/libs/jquery.pjax/2.0.1/jquery.pjax.min.js"></script>
        <?php require_once __DIR__ . '/common.php' ?>
    </body>

    </html>
<?php
} else {
    http_response_code(404);
}
