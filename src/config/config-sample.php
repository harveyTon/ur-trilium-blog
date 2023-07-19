<?php

// config.php

// 配置数据
$config = [
    'apiToken' => 'SlVTVCBGVUNLIElUIQ==',   // API令牌
    'apiUrl' => 'https://trilium-note-server/etapi',  // API的URL
    'pageSize' => 12,    // 每页显示的笔记数量
    'siteTitle' => '虎笺',
    'secondTitle' => 'Tiger\'s Notes',
    'description' => 'This is your site description',   // 网站描述
    'keyWords' => ['notes', 'urtrilium', ' trilium notes'],  // 网站关键词
    'redis' => [   // Redis配置
        'host' => '127.0.0.1',   // 主机地址
        'port' => '6379',    // 端口号
        'password' => '',    // 密码
    ],
];

// 创建Redis客户端实例
$redis = new Redis();
try {
    // 尝试连接到Redis
    $redis->connect($config['redis']['host'], $config['redis']['port']);
    // 如果配置了密码，则进行认证
    if (!empty($config['redis']['password'])) {
        $redis->auth($config['redis']['password']);
    }
} catch (Exception $e) {
    // 处理连接错误
    die('Failed to connect to Redis: ' . $e->getMessage());
}

// 将Redis客户端添加到配置数组中
$config['redisClient'] = $redis;

// 返回配置数组
return $config;

