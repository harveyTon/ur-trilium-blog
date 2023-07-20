<?php
include __DIR__ . '/header.php';
?>

<h1 id="title" class="title">关于本站</h1>

<div class="ck-content" id="content">
    <div class="author">
        <span style="display:none" id="ArtalkPV"></span>
    </div>
    <div class="note-content">

        <!-- 关于本站区域-->
        <div class="category special" id="flinks"><img src="https://tutu.to/ttt_0HP9Vk.png" width="24" alt="flink"> &nbsp;关于本站</div>
        <div class="links">
            <div class="link link-text link-about-text">
                <p>“虎笺”者，吾人个人之记载所也。吾于此处，以技艺之学习与实践经验为众分享。</p>
                <p>本站以 PHP 8.1 之技术打造，倚 Trilium Notes 为后端之支撑，以数据管理，用 Artalk 系统录评论。</p>
                <p>欢迎诸君反馈并建言，以助吾改良网站之内容与服务。吾期待君之参与，与我同在技艺之路上探寻与学习。</p>
            </div>
        </div>
        
    </div>

</div>
<div class="hr-with-text">
    <hr>
    <span>END</span>
    <hr>
</div>
<div>
    <h3>Comments</h3>
    <div id="comments"></div>
</div>
<script src="/wp-content/themes/urtrilium/libs/Artalk.js"></script>
<script>
    new Artalk({
        el: '#comments',
        pageKey: 'about.html',
        pageTitle: "关于本站 - <?= $config['siteTitle'] ?>",
        server: 'https://comments.uto.to',
        site: "虎笺",
        imgUpload: false,
        useBackendConf: false,
        emoticons: ["https://cdn.jsdelivr.net/gh/ArtalkJS/Emoticons/grps/default.json"],
        pvEl: '#ArtalkPV',
    })
</script>


<?php
include __DIR__ . '/footer.php';
?>