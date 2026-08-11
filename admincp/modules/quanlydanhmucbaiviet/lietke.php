<?php
$sql_lietke_danhmucbv = "
    SELECT danhmuc.*,
           COUNT(baiviet.id_bv) AS tong_baiviet
    FROM tbl_danhmucbaiviet AS danhmuc
    LEFT JOIN tbl_baiviet AS baiviet ON baiviet.id_danhmuc = danhmuc.id_baiviet
    GROUP BY danhmuc.id_baiviet, danhmuc.tendanhmucbv, danhmuc.thutu
    ORDER BY danhmuc.thutu ASC
";
$query_lietke_danhmucbv = mysqli_query($mysqli, $sql_lietke_danhmucbv);
?>

<div class="content-card crud-hero info">
    <div class="card-body-custom crud-hero-body">
        <div class="crud-title-group">
            <div class="crud-icon info">
                <i class="fas fa-folder-open"></i>
            </div>
            <div>
                <h4 class="crud-title">Danh mục bài viết</h4>
                <p class="crud-subtitle">Quản lý phân loại bài viết và khuyến mãi</p>
            </div>
        </div>
        <a href="?action=quanlydanhmucbaiviet&query=them" class="btn-custom btn-custom-success text-decoration-none">
            <i class="fas fa-plus"></i>
            <span>Thêm danh mục</span>
        </a>
    </div>
</div>

<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-th-large me-2 crud-card-title-icon info"></i>Danh sách danh mục</h5>
        <div class="input-group crud-search">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" placeholder="Tìm danh mục..." data-search="#post-category-grid">
        </div>
    </div>
    <div class="card-body-custom">
        <div class="row g-3" id="post-category-grid">
            <?php
            $i = 0;
            while ($row = mysqli_fetch_array($query_lietke_danhmucbv)) {
                $i++;
                $idDanhMuc = (int)$row['id_baiviet'];
                $tenDanhMuc = htmlspecialchars($row['tendanhmucbv'], ENT_QUOTES, 'UTF-8');
                $thuTu = (int)$row['thutu'];
                $tongBaiViet = (int)($row['tong_baiviet'] ?? 0);
                $tone = 'tone-' . ((($i - 1) % 6) + 1);
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="category-card <?php echo $tone; ?>">
                    <div class="category-card-top">
                        <div class="category-icon <?php echo $tone; ?>"><i class="fas fa-folder"></i></div>
                        <span class="category-usage-pill">
                            <i class="fas fa-newspaper"></i>
                            <?php echo $tongBaiViet; ?> bài viết
                        </span>
                    </div>
                    <div class="category-card-content">
                        <h5 class="crud-entity-title category-title"><?php echo $tenDanhMuc; ?></h5>
                        <div class="category-meta-line">
                            <span>ID: <?php echo $idDanhMuc; ?></span>
                            <span>Thứ tự: <?php echo $thuTu; ?></span>
                        </div>
                    </div>
                    <div class="d-flex gap-2 category-actions">
                        <a href="?action=quanlydanhmucbaiviet&query=sua&idbaiviet=<?php echo $idDanhMuc; ?>" class="btn-action edit" title="Sửa">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="modules/quanlydanhmucbaiviet/xuly.php?idbaiviet=<?php echo $idDanhMuc; ?>" class="btn-action delete" title="Xóa" data-confirm="Bạn có chắc chắn muốn xóa danh mục này?">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
