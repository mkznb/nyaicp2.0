<?php
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../Assets/helpers.php";
require_once __DIR__ . "/helpers.php";
require_once __DIR__ . "/../init.php";

function renderPendingRow($item, $counter)
{
    $itemId = htmlspecialchars($item["id"]);
    $domain = htmlspecialchars($item["domain"]);
    $description = htmlspecialchars($item["description"]);
    $master = htmlspecialchars($item["master"]);
    $email = htmlspecialchars($item["email"]);
    // 向下兼容
    $join_date = htmlspecialchars($item["join_date"] ?? '', ENT_QUOTES, 'UTF-8');

    return <<<HTML
    <tr id="item-$itemId">
        <td>$itemId</td>
        <td>$domain</td>
        <td>$description</td>
        <td>$master</td>
        <td>$email</td>
        <td>$join_date</td>
        <td>
            <button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-teal" onclick="handleAction('$itemId', 'pass')">通过(速度有亿些慢)</button>
            <button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-red" onclick="handleAction('$itemId', 'fail')">阻止</button>
        </td>
    </tr>
    HTML;
}