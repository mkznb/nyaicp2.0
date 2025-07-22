<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

/**
 * Summary of searchArray
 * @param mixed $array
 * @param mixed $value
 * @return array
 */
function searchArray($array, $value)
{
    $results = [];
    foreach ($array as $key => $item) {
        if ($item === $value) {
            $results[] = $array;
        } else if (is_array($item)) {
            $subResults = searchArray($item, $value);
            if (!empty($subResults)) {
                $results = array_merge($results, $subResults);
            }
        }
    }
    return $results;
}

/**
 * 检查域名是否存在、是否悬挂备案标识
 * ---------------
 * DKoTechnology，自动审核模块
 * 
 * @param string $domain 域名
 * @return bool 如果域名存在返回 true，否则返回 false
 */
function checkDomain($domain)
{
    // 初始化 cURL
    $ch = curl_init();
    // 设置 cURL 选项
    curl_setopt($ch, CURLOPT_URL, $domain);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    // 执行 cURL 请求并获取响应
    $response = curl_exec($ch);
    // 获取 HTTP 状态码
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // 关闭 cURL 资源
    curl_close($ch);
    // 检查 HTTP 状态码
    if ($httpCode != 200) {
        return false;
    }
    // 使用 DOMDocument 解析 HTML
    $dom = new DOMDocument();
    libxml_use_internal_errors(true); // Suppress warnings
    $dom->loadHTML($response);
    libxml_clear_errors(); // Clear any accumulated errors
    // 使用 DOMXPath 查找所有 a 标签
    $xpath = new DOMXPath($dom);
    $aTags = $xpath->query('//a');
    // 遍历所有 a 标签
    foreach ($aTags as $aTag) {
        $href = $aTag->getAttribute('href');
        if (strpos($href, 'annno.cn') !== false) {
            return true;
        }
        if (strpos($href, 'nyaicp.xyz') !== false) {
            return true;
        }
        if (strpos($href, 'www.omoi.fun') !== false) {
            return true;
        }
    }
    return false;
}

/**
 * 发送邮件（支持单个或多个收件人）
 *
 * @param string|array $addAddress 收件人邮箱地址（字符串或数组）
 * @param string $subject 邮件主题
 * @param string $body 邮件内容
 * @param string $altBody 邮件的纯文本内容（可选）
 * @param array $attachments 附件数组（可选）格式：[['path'=>'文件路径', 'name'=>'显示名称']]
 * @return bool|array 全部成功返回true，部分失败返回失败详情数组
 */
function sendMail($addAddress, $subject, $body, $altBody = "", $attachments = [])
{
    $mail = new PHPMailer(true);
    $results = [];

    try {
        // Server settings
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = '邮箱服务器';
        $mail->SMTPAuth = true;
        $mail->Username = '邮箱';
        $mail->Password = '效验码';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Sender
        $mail->setFrom('邮箱', '名字');
        $mail->addReplyTo('邮箱', 'Info');

        // 处理收件人（支持字符串或数组）
        if (is_string($addAddress)) {
            $mail->addAddress($addAddress);
        } elseif (is_array($addAddress)) {
            foreach ($addAddress as $email => $name) {
                if (is_numeric($email)) {
                    $mail->addBCC($name); // 无名称
                } else {
                    $mail->addBCC($email, $name); // 带名称
                }
            }
        }

        // 处理附件
        if (!empty($attachments)) {
            foreach ($attachments as $attachment) {
                $mail->addAttachment(
                    $attachment['path'],
                    $attachment['name'] ?? ''
                );
            }
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = $altBody ?: strip_tags($body);

        $result = $mail->send();
        return $result;
    } catch (Exception) {
        // 记录失败详情
        $failed = $mail->getToAddresses();
        foreach ($failed as $recipient) {
            $results['failed'][$recipient[0]] = $mail->ErrorInfo;
        }

        // 如果有部分成功发送的，返回失败详情
        if (!empty($results['failed'])) {
            return $results;
        }

        return false;
    }
}

/**
 * 渲染分页导航
 *
 * @param int $currentPage 当前页码
 * @param int $totalPages 总页数
 * @param string $baseUrl 基础URL
 * @param array $queryParams 附加的查询参数 (可选)
 *
 * @return void
 *
 * 此函数生成分页导航，包括首页、上一页、页码范围、下一页、尾页以及跳转功能。
 * 它支持动态生成链接，并根据当前页码和总页数调整显示内容。
 */
function renderPagination($currentPage, $totalPages, $baseUrl, $queryParams = [])
{
    // 构造基础URL
    $queryParamsString = http_build_query($queryParams);
    $baseUrlWithParams = $baseUrl . '?' . $queryParamsString;

    echo '<div class="mdui-m-l-2 mdui-m-t-4" style="display: flex; justify-content: center; align-items: center; flex-wrap: wrap; gap: 8px;">';

    // 首页和上一页
    if ($currentPage > 1) {
        echo '<a href="' . $baseUrlWithParams . '&page=1" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-teal">首页</a>';
        echo '<a href="' . $baseUrlWithParams . '&page=' . ($currentPage - 1) . '" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-teal">上一页</a>';
    }

    // 计算页码范围
    $startPage = max(1, $currentPage - 2);
    $endPage = min($totalPages, $currentPage + 2);

    // 显示前面的省略号
    if ($startPage > 1) {
        echo '<span class="mdui-btn">...</span>';
    }

    // 显示页码
    for ($i = $startPage; $i <= $endPage; $i++) {
        if ($i == $currentPage) {
            echo '<a class="mdui-btn mdui-btn-raised mdui-color-theme-accent">' . $i . '</a>';
        } else {
            echo '<a href="' . $baseUrlWithParams . '&page=' . $i . '" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-teal">' . $i . '</a>';
        }
    }

    // 显示后面的省略号
    if ($endPage < $totalPages) {
        echo '<span class="mdui-btn">...</span>';
    }

    // 下一页和尾页
    if ($currentPage < $totalPages) {
        echo '<a href="' . $baseUrlWithParams . '&page=' . ($currentPage + 1) . '" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-teal">下一页</a>';
        echo '<a href="' . $baseUrlWithParams . '&page=' . $totalPages . '" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-teal">尾页</a>';
    }

    // 页面信息
    echo '<span class="mdui-m-l-2 mdui-m-r-2">第 ' . $currentPage . ' 页 / 共 ' . $totalPages . ' 页</span>';

    // 跳转功能
    echo <<<HTML
    <div style="display: inline-flex; align-items: center; gap: 8px;">
        <input type="number" id="pageInput" min="1" max="$totalPages" value="$currentPage" 
               class="mdui-textfield-input" style="width: 80px; text-align: center;">
        <button onclick="goToPage()" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-teal">跳转</button>
    </div>
    <script>
        function goToPage() {
            const pageInput = document.getElementById('pageInput');
            const page = parseInt(pageInput.value);
            const totalPages = $totalPages;
            
            if (isNaN(page) || page < 1 || page > totalPages) {
                mdui.snackbar({
                    message: '请输入有效的页码 (1-' + totalPages + ')',
                    position: 'left-bottom',
                });
                pageInput.focus();
                return;
            }
            
            window.location.href = '$baseUrlWithParams&page=' + page;
        }
        
        // 监听回车键
        document.getElementById('pageInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                goToPage();
            }
        });
    </script>
    HTML;

    echo '</div>';
}