<?php
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../Assets/helpers.php";
require_once __DIR__ . "/helpers.php";
require_once __DIR__ . "/../init.php";

function renderTableRow($item, $counter)
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
        <td>$counter</td>
        <td>$itemId</td>
        <td>$domain</td>
        <td>$description</td>
        <td>$master</td>
        <td>$email</td>
        <td>$join_date</td>
        <td>
            <button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-blue" style="margin-bottom: 10px;" onclick="showEditDialog('$itemId')">编辑</button>
            <button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-red" onclick="confirmDelete('$itemId')">删除</button>
        </td>
    </tr>
    HTML;
}

if (!empty($_POST["fail"])) {
    header('Content-Type: application/json');
    searchAndMoveValue(__DIR__ . "/../Assets/data/json/idinfo.json", "fail.json", "id", $_POST["fail"]);
    searchAndMoveValue2(__DIR__ . "/../Assets/data/json/id.json", "fail.json", $_POST["fail"]);
    echo json_encode(["status" => "success"]);
    exit;
}

// 处理编辑请求
if (!empty($_POST["edit"])) {
    header('Content-Type: application/json');
    $id = $_POST["id"];

    $filePath = __DIR__ . "/../Assets/data/json/idinfo.json";
    $data = json_decode(file_get_contents($filePath), true);

    $updated = false;
    foreach ($data as &$item) {
        if ($item["id"] == $id) {
            $item["domain"] = $_POST["domain"];
            $item["description"] = $_POST["description"];
            $item["master"] = $_POST["master"];
            $item["email"] = $_POST["email"];
            $item["join_date"] = $_POST["join_date"];
            $updated = true;
            break;
        }
    }

    if ($updated) {
        file_put_contents($filePath, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "未找到对应ID的记录"]);
    }
    exit;
}

// 获取单条记录数据
if (!empty($_GET["get_record"])) {
    header('Content-Type: application/json');
    $id = $_GET["id"];
    $data = json_decode(file_get_contents(__DIR__ . "/../Assets/data/json/idinfo.json"), true);

    foreach ($data as $item) {
        if ($item["id"] == $id) {
            echo json_encode(["status" => "success", "data" => $item]);
            exit;
        }
    }

    echo json_encode(["status" => "error", "message" => "未找到对应ID的记录"]);
    exit;
}

// 获取并处理数据
$data = json_decode(file_get_contents(__DIR__ . "/../Assets/data/json/idinfo.json"), true);

// 搜索功能处理
if (!empty($_GET['search'])) {
    $searchTerm = strtolower($_GET['search']);
    $data = array_filter($data, function ($item) use ($searchTerm) {
        return (strpos(strtolower($item['domain']), $searchTerm) !== false) ||
            (strpos(strtolower($item['description']), $searchTerm) !== false) ||
            (strpos(strtolower($item['master']), $searchTerm) !== false) ||
            (strpos(strtolower($item['email']), $searchTerm) !== false);
    });
}

// 排序功能处理
if (!empty($_GET['sort'])) {
    $sortField = $_GET['sort'];
    $sortDirection = isset($_GET['direction']) && $_GET['direction'] === 'desc' ? SORT_DESC : SORT_ASC;

    // 确保排序字段存在于数据中
    $sortArray = array();
    foreach ($data as $key => $row) {
        // 如果字段不存在或为null，设为空字符串
        $sortArray[$key] = isset($row[$sortField]) ? $row[$sortField] : '';
    }

    // 重新索引数组为数字索引
    $data = array_values($data);
    $sortArray = array_values($sortArray);

    // 执行排序
    array_multisort($sortArray, $sortDirection, $data);
}

// Pagination setup
$totalItems = count($data);
$itemsPerPage = 10;  // 每页显示的项目数量
$currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$totalPages = max(1, ceil($totalItems / $itemsPerPage));
$currentPage = min($currentPage, $totalPages); // 确保不超过最大页数

// 计算当前页显示的项目
$startIndex = ($currentPage - 1) * $itemsPerPage;
$currentPageData = array_slice($data, $startIndex, $itemsPerPage);