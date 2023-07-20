<?php
include __DIR__ . '/header.php';
?>
<h1 id="title" class="title"><?php echo htmlspecialchars($note['title']); ?></h1>

<div class="ck-content" id="content">
    <div class="author">
        <figure class="table">
            <table style="border-style:none;">
                <tbody>
                    <tr>
                        <td style="border-style:dashed;text-align:center;"><span style="display:none" id="ArtalkPV"></span>👤 <a href="https://blog.uto.to/">Tiger</a> &nbsp; 📅 <span class="tooltip"><?= (new DateTime($note['utcDateCreated'], new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('Asia/Shanghai'))->format('Y-m-d H:i') ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </figure>
        <?php if (isset($note['pageUrl'])) : ?>
            <div id="noteClippedFrom">这个笔记剪藏自 <a href="<?= $note['pageUrl'] ?>" target="_blank" rel="nofollow"><?= $note['pageUrl'] ?></a></div>
        <?php endif; ?>
    </div>
    <div class="note-content">
        <?php
        echo $note['content'];
        ?>
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

<script src="/wp-content/themes/urtrilium/libs/prism.js"></script>
<script src="https://comments.uto.to/dist/Artalk.js"></script>
<script>
    new Artalk({
        el: '#comments',
        pageKey: 'note/<?= $note['noteId'] . '.html' ?>',
        pageTitle: '<?= $note['title'] ?>',
        server: 'https://comments.uto.to',
        site: "虎笺",
        imgUpload: false,
        useBackendConf: false,
        emoticons: ["https://cdn.jsdelivr.net/gh/ArtalkJS/Emoticons/grps/default.json"],
        pvEl: '#ArtalkPV',
    })
    // 创建灯箱元素
    var lightbox = document.createElement('div');
    lightbox.id = 'lightbox';
    document.body.appendChild(lightbox);

    // 创建关闭按钮
    var closeButton = document.createElement('a');
    closeButton.className = 'close';
    closeButton.innerHTML = '&times;';
    closeButton.onclick = function() {
        lightbox.style.display = 'none';
    }
    lightbox.appendChild(closeButton);

    // 创建用于在灯箱中显示的图像元素
    var lightboxImage = document.createElement('img');
    lightbox.appendChild(lightboxImage);

    // 为每个链接添加一个点击事件处理器
    var imageLinks = document.getElementsByClassName('image-link');
    for (var i = 0; i < imageLinks.length; i++) {
        imageLinks[i].onclick = function(event) {
            event.preventDefault(); // 阻止链接的默认行为
            lightboxImage.src = this.href; // 设置灯箱图像的src为链接的href
            lightbox.style.display = 'block'; // 显示灯箱
        }
    }
</script>
<?php
include __DIR__ . '/footer.php';
?>