<?php

// 加载配置文件，并获取相关的配置信息
use JetBrains\PhpStorm\NoReturn;

$config = require_once ROOT_DIR . '/config/config.php';
$apiToken = $config['apiToken'];
$apiUrl = $config['apiUrl'];
$redis = $config['redisClient'];

// 根据SVG内容输出图片，并保存到Redis
function outputSvgFromString($svgContent, $imageKey): bool
{
    global $redis;

    // 检查内容是否为有效的SVG
    if (!str_contains($svgContent, '<svg')) {
        return false;
    }

    // 设置响应的内容类型为SVG
    header('Content-Type: image/svg+xml');

    // 输出SVG内容
    echo $svgContent;

    // 将SVG内容保存到Redis，设置有效期为30天
    $redis->set($imageKey, $svgContent, 3600 * 24 * 30);

    return true;
}

// 根据图片内容输出图片，并保存到Redis
// function outputImageFromString($imageContent, $imageKey): bool
// {
//     global $redis;
//     $image = @imagecreatefromstring($imageContent);

//     if (!$image) {
//         return false;
//     }

//     // 设置响应的内容类型为JPEG
//     header('Content-Type: image/jpeg');

//     // 输出图片
//     if(imagejpeg($image)){
//         // 如果成功输出，将图片内容保存到Redis，有效期为30天
//         $redis->set($imageKey, $imageContent, 3600 * 24 * 30);
//     }

//     // 释放内存
//     imagedestroy($image);

//     return true;
// }

// 增加对GIF的支持，切换到Imagick处理图片
function outputImageFromString($imageContent, $imageKey): bool
{
    global $redis;

    try {
        // 创建一个新的 Imagick 对象
        $imagick = new Imagick();
        $imagick->readImageBlob($imageContent);

        // 根据图像格式设置响应的内容类型
        $format = $imagick->getImageFormat();
        if ($format == 'GIF') {
            header('Content-Type: image/gif');
            echo $imagick->getImagesBlob(); // 输出完整的 GIF 动画
        } else {
            header('Content-Type: image/jpeg');
            echo $imagick->getImageBlob();
        }

        // 将图片内容保存到 Redis，有效期为 30 天
        $redis->set($imageKey, $imageContent, 3600 * 24 * 30);

        // 清除 Imagick 对象
        $imagick->clear();
        $imagick->destroy();

        return true;
    } catch (Exception $e) {
        return false;
    }
}

// 调用 API 并获取结果
/**
 * @throws Exception
 */function callApi($url)
 {
    global $apiToken;
    // 设置 HTTP 请求头
    $options = [
        'http' => [
            'header' => "Authorization: " . $apiToken
        ]
    ];
    // 创建一个 HTTP 流的上下文
    $context = stream_context_create($options);
    // 使用上下文发送 HTTP 请求，并获取结果
    $result = file_get_contents($url, false, $context);
    // 如果获取结果失败，抛出异常
    if ($result === false) {
        throw new Exception("Failed to call API: $url");
    }
    // 如果 URL 是以 'content' 结束的，则返回原始结果
    if (str_ends_with($url, 'content')) {
        return $result;
    }
    // 否则，将结果转换为数组并返回
    return json_decode($result, true);
}

// 根据 API 端点构建 URL
function buildUrl($endpoint): string
{
    global $apiUrl;
    return $apiUrl . $endpoint;
}

// 搜索笔记
/**
 * @throws Exception
 */function searchNotes($keyword)
{
    // 构建 URL 并调用 API
    $url = buildUrl('/notes?search=' . urlencode($keyword) . '&orderBy=utcDateCreated&limit=100');
    return callApi($url);
}

// 替换内容中的某些部分，如图片URL和语言标识
function replaceContent($content, $apiHost): array|string|null
{
    // 匹配并替换图片 URL
    $content = preg_replace_callback(
        '/<img src="api\/images\/([A-Za-z\d]{12})\/[^>]*?(?:\/>|>)/',
        function($matches) use ($apiHost) {
            return '<img src="/?noteImg='.$apiHost.'api/images/' . $matches[1] . '/content" alt="' . $matches[1] . '">';
        },
        $content
    );


    // 匹配并替换语言标识
    $content = preg_replace('/language-([a-z-]+)-env-backend/', 'language-javascript', $content);
    $content = preg_replace('/language-text-x-sh/', 'language-bash', $content);
    $content = preg_replace('/language-text-x-java/', 'language-java', $content);
    $content = preg_replace('/language-text-x-php/', 'language-php', $content);
    $content = preg_replace('/language-text-x-dockerfile/', 'language-dockerfile', $content);
    
    // img 添加 a
    return preg_replace_callback('/(<img[^>]+src="([^"]*)"[^>]*>)/i', function($matches) {
        // $matches[0] is the entire match
        // $matches[1] is the img tag
        // $matches[2] is the src attribute
        return '<a class="image-link" href="' . $matches[2] . '">' . $matches[1] . '</a>';
    }, $content);
}


// 根据笔记 ID 获取笔记
/**
 * @throws Exception
 */function getNoteById($noteId, $noteType, $ignoreCache = false)
 {
    global $apiUrl, $redis;
    // 创建笔记的 Redis key
    $noteKey = "$noteType:$noteId";
    // 尝试从 Redis 获取笔记
    $note = $redis->get($noteKey);
    
    if(!$ignoreCache){
        if ( $note && $noteType == 'image') {
            if(preg_match('/<svg[^>]*+>/i', $note)){
                outputSvgFromString($note, $noteKey);
                exit();
            }
            outputImageFromString($note, $noteKey);
            exit();
        }elseif($note && $noteType == 'note'){
            return json_decode($note, true);
        }
    }
    

    // 解析出 API 的主机地址
    $apiHost = parse_url($apiUrl, PHP_URL_SCHEME) . '://' . parse_url($apiUrl, PHP_URL_HOST) . '/';

    // 构建 URL 并调用 API 获取笔记
    $url = buildUrl('/notes/' . $noteId);
    $note = callApi($url);
    // 如果获取笔记失败，则返回 null
    if (!$note) {
        return null;
    }

    // 构建 URL 并调用 API 获取笔记内容
    $url = buildUrl('/notes/' . $noteId . '/content');
    $content = callApi($url);
    // 如果获取笔记内容失败，则返回 null
    if (!$content) {
        return null;
    }
    if($note['type'] == 'canvas'){
        if($noteType == 'image'){
            outputSvgFromString(json_decode($content)->svg, $noteKey);
            exit();
        }
        $content = '<a class="image-link" href="/?noteImg=' . $apiHost . 'api/images/' . $note['noteId'] . '/'. rawurlencode($note['title']) .'"><img src="/?noteImg=' . $apiHost . 'api/images/' . $note['noteId'] . '/'. rawurlencode($note['title']) .'" alt="' . $note['title'] . '" type="image/svg+xml" width="100%" height="auto" /></a>';
        
    }elseif($noteType == 'image'){
        outputImageFromString($content, $noteKey);
        exit();
    }else{
        $content = replaceContent($content, $apiHost);
    }

    // 获取属性数组
    $attributes = $note['attributes'];

    // 遍历属性数组查找"pageUrl"属性
    foreach ($attributes as $attribute) {
        if ($attribute['name'] === 'pageUrl') {
            // 找到了"pageUrl"属性，返回其值
            $pageUrl = $attribute['value'];
            break;
        }
    }

    if(isset($pageUrl)){
        $note['pageUrl'] = $pageUrl;
    }
    // 将笔记内容添加到笔记数组中
    $note['content'] = $content;

    // 将笔记保存到 Redis 中，并设置过期时间
    $redis->set($noteKey, json_encode($note), 3600 * 24 * 30);

    // 返回笔记
    return $note;
}

// 错误处理函数
#[NoReturn] function handleError($errorMessage): void
{
    // 输出错误信息
    echo $errorMessage;
    // 设置3秒后自动跳转到首页
    echo '<script>setTimeout(function(){location.href="/"},3000);</script>';
    exit;
}