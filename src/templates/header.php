<!doctype html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <meta name="description" content="Tiger's Notes. ">
    <meta name="keywords" content="<?php echo implode(", ", $config['keyWords']); ?>">
    <meta name="author" content="Tiger">
    <?php
        $title = $config['siteTitle'];

        if ($pageName == 'about') {
            $title = '关于本站 - ' . $title;
        } elseif ($pageName == 'links') {
            $title = '友情链接 - ' . $title;
        } elseif ($note) {
            $title = $note['title'] . ' - ' . $title;
        } else {
            $title .= ' - ' . $config['secondTitle'];
        }
    ?>
    <title><?= $title ?></title>
    <link rel='stylesheet' href='https://ik.imagekit.io/tigerton/LXGWWenKai-Bold/result.css' />
    <link rel="stylesheet" href="/wp-content/themes/urtrilium/style.css?ver=0.1.1-dev">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <?php if ($note || $pageName) : ?>
        <link rel="stylesheet" href="/wp-content/themes/urtrilium/libs/prism.css">
        <link rel="stylesheet" href="/wp-content/themes/urtrilium/libs/Artalk.css">
    <?php endif; ?>
    <script defer data-domain="blog.uto.to" src="https://s.uto.to/js/script.js"></script>
</head>
<body<?= $note ? ' class="line-numbers"' : '' ?>>
    <header>
        <nav>
            <ul>
                <li><a href="/">首页</a></li>
                <li>
                    <a href="javascript:void(0)" class="collapsed">工具</a>
                    <ul style="display: none" class="subnav">
                        <li><a href="https://tutu.to" target="_blank">图床</a></li>
                        <li><a href="https://3dimg.com" target="_blank">3DIMG</a></li>
                        <li><a href="https://uto.to" target="_blank">短网址</a></li>
                    </ul>
                </li>
                <li><a href="/links.html">友链</a></li>
                <li><a href="/about.html">关于</a></li>
            </ul>
        </nav>
    </header>
    <div id="layout">
        <div id="main">