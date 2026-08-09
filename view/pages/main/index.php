<?php
require_once __DIR__ . '/home/home_page_data.php';
?>

<!-- Hero Section -->
<div class="hero-section">
    <h1 class="hero-title">🍔 Chào mừng đến FastFood!</h1>
    <p class="hero-subtitle">Thực đơn phong phú - Giao hàng tận nơi - Chất lượng hàng đầu</p>
    <div class="hero-stats">
        <div class="hero-stat">
            <div class="hero-stat-number">50+</div>
            <div class="hero-stat-label">Món ăn</div>
        </div>
        <div class="hero-stat">
            <div class="hero-stat-number">10K+</div>
            <div class="hero-stat-label">Khách hàng</div>
        </div>
        <div class="hero-stat">
            <div class="hero-stat-number">30min</div>
            <div class="hero-stat-label">Giao hàng</div>
        </div>
    </div>
</div>

<!-- Search & Filter -->
<form class="search-filter-bar" method="GET" action="">
    <input type="hidden" name="quanly" value="trangchu">
    <div class="search-box">
        <input type="text" name="search" placeholder="Tìm món ăn..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : ''; ?>">
    </div>
    <select name="danhmuc" class="filter-select" id="homeCategorySelect" data-scroll-mode="<?php echo !$isFilteredView ? 'section' : 'filter'; ?>">
        <option value="">Tất cả danh mục</option>
        <?php while($dm = mysqli_fetch_array($query_dm)) { ?>
            <option value="<?php echo (int) $dm['id_danhmuc']; ?>" <?php echo (isset($_GET['danhmuc']) && (int) $_GET['danhmuc'] === (int) $dm['id_danhmuc']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($dm['tendanhmuc'], ENT_QUOTES, 'UTF-8'); ?>
            </option>
        <?php } ?>
    </select>
    <button type="submit" class="btn-search">🔍 Tìm kiếm</button>
</form>

<!-- Promo Banner -->
<div class="promo-banner">
    <div class="promo-content">
        <h3>🎉 Khuyến mãi đặc biệt!</h3>
        <p>Giảm 15% cho đơn hàng đầu tiên khi đặt hàng qua website</p>
    </div>
    <div class="promo-code">
        <div class="promo-code-label">Nhập mã</div>
        <div class="promo-code-value">FAST15</div>
    </div>
</div>

<?php if (!$isFilteredView) { ?>
    <?php foreach ($categorySections as $section) { ?>
        <section class="menu-section" id="danhmuc-<?php echo (int) $section['category']['id_danhmuc']; ?>">
            <div class="menu-section-header">
                <h2 class="menu-section-title">
                    <i class="<?php echo menu_category_icon((string) $section['category']['tendanhmuc']); ?>"></i>
                    <?php echo htmlspecialchars($section['category']['tendanhmuc'], ENT_QUOTES, 'UTF-8'); ?>
                </h2>
                <span class="menu-section-count"><?php echo count($section['products']); ?> món đang hiển thị</span>
            </div>
            <div class="product-grid">
                <?php foreach ($section['products'] as $index => $row) {
                    render_menu_product_card($row, $index);
                } ?>
            </div>
        </section>
    <?php } ?>

    <?php if ($categorySections === []) { ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-utensils"></i></div>
            <h3>Chưa có món ăn để hiển thị</h3>
            <p>Hãy thêm món ăn và gán danh mục trong trang quản trị.</p>
        </div>
    <?php } ?>
<?php } else { ?>
<!-- Section Title -->
<h2 class="section-title">Kết quả thực đơn</h2>

<!-- Products Grid -->
<div class="product-grid">
    <?php
    $counter = 0;
    while ($row = mysqli_fetch_array($query_pro)) {
        $counter++;
        $badge = ($counter <= 2) ? 'hot' : (($counter <= 4) ? 'new' : '');
        $badge_text = ($counter <= 2) ? 'Nổi bật' : (($counter <= 4) ? 'Mới' : '');
    ?>
        <div class="product-card">
            <?php if($badge) { ?>
                <span class="product-badge <?php echo $badge; ?>"><?php echo $badge_text; ?></span>
            <?php } ?>
            <img src="<?php echo htmlspecialchars(upload_url($row['hinhanh']), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($row['tensanpham'], ENT_QUOTES, 'UTF-8'); ?>" class="product-image">
            <div class="product-info">
                <div class="product-category"><?php echo htmlspecialchars($row['tendanhmuc'], ENT_QUOTES, 'UTF-8'); ?></div>
                <h3 class="product-name"><?php echo htmlspecialchars($row['tensanpham'], ENT_QUOTES, 'UTF-8'); ?></h3>
                <p class="product-desc"><?php echo htmlspecialchars(mb_substr($row['tomtat'], 0, 60, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>...</p>
                <div class="product-footer">
                    <div class="product-price">
                        <?php echo number_format($row['giasp'], 0, ',', '.'); ?>đ
                    </div>
                    <form method="POST" action="" class="product-order-form">
                        <input type="hidden" name="id_sanpham" value="<?php echo (int) $row['id_sanpham']; ?>">
                        <input type="hidden" name="ten_sanpham" value="<?php echo htmlspecialchars($row['tensanpham'], ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="giasp" value="<?php echo (int) $row['giasp']; ?>">
                        <input type="hidden" name="hinhanh" value="<?php echo htmlspecialchars($row['hinhanh'], ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="number" name="soluong" value="1" min="1" max="10" class="product-quantity" aria-label="Số lượng">
                        <a class="btn-detail" href="index.php?quanly=sanpham&id=<?php echo (int) $row['id_sanpham']; ?>" title="Xem chi tiết" aria-label="Xem chi tiết">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button type="submit" name="them_giohang" class="btn-add-cart" title="Thêm vào giỏ hàng" aria-label="Thêm vào giỏ hàng">
                            <i class="fas fa-cart-plus"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?php if($counter == 0) { ?>
    <div class="empty-state">
        <div class="empty-state-icon">😕</div>
        <h3>Không tìm thấy sản phẩm</h3>
        <p>Vui lòng thử tìm kiếm với từ khóa khác hoặc chọn danh mục khác</p>
    </div>
<?php } ?>

<?php } ?>

<?php if ($isFilteredView) { ?>
<!-- Pagination -->
<div class="pagination-modern">
    <?php if ($page > 1) { ?>
        <a href="?quanly=trangchu&trang=<?php echo $page - 1; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?><?php echo isset($_GET['danhmuc']) ? '&danhmuc=' . (int) $_GET['danhmuc'] : ''; ?>">← Trước</a>
    <?php } else { ?>
        <span class="disabled">← Trước</span>
    <?php } ?>

    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
        <?php if ($i == $page) { ?>
            <span class="current"><?php echo $i; ?></span>
        <?php } else { ?>
            <a href="?quanly=trangchu&trang=<?php echo $i; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?><?php echo isset($_GET['danhmuc']) ? '&danhmuc=' . (int) $_GET['danhmuc'] : ''; ?>"><?php echo $i; ?></a>
        <?php } ?>
    <?php } ?>

    <?php if ($page < $total_pages) { ?>
        <a href="?quanly=trangchu&trang=<?php echo $page + 1; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?><?php echo isset($_GET['danhmuc']) ? '&danhmuc=' . (int) $_GET['danhmuc'] : ''; ?>">Sau →</a>
    <?php } else { ?>
        <span class="disabled">Sau →</span>
    <?php } ?>
</div>
<?php } ?>



<!-- Auto reset timer for kiosk mode -->
<script src="js/timeout.js"></script>

<!-- Include footer with chatbot -->
