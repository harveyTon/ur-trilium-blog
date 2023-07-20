<?php
include __DIR__ . '/header.php';
?>

<h1 id="title" class="title"><?php echo $config['siteTitle']; ?></h1>
<div id="content" class="timeline">
    <?php foreach ($searchResults['results'] as $note) : ?>
        <div class="timeline-box">
            <span class="timeline-date">
                <span><?= (new DateTime($note['utcDateCreated'], new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('Asia/Shanghai'))->format('Y-m-d H:i') ?></span>
            </span>
            <h3>
                <a href="/note/<?= htmlspecialchars($note['noteId']) ?>.html"><?= htmlspecialchars($note['title']) ?></a>
            </h3>
        </div>
    <?php endforeach; ?>
</div>

<?php if ($loadFlag) : ?>
    <div class="load-more-container">
        <button class="load-more" id="load-more" data-next="0" onclick="loadMore()" style="display:block;">Load More</button>
    </div>
    <script>
        function convertUTCToLocalTime(utcDateString, targetTimezoneOffset) {
            const date = new Date(utcDateString);

            const targetTimezoneOffsetMillisecs = targetTimezoneOffset * 60 * 60 * 1000;
            const targetDateMillisecs = date.getTime() + targetTimezoneOffsetMillisecs;

            const targetDate = new Date(targetDateMillisecs);

            return `${targetDate.getFullYear()}-${(targetDate.getMonth() + 1).toString().padStart(2, '0')}-${targetDate.getDate().toString().padStart(2, '0')} ${targetDate.getHours().toString().padStart(2, '0')}:${targetDate.getMinutes().toString().padStart(2, '0')}`;
        }

        function loadMore() {
            const contentDiv = document.getElementById("content");
            const loadBtn = document.getElementById("load-more");
            let dataNextValue = parseInt(loadBtn.getAttribute("data-next"));
            const pageResult = <?php echo json_encode($pageResult['results']); ?>; // assume this data comes from server

            if (pageResult[dataNextValue]) {
                const newContents = pageResult[dataNextValue].map(result =>
                    `<div class="timeline-box">
                <span class="timeline-date">
                    <span>${convertUTCToLocalTime(result['utcDateCreated'], 8)}</span>
                </span>
                <h3>
                    <a href="/note/${result['noteId']}.html">${result['title']}</a>
                </h3>
            </div>`
                );
                contentDiv.innerHTML += newContents.join("");
                dataNextValue++;
                if (dataNextValue < pageResult.length) {
                    loadBtn.setAttribute("data-next", dataNextValue.toString());
                } else {
                    loadBtn.setAttribute("data-next", "-1");
                    loadBtn.disabled = true;
                    loadBtn.textContent = "No More!";
                    loadBtn.style.backgroundColor = "gray";
                    loadBtn.style.color = "white";
                    // 添加动画效果
                    loadBtn.style.transition = 'opacity 3s ease';

                    // 设置透明度为0
                    loadBtn.style.opacity = '0';
                    setTimeout(function() {
                        loadBtn.style.display = 'none';
                    }, 3000);
                }
            }
        }
    </script>
<?php endif; ?>


<?php
include __DIR__ . '/footer.php';
?>