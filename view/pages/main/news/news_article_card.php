<?php
function render_news_article_card(array $article, int $index = 0): void
{
    ?>
    <article class="news-card">
        <?php if ($index < 2) { ?>
            <span class="news-badge"><i class="fas fa-fire"></i> Nổi bật</span>
        <?php } ?>
        <a class="news-image-link" href="index.php?quanly=baiviet&id=<?php echo (int) $article['id_bv']; ?>" aria-label="Xem chi tiết <?php echo htmlspecialchars($article['tenbaiviet'], ENT_QUOTES, 'UTF-8'); ?>">
            <img src="<?php echo htmlspecialchars(upload_url($article['hinhanh']), ENT_QUOTES, 'UTF-8'); ?>"
                 alt="<?php echo htmlspecialchars($article['tenbaiviet'], ENT_QUOTES, 'UTF-8'); ?>"
                 class="news-image"
                 onerror="this.src='<?php echo htmlspecialchars(asset_url('placeholders/news-placeholder.jpg'), ENT_QUOTES, 'UTF-8'); ?>'">
        </a>
        <div class="news-content">
            <div class="news-category"><?php echo htmlspecialchars($article['tendanhmucbv'] ?? 'Tin tức', ENT_QUOTES, 'UTF-8'); ?></div>
            <h3 class="news-title"><?php echo htmlspecialchars($article['tenbaiviet'], ENT_QUOTES, 'UTF-8'); ?></h3>
            <p class="news-summary"><?php echo htmlspecialchars($article['tomtat'], ENT_QUOTES, 'UTF-8'); ?></p>
            <div class="news-footer">
                <span class="news-date"><i class="far fa-calendar-alt"></i> <?php echo date('d/m/Y'); ?></span>
                <a href="index.php?quanly=baiviet&id=<?php echo (int) $article['id_bv']; ?>" class="btn-read-more" title="Xem chi tiết" aria-label="Xem chi tiết">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </article>
    <?php
}

function news_category_icon(string $categoryName): string
{
    $name = mb_strtolower($categoryName, 'UTF-8');

    if (strpos($name, 'khuyến') !== false || strpos($name, 'mãi') !== false) {
        return 'fas fa-gift';
    }

    if (strpos($name, 'tin') !== false) {
        return 'fas fa-newspaper';
    }

    if (strpos($name, 'sự kiện') !== false) {
        return 'fas fa-calendar-check';
    }

    return 'fas fa-folder-open';
}
