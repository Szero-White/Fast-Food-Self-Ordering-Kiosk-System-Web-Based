<?php
$idBaiViet = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($idBaiViet <= 0) {
    echo '<div class="news-detail-empty"><h2>Bài viết không tồn tại</h2></div>';
    return;
}

$stmt = mysqli_prepare(
    $mysqli,
    'SELECT tbl_baiviet.*, tbl_danhmucbaiviet.tendanhmucbv
     FROM tbl_baiviet
     LEFT JOIN tbl_danhmucbaiviet ON tbl_baiviet.id_danhmuc = tbl_danhmucbaiviet.id_baiviet
     WHERE tbl_baiviet.id_bv = ?
     LIMIT 1'
);
mysqli_stmt_bind_param($stmt, 'i', $idBaiViet);
mysqli_stmt_execute($stmt);
$query_bv = mysqli_stmt_get_result($stmt);
$row_bv = mysqli_fetch_assoc($query_bv);
mysqli_stmt_close($stmt);

if (!$row_bv) {
    echo '<div class="news-detail-empty"><h2>Không tìm thấy bài viết</h2></div>';
    return;
}

$tenBaiViet = htmlspecialchars($row_bv['tenbaiviet'] ?? '', ENT_QUOTES, 'UTF-8');
$tenDanhMuc = htmlspecialchars($row_bv['tendanhmucbv'] ?? 'Tin tức', ENT_QUOTES, 'UTF-8');
$tomTat = htmlspecialchars($row_bv['tomtat'] ?? '', ENT_QUOTES, 'UTF-8');
$noiDung = htmlspecialchars($row_bv['noidung'] ?? '', ENT_QUOTES, 'UTF-8');
$hinhAnh = htmlspecialchars(upload_url($row_bv['hinhanh'] ?? ''), ENT_QUOTES, 'UTF-8');
?>

<article class="news-detail">
    <div class="news-detail-media">
        <img src="<?php echo $hinhAnh; ?>" alt="<?php echo $tenBaiViet; ?>">
    </div>

    <div class="news-detail-panel">
        <header class="news-detail-header">
            <div class="news-detail-category">
                <i class="fas fa-tag"></i>
                <?php echo $tenDanhMuc; ?>
            </div>
            <h1 class="news-detail-title"><?php echo $tenBaiViet; ?></h1>
            <div class="news-detail-meta">
                <span>
                    <i class="fas fa-calendar-days"></i>
                    <?php echo date('d/m/Y'); ?>
                </span>
                <span>
                    <i class="fas fa-newspaper"></i>
                    Tin tức khuyến mãi
                </span>
            </div>
        </header>

        <?php if ($tomTat !== '') { ?>
            <section class="news-detail-box news-detail-summary">
                <h2>
                    <i class="fas fa-list"></i>
                    Tóm tắt
                </h2>
                <p><?php echo nl2br($tomTat); ?></p>
            </section>
        <?php } ?>

        <?php if ($noiDung !== '') { ?>
            <section class="news-detail-box news-detail-content">
                <h2>
                    <i class="fas fa-star"></i>
                    Nội dung nổi bật
                </h2>
                <div><?php echo nl2br($noiDung); ?></div>
            </section>
        <?php } ?>

        <nav class="news-detail-nav">
            <a href="index.php?quanly=danhmucbaiviet&id=<?php echo (int)$row_bv['id_danhmuc']; ?>" class="news-detail-btn news-detail-btn--light">
                <i class="fas fa-arrow-left"></i>
                <span>Xem thêm <?php echo $tenDanhMuc; ?></span>
            </a>
            <a href="index.php" class="news-detail-btn">
                <i class="fas fa-house"></i>
                <span>Về trang chủ</span>
            </a>
        </nav>

        <div class="news-detail-tip">
            <i class="fas fa-lightbulb"></i>
            Theo dõi khuyến mãi mới để đặt món tiết kiệm hơn.
        </div>
    </div>
</article>
