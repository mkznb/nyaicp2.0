<?php
require_once __DIR__ . '/../../Assets/module/head.php'
?>
<style>
    #main-content {
        font-family: Arial, sans-serif;
        /* 字体 */
    }

    .mdui-textfield {
        width: 100%;
        /* 使文本字段占满容器宽度 */
    }

    .mdui-btn {
        border-radius: 4px;
        /* 圆角边框 */
    }

    .userInfo {
        gap: 20px;
        /* 项目间隔 */
        margin-bottom: 20px;
        /* 底部间隔 */
        display: flex;
    }

    .avatar {
        width: 30px;
        height: 30px;
    }

    .timestamp {
        padding-bottom: 5px;
    }
</style>
<div class="mdui-card" id="main-head">
    <div class="mdui-card-primary">
        <div class="mdui-card-primary-title">
            <h3 class="mdui-text-center"><?php echo htmlspecialchars($global_site_title); ?>留言板</h3>
        </div>
    </div>
</div>

<div class="mdui-container">
    <div class="mdui-card-content">
        <form id="main-content" class="mdui-form">
            <div id="userInfo">
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <label class="mdui-textfield-label">名称</label>
                    <input class="mdui-textfield-input" type="text" name="name" required>
                </div>
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <label class="mdui-textfield-label">头像连接</label>
                    <input class="mdui-textfield-input" type="text" name="avatar">
                </div>
            </div>
            <div class="mdui-textfield mdui-textfield-floating-label">
                <label class="mdui-textfield-label">留言内容</label>
                <textarea class="mdui-textfield-input" name="message" rows="4" required></textarea>
            </div>
            <button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-teal" type="submit">留言</button>
        </form>

        <!-- 留言列表 -->
        <div class="message-list" id="message-list">
            <div class="mdui-progress">
                <div class="mdui-progress-indeterminate"></div>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * 显示消息列表
     * @param {Array|Object} data 数据数组或单个对象
     */
    function displayMessageList(data) {
        // 确保 data 是数组，如果不是数组，转换为包含单个元素的数组
        const messages = Array.isArray(data) ? data : [data];

        const messagesHtml = messages.map(item => `
            <div class="mdui-card mdui-m-t-2 mdui-hoverable box-shadow">
                <div class="mdui-card-primary userInfo">
                    <img src="${item.avatar || '/Assets/image/DefaultAvatar.png'}" class="mdui-img-rounded avatar">
                    <div class="mdui-card-primary-title">${item.name}</div>
                </div>
                <div class="mdui-card-content">
                    <div class="mdui-text-truncate">${item.message}</div>
                    <div class="mdui-float-right timestamp">-------${item.date}</div>
                </div>
            </div>`).join('');

        // 如果 data 是单个对象，则添加到列表顶部，否则替换整个列表
        if (Array.isArray(data)) {
            $('#message-list').html(messagesHtml);
        } else {
            $('#message-list').prepend(messagesHtml);
        }
    }

    $(document).ready(function() {
        // 获取留言列表
        function loadMessages() {
            $.ajax({
                url: '/page/liuyanban/message.php',
                dataType: 'json',
                success: function(data) {
                    displayMessageList(data);
                },
                error: function() {
                    $('#message-list').html('<p>无法加载留言列表。</p>');
                }
            });
        }

        // 加载留言列表
        loadMessages();

        // 提交留言
        $('#main-content').on('submit', function(e) {
            e.preventDefault();

            const formData = $(this).serialize();

            const submitButton = $(this).find('button');
            submitButton.attr('disabled', true);

            $.ajax({
                type: 'POST',
                url: '/page/liuyanban/message.php',
                data: formData,
                success: function(response) {
                    displayMessageList(response.data);
                    $('#main-content')[0].reset();
                    submitButton.attr('disabled', false);
                },
                error: function() {
                    mdui.alert('提交失败，请重试。');
                    submitButton.attr('disabled', false);
                }
            });
        });
    });
</script>

<?php
require_once __DIR__ . '/../../Assets/module/footer.php'
?>