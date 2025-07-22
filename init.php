<?php
// 友情我大神Composer
require_once __DIR__ ."/vendor/autoload.php";
/*------------------------------------------------------------------------------*/
// 定义资源文件路径
$global_assets_dir = __DIR__ . '/Assets';
$global_assets_data_dir = "$global_assets_dir/data";

// 安全加载 JSON 数据
function safe_json_load($file_path)
{
    if (file_exists($file_path)) {
        return file_get_contents($file_path);
    } else {
        return [];  // 或者你可以抛出异常
    }
}

// 加载数据文件内容
$global_data_idinfo = safe_json_load("$global_assets_data_dir/json/idinfo.json");
$global_data_id = safe_json_load("$global_assets_data_dir/json/id.json");
$global_data_pendingid = safe_json_load("$global_assets_data_dir/json/pendingid.json");

// 加载文本文件内容
function safe_file_load($file_path)
{
    return file_exists($file_path) ? trim(file_get_contents($file_path)) : '';
}

$global_site_title = safe_file_load("$global_assets_data_dir/title.txt");
$global_site_domain = safe_file_load("$global_assets_data_dir/domain.txt");

// 站点公告
$global_site_notice = safe_file_load("$global_assets_data_dir/notice.md");

/*------------------------------------------------------------------------------*/
// 加载其他必要的文件
require_once "$global_assets_dir/helpers.php";
