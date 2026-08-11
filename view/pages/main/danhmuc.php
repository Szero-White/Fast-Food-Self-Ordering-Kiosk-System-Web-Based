<?php require_once __DIR__ . '/danhmuc_page_data.php'; ?>

<section class="menu-category-page">
    <header class="menu-category-hero">
        <div class="menu-category-icon">
            <i class="<?php echo menu_category_icon((string) ($category['tendanhmuc'] ?? '')); ?>"></i>
        </div>
        <div class="menu-category-copy">
            <h1><?php echo htmlspecialchars($category['tendanhmuc'] ?? 'Danh mục món ăn', ENT_QUOTES, 'UTF-8'); ?></h1>
            <p><?php echo $totalProducts; ?> món trong danh mục này</p>
        </div>
    </header>

    <?php if (!$category) { ?>
        <div class="menu-category-empty">
            <i class="fas fa-circle-exclamation"></i>
            <h3>Danh mục không tồn tại</h3>
            <p>Vui lòng quay lại trang chủ và chọn danh mục khác.</p>
        </div>
    <?php } elseif ($products === []) { ?>
        <div class="menu-category-empty">
            <i class="fas fa-utensils"></i>
            <h3>Chưa có món ăn trong danh mục này</h3>
            <p>Danh mục này chưa có món nào để hiển thị.</p>
        </div>
    <?php } else { ?>
        <div class="product-grid">
            <?php foreach ($products as $index => $product) {
                render_menu_product_card($product, $index);
            } ?>
        </div>

        <?php if ($totalPages > 1) { ?>
            <?php
            $baseUrl = 'index.php?quanly=danhmucsanpham&id=' . $categoryId;
            ?>
            <nav class="pagination-modern" aria-label="Phân trang">
                <?php if ($currentPage > 1) { ?>
                    <a href="<?php echo $baseUrl; ?>&trang=<?php echo $currentPage - 1; ?>"
                       aria-label="Trang trước">← Trước</a>
                <?php } else { ?>
                    <span class="disabled" aria-disabled="true">← Trước</span>
                <?php } ?>

                <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                    <?php if ($i === $currentPage) { ?>
                        <span class="current" aria-current="page"><?php echo $i; ?></span>
                    <?php } else { ?>
                        <a href="<?php echo $baseUrl; ?>&trang=<?php echo $i; ?>"><?php echo $i; ?></a>
                    <?php } ?>
                <?php } ?>

                <?php if ($currentPage < $totalPages) { ?>
                    <a href="<?php echo $baseUrl; ?>&trang=<?php echo $currentPage + 1; ?>"
                       aria-label="Trang sau">Sau →</a>
                <?php } else { ?>
                    <span class="disabled" aria-disabled="true">Sau →</span>
                <?php } ?>
            </nav>
        <?php } ?>
    <?php } ?>
</section>
