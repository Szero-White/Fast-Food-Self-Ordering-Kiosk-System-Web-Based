<?php
require_once __DIR__ . '/home/menu_product_card.php';

$categoryId = isset($_GET['id']) ? max(0, (int) $_GET['id']) : 0;

$categoryStmt = mysqli_prepare($mysqli, 'SELECT * FROM tbl_danhmuc WHERE id_danhmuc = ? LIMIT 1');
mysqli_stmt_bind_param($categoryStmt, 'i', $categoryId);
mysqli_stmt_execute($categoryStmt);
$category = mysqli_fetch_assoc(mysqli_stmt_get_result($categoryStmt));
mysqli_stmt_close($categoryStmt);

$products = [];
if ($category) {
    $productStmt = mysqli_prepare(
        $mysqli,
        'SELECT tbl_sanpham.*, tbl_danhmuc.tendanhmuc
         FROM tbl_sanpham
         INNER JOIN tbl_danhmuc ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc
         WHERE tbl_sanpham.id_danhmuc = ?
         ORDER BY tbl_sanpham.id_sanpham DESC'
    );
    mysqli_stmt_bind_param($productStmt, 'i', $categoryId);
    mysqli_stmt_execute($productStmt);
    $productResult = mysqli_stmt_get_result($productStmt);

    while ($product = mysqli_fetch_assoc($productResult)) {
        $products[] = $product;
    }

    mysqli_stmt_close($productStmt);
}
?>

<section class="menu-category-page">
    <header class="menu-category-hero">
        <div class="menu-category-icon">
            <i class="<?php echo menu_category_icon((string) ($category['tendanhmuc'] ?? '')); ?>"></i>
        </div>
        <div class="menu-category-copy">
            <h1><?php echo htmlspecialchars($category['tendanhmuc'] ?? 'Danh mục món ăn', ENT_QUOTES, 'UTF-8'); ?></h1>
            <p><?php echo count($products); ?> món đang có trong danh mục này</p>
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
    <?php } ?>
</section>
