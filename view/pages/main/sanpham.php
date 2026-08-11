<?php
$idSanPham = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

if ($idSanPham <= 0) {
    echo '<div class="product-detail-empty">Sản phẩm không tồn tại.</div>';
    return;
}

if (!function_exists('product_detail_plain_text')) {
    function product_detail_plain_text(?string $value): string
    {
        $text = str_replace(['<br>', '<br/>', '<br />', '</p>'], "\n", (string)$value);
        $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return trim(preg_replace('/[ \t]+/', ' ', $text) ?? '');
    }
}

if (!function_exists('product_detail_highlights')) {
    function product_detail_highlights(string $text, int $limit = 3): array
    {
        $sentences = preg_split('/(?<=[.!?])\s+|\n+/u', $text) ?: [];
        $highlights = [];

        foreach ($sentences as $sentence) {
            $sentence = trim($sentence);
            if ($sentence === '') {
                continue;
            }

            if (mb_strlen($sentence, 'UTF-8') > 150) {
                $sentence = mb_substr($sentence, 0, 147, 'UTF-8') . '...';
            }

            $highlights[] = $sentence;
            if (count($highlights) >= $limit) {
                break;
            }
        }

        return $highlights;
    }
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
$tenDanhMucRaw = (string)($row_chitiet['tendanhmuc'] ?? 'Chưa phân loại');
$tenDanhMuc = htmlspecialchars($tenDanhMucRaw, ENT_QUOTES, 'UTF-8');
$hinhAnh = htmlspecialchars(upload_url($row_chitiet['hinhanh'] ?? ''), ENT_QUOTES, 'UTF-8');
$giaSanPham = (float)($row_chitiet['giasp'] ?? 0);
$soLuong = (int)($row_chitiet['soluong'] ?? 0);
$tomTat = product_detail_plain_text($row_chitiet['tomtat'] ?? '');
$noiDungChiTiet = product_detail_plain_text($row_chitiet['noidung'] ?? '');
$highlightSource = $noiDungChiTiet !== '' ? $noiDungChiTiet : $tomTat;
$highlights = product_detail_highlights($highlightSource);
$stockLabel = $soLuong > 0 ? 'Còn món' : 'Tạm hết';
$stockClass = $soLuong > 0 ? 'is-available' : 'is-empty';
?>

<section class="product-detail">
    <div class="product-detail-card">
        <div class="product-detail-media">
            <img src="<?php echo $hinhAnh; ?>" alt="<?php echo $tenSanPham; ?>">
        </div>

        <div class="product-detail-content">
            <div class="product-detail-eyebrow"><?php echo $tenDanhMuc; ?></div>
            <h1 class="product-detail-title"><?php echo $tenSanPham; ?></h1>

            <div class="product-detail-quick-info">
                <div class="product-detail-info-chip">
                    <span>Mã món</span>
                    <strong><?php echo $maSanPham; ?></strong>
                </div>
                <div class="product-detail-info-chip">
                    <span>Giá bán</span>
                    <strong class="product-detail-price"><?php echo number_format($giaSanPham, 0, ',', '.'); ?>đ</strong>
                </div>
                <div class="product-detail-info-chip">
                    <span>Tồn kho</span>
                    <strong><?php echo $soLuong; ?> phần</strong>
                </div>
                <div class="product-detail-info-chip <?php echo $stockClass; ?>">
                    <span>Trạng thái</span>
                    <strong><?php echo $stockLabel; ?></strong>
                </div>
            </div>

            <?php if ($tomTat !== '') { ?>
                <div class="product-detail-summary">
                    <span class="product-detail-section-label">
                        <i class="fas fa-align-left"></i>
                        Tóm tắt
                    </span>
                    <p><?php echo nl2br(htmlspecialchars($tomTat, ENT_QUOTES, 'UTF-8')); ?></p>
                </div>
            <?php } ?>

            <?php if ($highlights !== []) { ?>
                <div class="product-detail-highlights">
                    <h3>
                        <i class="fas fa-star"></i>
                        Điểm nổi bật
                    </h3>
                    <ul>
                        <?php foreach ($highlights as $highlight) { ?>
                            <li><?php echo htmlspecialchars($highlight, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>

            <?php if ($noiDungChiTiet !== '') { ?>
                <details class="product-detail-full-text">
                    <summary>
                        <span>Xem mô tả đầy đủ</span>
                        <i class="fas fa-chevron-down"></i>
                    </summary>
                    <p><?php echo nl2br(htmlspecialchars($noiDungChiTiet, ENT_QUOTES, 'UTF-8')); ?></p>
                </details>
            <?php } ?>

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
