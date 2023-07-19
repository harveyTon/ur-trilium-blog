<?php

// 引入 functions.php 文件
require_once ROOT_DIR . '/wp-includes/functions.php';

// 声明全局变量
global $note;
global $pageName;


if (isset($_GET['noteImg'])) {
    preg_match('/images\/(.*?)\//', urldecode($_GET['noteImg']), $matches);
    $noteId = $matches[1];
    // 为 noteId 设置一个匹配模式
    $pattern = "/^[A-Za-z\\d]{12}$/";
    // 如果 noteId 不匹配该模式，返回错误信息
    if (!preg_match($pattern, $noteId)) {
        handleError("JUST FUCK IT!");
    }
    if($noteId){
        try {
            getNoteById($noteId, 'image');
        } catch (Exception $e) {
            handleError($e->getMessage());
        }
    }
    // 如果不是，则输出错误信息并退出
    handleError('JUST FUCK IT!');
}

// 检查是否存在 GET 参数 noteId
if (isset($_GET['noteId'])) {
    $templateName = 'note';
    $noteId = $_GET['noteId'];
    // 为 noteId 设置一个匹配模式
    $pattern = "/^[A-Za-z\\d]{12}$/";
    // 如果 noteId 不匹配该模式，返回错误信息
    if (!preg_match($pattern, $noteId)) {
        handleError("JUST FUCK IT!");
    }
    // 根据 noteId 获取笔记
    $note = getNoteById($noteId, 'note');
} 
// 检查是否存在 GET 参数 pageName
elseif (isset($_GET['pageName'])) {
    $templateName = $_GET['pageName'];
    // 定义允许的页面名称数组
    $allowedPages = ['about', 'links'];

    // 判断请求的页面是否在允许的页面名称数组中
    if (!in_array($templateName, $allowedPages)) {
        handleError("JUST FUCK IT!");
    }
    $pageName = $templateName;
} 
// 如果没有指定页面或笔记，就默认加载首页
else {
    $templateName = "home";
    $keyword = '#blog = true';
    global $redis;
    // 尝试从 Redis 中获取搜索结果
    if ($redis->exists('searchResults')) {
        $searchResults = json_decode($redis->get('searchResults'), true);
    } else {
        // 如果 Redis 中没有搜索结果，则通过 API 搜索笔记
        $searchResults = searchNotes($keyword);

        // 将搜索结果保存到 Redis，并设置12小时过期时间
        $redis->set('searchResults', json_encode($searchResults), 3600 * 12);
    }

    // 判断搜索结果的数量是否超过配置的页面大小
    if (count($searchResults['results']) > $config['pageSize']) {
        $loadFlag = true;
        // 将搜索结果分块
        $chunks = array_chunk($searchResults['results'], $config['pageSize']);
        $searchResults['results'] = $chunks[0];
        $pageResult['results'] = array_slice($chunks, 1); // 获取其余的块

        // 仅保留所需的属性
        $desiredProperties = ["noteId", "title", "type", "utcDateCreated", "utcDateModified"];
        for ($i = 0; $i < count($pageResult['results']); $i++) {
            $pageResult['results'][$i] = array_map(function ($obj) use ($desiredProperties) {
                return array_intersect_key($obj, array_flip($desiredProperties));
            }, $pageResult['results'][$i]);
        }
    }
}

// 根据模板名称加载对应的模板文件
$templatePath = ROOT_DIR . '/templates/template-' . $templateName . '.php';

// 检查模板文件是否存在，如果存在则包含，否则返回错误信息
if (file_exists($templatePath)) {
    include $templatePath;
} else {
    handleError("JUST FUCK IT!");
}
