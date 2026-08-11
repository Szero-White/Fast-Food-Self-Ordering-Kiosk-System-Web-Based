<?php require_once __DIR__ . '/news/news_page_data.php'; ?>

<div class="news-page">
    <header class="news-hero">
        <div class="news-hero-icon">
            <i class="fas fa-newspaper"></i>
        </div>
        <div class="news-hero-text">
            <h1><?php echo $pageTitle; ?></h1>
            <p><?php echo $pageSubtitle; ?></p>
        </div>
    </header>

    <?php if ($newsSections === []) { ?>
        <div class="news-empty-state">
            <i class="fas fa-inbox"></i>
            <h3>Chưa có bài viết</h3>
            <p>Danh mục này chưa có nội dung. Vui lòng quay lại sau.</p>
        </div>
    <?php } ?>

    <?php foreach ($newsSections as $section) {
        $catId       = (int) $section['category']['id_baiviet'];
        $sectionPage = (int) $section['currentPage'];
        $sectionTotal= (int) $section['totalPages'];
        $isSingleCat = $selectedNewsCategoryId > 0;
    ?>
        <section class="news-category-section" id="danhmuc-baiviet-<?php echo $catId; ?>">
            <div class="news-category-header">
                <h2>
                    <i class="<?php echo news_category_icon((string) $section['category']['tendanhmucbv']); ?>"></i>
                    <?php echo htmlspecialchars($section['category']['tendanhmucbv'], ENT_QUOTES, 'UTF-8'); ?>
                </h2>
                <span><?php echo count($section['articles']); ?> bài viết</span>
            </div>

            <div class="news-grid">
                <?php foreach ($section['articles'] as $index => $article) {
                    render_news_article_card($article, $index);
                } ?>
            </div>

            <?php if ($isSingleCat && $sectionTotal > 1) { ?>
                <?php $baseUrl = 'index.php?quanly=danhmucbaiviet&id=' . $catId; ?>
                <nav class="pagination-modern" aria-label="Phân trang">
                    <?php if ($sectionPage > 1) { ?>
                        <a href="<?php echo $baseUrl; ?>&trang=<?php echo $sectionPage - 1; ?>"
                           aria-label="Trang trước">← Trước</a>
                    <?php } else { ?>
                        <span class="disabled" aria-disabled="true">← Trước</span>
                    <?php } ?>

                    <?php for ($i = 1; $i <= $sectionTotal; $i++) { ?>
                        <?php if ($i === $sectionPage) { ?>
                            <span class="current" aria-current="page"><?php echo $i; ?></span>
                        <?php } else { ?>
                            <a href="<?php echo $baseUrl; ?>&trang=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php } ?>
                    <?php } ?>

                    <?php if ($sectionPage < $sectionTotal) { ?>
                        <a href="<?php echo $baseUrl; ?>&trang=<?php echo $sectionPage + 1; ?>"
                           aria-label="Trang sau">Sau →</a>
                    <?php } else { ?>
                        <span class="disabled" aria-disabled="true">Sau →</span>
                    <?php } ?>
                </nav>
            <?php } elseif (!$isSingleCat && !empty($section['hasMore'])) { ?>
                <div class="news-view-more">
                    <a href="index.php?quanly=danhmucbaiviet&id=<?php echo $catId; ?>"
                       class="btn-view-more">
                        Xem tất cả <?php echo (int) $section['total']; ?> bài viết
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            <?php } ?>
        </section>
    <?php } ?>
</div>
