<?php
// 定义搜索并移动值的函数
function searchAndMoveValue($jsonFile1, $jsonFile2, $key, $value)
{
    $arr1 = json_decode(file_get_contents($jsonFile1), true);
    $arr2 = json_decode(file_get_contents($jsonFile2), true);

    foreach ($arr1 as $index => $array) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $movedArray = array_splice($arr1, $index, 1);
            array_push($arr2, $movedArray[0]);
            file_put_contents($jsonFile1, json_encode($arr1));
            file_put_contents($jsonFile2, json_encode($arr2));
            return true;
        }
    }
    return false;
}

function searchAndMoveValue2(string $sourceFile, string $targetFile, $value)
{
    $sourceJson = file_get_contents($sourceFile);
    $sourceArray = json_decode($sourceJson, true);
    $targetJson = file_get_contents($targetFile);
    $targetArray = json_decode($targetJson, true);
    $key = array_search($value, $sourceArray);
    if ($key !== false) {
        unset($sourceArray[$key]);
    }
    $targetArray[] = $value;
    file_put_contents($sourceFile, json_encode($sourceArray));
    file_put_contents($targetFile, json_encode($targetArray));
}