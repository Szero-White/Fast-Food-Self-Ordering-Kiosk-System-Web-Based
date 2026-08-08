<?php
// Lay danh muc cho bo loc
$sql_dm = "SELECT * FROM tbl_danhmuc ORDER BY id_danhmuc DESC";
$query_dm = mysqli_query($mysqli, $sql_dm);

// Lay san pham noi bat
$sql_featured = "SELECT * FROM tbl_sanpham ORDER BY id_sanpham DESC LIMIT 4";
$query_featured = mysqli_query($mysqli, $sql_featured);

// Phan trang
$page = isset($_GET['trang']) ? max(1, (int)$_GET['trang']) : 1;
$begin = ($page - 1) * 8;

// Bo loc danh muc
$filter = "";
if (isset($_GET['danhmuc']) && $_GET['danhmuc'] != "") {
    $id_dm = (int)$_GET['danhmuc'];
    $filter = " AND tbl_sanpham.id_danhmuc = $id_dm";
}

// Tim kiem
$search = "";
if (isset($_GET['search']) && $_GET['search'] != "") {
    $keyword = mysqli_real_escape_string($mysqli, trim($_GET['search']));
    $search = " AND tbl_sanpham.tensanpham LIKE '%$keyword%'";
}

$sql_pro = "SELECT * FROM tbl_sanpham, tbl_danhmuc WHERE tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc $filter $search ORDER BY tbl_sanpham.id_sanpham DESC LIMIT $begin,8";
$query_pro = mysqli_query($mysqli, $sql_pro);

// Dem tong san pham
$count_query = mysqli_query($mysqli, "SELECT COUNT(*) AS total FROM tbl_sanpham WHERE 1=1 $filter $search");
$count_result = mysqli_fetch_assoc($count_query);
$row_count = $count_result['total'];
$total_pages = ceil($row_count / 8);
?>

<style>
    /* Hero Section - Style riêng trang chủ */
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 60px 40px;
        border-radius: 20px;
        margin: 0 auto 40px auto;
        max-width: 84rem;
        color: white;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .hero-section::before {
        content: '🍔';
        position: absolute;
        font-size: 200px;
        opacity: 0.1;
        right: -50px;
        top: -50px;
    }
    .hero-title {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 15px;
    }
    .hero-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 30px;
    }
    .hero-stats {
        display: flex;
        justify-content: center;
        gap: 50px;
        margin-top: 30px;
        position: relative;
        z-index: 1;
    }
    .hero-stat {
        text-align: center;
    }
    .hero-stat-number {
        font-size: 2rem;
        font-weight: bold;
    }
    .hero-stat-label {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    /* Search & Filter Bar */
    .search-filter-bar {
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        margin: 0 auto 30px auto;
        max-width: 84rem;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
    }
    .search-box {
        flex: 1;
        min-width: 250px;
        position: relative;
    }
    .search-box input {
        width: 100%;
        padding: 15px 20px 15px 50px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s;
    }
    .search-box input:focus {
        border-color: #667eea;
        outline: none;
    }
    .search-box::before {
        content: '🔍';
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.2rem;
    }
    .filter-select {
        padding: 15px 20px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 1rem;
        background: white;
        cursor: pointer;
        min-width: 180px;
    }
    .btn-search {
        padding: 15px 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        cursor: pointer;
        transition: transform 0.3s;
    }
    .btn-search:hover {
        transform: translateY(-2px);
    }

    /* Category Pills */
    .category-pills {
        display: flex;
        gap: 10px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }
    .category-pill {
        padding: 10px 20px;
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 25px;
        text-decoration: none;
        color: #666;
        transition: all 0.3s;
        font-size: 0.95rem;
    }
    .category-pill:hover,
    .category-pill.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
    }

    /* Featured Section */
    .section-title {
        font-size: 1.8rem;
        color: #2c3e50;
        margin: 0 auto 25px auto;
        max-width: 84rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-title::after {
        content: '';
        flex: 1;
        height: 2px;
        background: linear-gradient(90deg, #667eea 0%, transparent 100%);
        margin-left: 15px;
    }

    /* Product Cards New */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        margin: 0 auto 40px auto;
        max-width: 84rem;
    }
    .product-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: all 0.3s;
        position: relative;
    }
    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    }
    .product-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #e74c3c;
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: bold;
        z-index: 2;
    }
    .product-badge.new { background: #27ae60; }
    .product-badge.hot { background: #f39c12; }
    .product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: transform 0.3s;
    }
    .product-card:hover .product-image {
        transform: scale(1.05);
    }
    .product-info {
        padding: 20px;
    }
    .product-category {
        color: #667eea;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }
    .product-name {
        font-size: 1.2rem;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 10px;
    }
    .product-desc {
        color: #7f8c8d;
        font-size: 0.9rem;
        margin-bottom: 15px;
        line-height: 1.5;
    }
    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .product-price {
        font-size: 1.3rem;
        font-weight: bold;
        color: #e74c3c;
    }
    .product-price .old {
        font-size: 0.9rem;
        color: #95a5a6;
        text-decoration: line-through;
        margin-right: 8px;
    }
    .btn-view:hover {
        background: #764ba2;
        transform: translateY(-2px);
    }

    /* Promo Banner */
    .promo-banner {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        padding: 40px;
        border-radius: 20px;
        margin: 40px auto;
        max-width: 84rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: white;
    }
    .promo-content h3 {
        font-size: 1.8rem;
        margin-bottom: 10px;
    }
    .promo-content p {
        font-size: 1.1rem;
        opacity: 0.9;
    }
    .promo-code {
        background: rgba(255,255,255,0.2);
        padding: 15px 30px;
        border-radius: 10px;
        border: 2px dashed white;
        text-align: center;
    }
    .promo-code-label {
        font-size: 0.85rem;
        opacity: 0.9;
    }
    .promo-code-value {
        font-size: 1.5rem;
        font-weight: bold;
        letter-spacing: 2px;
    }

    /* Pagination */
    .pagination-modern {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 40px;
    }
    .pagination-modern a,
    .pagination-modern span {
        padding: 12px 18px;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.3s;
        font-weight: 500;
    }
    .pagination-modern a {
        background: white;
        color: #667eea;
        border: 2px solid #e0e0e0;
    }
    .pagination-modern a:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }
    .pagination-modern .current {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .pagination-modern .disabled {
        background: #ecf0f1;
        color: #95a5a6;
        cursor: not-allowed;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px;
        background: white;
        border-radius: 15px;
    }
    .empty-state-icon {
        font-size: 5rem;
        margin-bottom: 20px;
    }
    .empty-state h3 {
        color: #2c3e50;
        margin-bottom: 10px;
    }
    .empty-state p {
        color: #7f8c8d;
    }
</style>

<!-- Hero Section -->
<div class="hero-section">
    <h1 class="hero-title">🍔 Chào mừng đến FastFood!</h1>
    <p class="hero-subtitle">Thực đơn phong phú - Giao hàng tận nơi - Chất lượng hàng đầu</p>
    <div class="hero-stats">
        <div class="hero-stat">
            <div class="hero-stat-number">50+</div>
            <div class="hero-stat-label">Mon an</div>
        </div>
        <div class="hero-stat">
            <div class="hero-stat-number">10K+</div>
            <div class="hero-stat-label">Khach hang</div>
        </div>
        <div class="hero-stat">
            <div class="hero-stat-number">30min</div>
            <div class="hero-stat-label">Giao hang</div>
        </div>
    </div>
</div>

<!-- Search & Filter -->
<form class="search-filter-bar" method="GET" action="">
    <input type="hidden" name="quanly" value="trangchu">
    <div class="search-box">
        <input type="text" name="search" placeholder="Tim mon an..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
    </div>
    <select name="danhmuc" class="filter-select">
        <option value="">Tat ca danh muc</option>
        <?php while($dm = mysqli_fetch_array($query_dm)) { ?>
            <option value="<?php echo $dm['id_danhmuc']; ?>" <?php echo (isset($_GET['danhmuc']) && $_GET['danhmuc'] == $dm['id_danhmuc']) ? 'selected' : ''; ?>>
                <?php echo $dm['tendanhmuc']; ?>
            </option>
        <?php } ?>
    </select>
    <button type="submit" class="btn-search">🔍 Tim kiem</button>
</form>

<!-- Promo Banner -->
<div class="promo-banner">
    <div class="promo-content">
        <h3>🎉 Khuyen mai dac biet!</h3>
        <p>Giam 15% cho don hang dau tien khi dat hang qua website</p>
    </div>
    <div class="promo-code">
        <div class="promo-code-label">Nhap ma</div>
        <div class="promo-code-value">FAST15</div>
    </div>
</div>

<!-- Section Title -->
<h2 class="section-title">🔥 Mon an noi bat</h2>

<!-- Products Grid -->
<div class="product-grid">
    <?php
    $counter = 0;
    while ($row = mysqli_fetch_array($query_pro)) {
        $counter++;
        $badge = ($counter <= 2) ? 'hot' : (($counter <= 4) ? 'new' : '');
        $badge_text = ($counter <= 2) ? 'HOT' : (($counter <= 4) ? 'NEW' : '');
    ?>
        <div class="product-card">
            <?php if($badge) { ?>
                <span class="product-badge <?php echo $badge; ?>"><?php echo $badge_text; ?></span>
            <?php } ?>
            <img src="uploads/<?php echo $row['hinhanh'] ?>" alt="<?php echo $row['tensanpham'] ?>" class="product-image">
            <div class="product-info">
                <div class="product-category"><?php echo $row['tendanhmuc'] ?></div>
                <h3 class="product-name"><?php echo $row['tensanpham'] ?></h3>
                <p class="product-desc"><?php echo substr($row['tomtat'], 0, 60) . '...'; ?></p>
                <div class="product-footer">
                    <div class="product-price">
                        <?php echo number_format($row['giasp'], 0, ',', '.'); ?>d
                    </div>
                    <form method="POST" action="" style="display: flex; gap: 8px; align-items: center;">
                        <input type="hidden" name="id_sanpham" value="<?php echo $row['id_sanpham']; ?>">
                        <input type="hidden" name="ten_sanpham" value="<?php echo $row['tensanpham']; ?>">
                        <input type="hidden" name="giasp" value="<?php echo $row['giasp']; ?>">
                        <input type="hidden" name="hinhanh" value="<?php echo $row['hinhanh']; ?>">
                        <input type="number" name="soluong" value="1" min="1" max="10" style="width: 50px; padding: 8px; border: 2px solid #667eea; border-radius: 8px; text-align: center; font-size: 1rem;">
                        <button type="submit" name="them_giohang" style="padding: 10px 20px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border: none; border-radius: 20px; font-size: 0.9rem; cursor: pointer; font-weight: bold;">
                            + Thêm
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
        <h3>Khong tim thay san pham</h3>
        <p>Vui long thu tim kiem voi tu khoa khac hoac chon danh muc khac</p>
    </div>
<?php } ?>

<!-- Pagination -->
<div class="pagination-modern">
    <?php if ($page > 1) { ?>
        <a href="?quanly=trangchu&trang=<?php echo $page - 1; ?><?php echo isset($_GET['search']) ? '&search='.$_GET['search'] : ''; ?><?php echo isset($_GET['danhmuc']) ? '&danhmuc='.$_GET['danhmuc'] : ''; ?>">← Truoc</a>
    <?php } else { ?>
        <span class="disabled">← Truoc</span>
    <?php } ?>

    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
        <?php if ($i == $page) { ?>
            <span class="current"><?php echo $i; ?></span>
        <?php } else { ?>
            <a href="?quanly=trangchu&trang=<?php echo $i; ?><?php echo isset($_GET['search']) ? '&search='.$_GET['search'] : ''; ?><?php echo isset($_GET['danhmuc']) ? '&danhmuc='.$_GET['danhmuc'] : ''; ?>"><?php echo $i; ?></a>
        <?php } ?>
    <?php } ?>

    <?php if ($page < $total_pages) { ?>
        <a href="?quanly=trangchu&trang=<?php echo $page + 1; ?><?php echo isset($_GET['search']) ? '&search='.$_GET['search'] : ''; ?><?php echo isset($_GET['danhmuc']) ? '&danhmuc='.$_GET['danhmuc'] : ''; ?>">Sau →</a>
    <?php } else { ?>
        <span class="disabled">Sau →</span>
    <?php } ?>
</div>



<!-- Auto reset timer for kiosk mode -->
<script src="js/timeout.js"></script>

<!-- Include footer with chatbot -->
