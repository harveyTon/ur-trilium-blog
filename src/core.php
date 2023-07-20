<?php

// 引入外部函数文件
require_once ROOT_DIR . '/wp-includes/functions.php';

// 声明全局变量
global $note, $pageName;

// 检查是否有 ignoreCache 参数
$ignoreCache = isset($_GET['ignoreCache']);

// 验证 noteId 的工具函数
function isValidNoteId($noteId) {
    return preg_match("/^[A-Za-z\d]{12}$/", $noteId);
}

// 处理 noteImg 参数的逻辑
if (isset($_GET['noteImg'])) {
    preg_match('/images\/(.*?)\//', urldecode($_GET['noteImg']), $matches);
    $noteId = $matches[1];
    // 为 noteId 设置一个匹配模式
    // 如果 noteId 不匹配该模式，返回错误信息
    if(isValidNoteId($noteId)){
        try {
            getNoteById($noteId, 'image');
        } catch (Exception $e) {
            handleError($e->getMessage());
        }
    }
    // 如果不是，则输出错误信息并退出
    handleError('JUST FUCK IT!');
}
// 处理 noteId 参数的逻辑
elseif (isset($_GET['noteId']) && isValidNoteId($_GET['noteId'])) {
    $templateName = "note";
    $note = getNoteById($_GET['noteId'], 'note');
}
// 处理 pageName 参数的逻辑
elseif (isset($_GET['pageName'])) {
    $allowedPages = ['about', 'links'];

    if (in_array($_GET['pageName'], $allowedPages)) {
        $pageName = $_GET['pageName'];
        $templateName = $pageName;
    } else {
        handleError("JUST FUCK IT!");
    }
}
// 默认逻辑: 加载首页
else {
    $templateName = "home";
    $keyword = '#blog = true';
    global $redis;
    // 尝试从 Redis 中获取搜索结果
    if (!$ignoreCache && $redis->exists('searchResults')) {
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
