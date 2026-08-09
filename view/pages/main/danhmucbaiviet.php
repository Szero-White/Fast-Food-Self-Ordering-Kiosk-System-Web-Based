<?php require_once __DIR__ . '/news/news_page_data.php'; ?>

<div class="news-page">
    <header class="news-hero">
        <div class="news-hero-icon">
            <i class="fas fa-newspaper"></i>
        </div>
        <div class="news-hero-text">
            <h1><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
            <p><?php echo htmlspecialchars($pageSubtitle, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
    </header>

    <?php if ($newsSections === []) { ?>
        <div class="news-empty-state">
            <i class="fas fa-inbox"></i>
            <h3>Chưa có bài viết</h3>
            <p>Danh mục này chưa có nội dung. Vui lòng quay lại sau.</p>
        </div>
    <?php } ?>

    <?php foreach ($newsSections as $section) { ?>
        <section class="news-category-section" id="danhmuc-baiviet-<?php echo (int) $section['category']['id_baiviet']; ?>">
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
        </section>
    <?php } ?>
</div>
