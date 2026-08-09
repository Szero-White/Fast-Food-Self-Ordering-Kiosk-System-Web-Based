<?php
$idSanPham = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

if ($idSanPham <= 0) {
    echo '<div class="product-detail-empty">Sản phẩm không tồn tại.</div>';
    return;
}

$stmt = mysqli_prepare(
    $mysqli,
    'SELECT tbl_sanpham.*, tbl_danhmuc.tendanhmuc
     FROM tbl_sanpham
     LEFT JOIN tbl_danhmuc ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc
     WHERE tbl_sanpham.id_sanpham = ?
     LIMIT 1'
);
mysqli_stmt_bind_param($stmt, 'i', $idSanPham);
mysqli_stmt_execute($stmt);
$query_chitiet = mysqli_stmt_get_result($stmt);
$row_chitiet = mysqli_fetch_assoc($query_chitiet);
mysqli_stmt_close($stmt);

if (!$row_chitiet) {
    echo '<div class="product-detail-empty">Sản phẩm không tồn tại.</div>';
    return;
}

$tenSanPham = htmlspecialchars($row_chitiet['tensanpham'] ?? '', ENT_QUOTES, 'UTF-8');
$maSanPham = htmlspecialchars($row_chitiet['masp'] ?? '', ENT_QUOTES, 'UTF-8');
$tenDanhMuc = htmlspecialchars($row_chitiet['tendanhmuc'] ?? 'Chưa phân loại', ENT_QUOTES, 'UTF-8');
$hinhAnh = htmlspecialchars(upload_url($row_chitiet['hinhanh'] ?? ''), ENT_QUOTES, 'UTF-8');
$tomTat = htmlspecialchars($row_chitiet['tomtat'] ?? '', ENT_QUOTES, 'UTF-8');
?>

<section class="product-detail">
    <div class="product-detail-card">
        <div class="product-detail-media">
            <img src="<?php echo $hinhAnh; ?>" alt="<?php echo $tenSanPham; ?>">
        </div>
        <div class="product-detail-content">
            <h1 class="product-detail-title"><?php echo $tenSanPham; ?></h1>

            <div class="product-detail-meta">
                <div>Mã sản phẩm: <?php echo $maSanPham; ?></div>
                <div>Giá sản phẩm: <span class="product-detail-price"><?php echo number_format((float)$row_chitiet['giasp'], 0, ',', '.'); ?>đ</span></div>
                <div>Số lượng sản phẩm: <?php echo (int)$row_chitiet['soluong']; ?></div>
                <div>Danh mục sản phẩm: <?php echo $tenDanhMuc; ?></div>
            </div>

            <div class="product-detail-description">
                <h4><i class="fas fa-pen-to-square"></i> Mô tả</h4>
                <p><?php echo nl2br($tomTat); ?></p>
            </div>

            <div class="product-detail-actions">
                <a href="index.php?quanly=danhmucsanpham&id=<?php echo (int)$row_chitiet['id_danhmuc']; ?>" class="btn btn-light product-detail-btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Xem thêm <?php echo $tenDanhMuc; ?></span>
                </a>
                <a href="index.php" class="btn btn-outline-light product-detail-btn">
                    <i class="fas fa-house"></i>
                    <span>Về trang chủ</span>
                </a>
            </div>

            <div class="product-detail-note">
                <i class="fas fa-lightbulb"></i>
                Mẹo: Ghé quầy để đặt món và nhận ưu đãi đặc biệt!
            </div>
        </div>
    </div>
</section>
