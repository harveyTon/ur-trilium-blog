<?php
include __DIR__ . '/header.php';
?>

<h1 id="title" class="title">友情链接</h1>

<div class="ck-content" id="content">
    <div class="author">
        <span style="display:none" id="ArtalkPV"></span>
    </div>
    <div class="note-content">

        <!-- 友链说明区域-->
        <div class="category link-about" id="flinks"><img src="https://tutu.to/ttt_Jnpmve.png" border="0" align="absmiddle" width="24" alt="flink"> &nbsp;友链说明</div>
        <div class="links">
            <div class="link link-text link-about-text">
                <p>本站对友情连接申请有几个小要求：</p>
                <p>1、本站所加的友情链接，如发现其失效持续超过一周，在贵站没有任何补充说明的情况下，将直接取消贵链接，恕不另行通知；</p>
                <p>2、申请之前请先提前对本站做好友情链接，再发送申请，格式在最下面；</p>
                <p>3、要求贵站页面设计整洁，友情链接清晰不乱，不接受广告联盟、营销网站之类站点的链接，建站时间大于1年，无恶意代码；</p>
                <p>4、网站域名需开启SSL证书；</p>
                <p>5、不想继续做友情链接的，请与我说明，相互取消链接；</p>
            </div>

            <div class="link link-text link-about-text">
                <p>本站友情连接格式：</p>
                <pre class="language-text-html" tabindex="0"><code class="language-text-html">网站名称：Tiger's Notes
网站域名：https://blog.uto.to</code></pre>
            </div>
        </div>

        <!-- 特别推荐区域 -->
        <div class="category special"><img src="https://tutu.to/ttt_0HP9Vk.png" border="0" align="absmiddle" width="24" alt="special"> &nbsp; 机械境</div>
        <div class="links">
            <div class="link">

                <a href="https://tutu.to" target="_blank"><img src="" alt="兔兔图床"><span>兔兔图床</span></a>
            </div>
            <div class="link">

                <a href="https://3dimg.com" target="_blank"><img src="" alt="3DIMG"><span>3DIMG</span></a>
            </div>
            <div class="link">

                <a href="https://uto.to/" target="_blank"><img src="" alt="utoto"><span>UTO.TO</span></a>
            </div>
            <div class="link">

                <a href="https://blog.uto.to" target="_blank"><img src="" alt="Blog"><span>Tiger's Notes</span></a>
            </div>
        </div>

        <!-- 网络邻居区域 -->
        <div class="category neighbor"><img src="https://tutu.to/ttt_4nvcBN.png" border="0" align="absmiddle" width="24" alt="neighbor"> &nbsp; 混沌海</div>
        <div class="links">
            <div class="link">

                <a href="https://www.xiucai.io/" target="_blank"><img src="" alt="秀才书屋"><span>秀才书屋</span></a>
            </div>
        </div>

        <!-- 查无此人区域 -->
        <div class="category not-found" style="display: none">查无此人</div>
        <div class="links" style="display: none">
            <div class="link">
                <img src="" alt="链接名称">
                <a href="">链接名称</a>
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
<script src="https://comments.uto.to/dist/Artalk.js"></script>
<script>
    new Artalk({
        el: '#comments',
        pageKey: 'links.html',
        pageTitle: "友情链接 - <?= $config['siteTitle'] ?>",
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