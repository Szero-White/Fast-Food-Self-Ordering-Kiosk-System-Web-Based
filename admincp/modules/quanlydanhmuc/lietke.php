<?php
$sql_lietke_danhmucsp = "
    SELECT danhmuc.*,
           COUNT(sanpham.id_sanpham) AS tong_sanpham
    FROM tbl_danhmuc AS danhmuc
    LEFT JOIN tbl_sanpham AS sanpham ON sanpham.id_danhmuc = danhmuc.id_danhmuc
    GROUP BY danhmuc.id_danhmuc, danhmuc.tendanhmuc, danhmuc.thutu
    ORDER BY danhmuc.thutu ASC
";
$query_lietke_danhmucsp = mysqli_query($mysqli, $sql_lietke_danhmucsp);
?>

<div class="content-card crud-hero">
    <div class="card-body-custom crud-hero-body">
        <div class="crud-title-group">
            <div class="crud-icon">
                <i class="fas fa-list-alt"></i>
            </div>
            <div>
                <h4 class="crud-title">Danh mục thực đơn</h4>
                <p class="crud-subtitle">Quản lý các danh mục món ăn</p>
            </div>
        </div>
        <a href="?action=quanlydanhmucsp&query=them" class="btn-custom btn-custom-primary text-decoration-none">
            <i class="fas fa-plus"></i>
            <span>Thêm danh mục</span>
        </a>
    </div>
</div>

<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-th-large me-2 crud-card-title-icon"></i>Danh sách danh mục</h5>
        <div class="input-group crud-search">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" placeholder="Tìm danh mục..." data-search="#menu-category-grid">
        </div>
    </div>
    <div class="card-body-custom">
        <div class="row g-3" id="menu-category-grid">
            <?php
            $i = 0;
            while ($row = mysqli_fetch_array($query_lietke_danhmucsp)) {
                $i++;
                $idDanhMuc = (int)$row['id_danhmuc'];
                $tenDanhMuc = htmlspecialchars($row['tendanhmuc'], ENT_QUOTES, 'UTF-8');
                $thuTu = (int)$row['thutu'];
                $tongSanPham = (int)($row['tong_sanpham'] ?? 0);
                $tone = 'tone-' . ((($i - 1) % 6) + 1);
                $firstLetter = htmlspecialchars(mb_strtoupper(mb_substr($row['tendanhmuc'], 0, 1, 'UTF-8'), 'UTF-8'), ENT_QUOTES, 'UTF-8');
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="category-card <?php echo $tone; ?>">
                    <div class="category-card-top">
                        <div class="category-icon <?php echo $tone; ?>"><?php echo $firstLetter; ?></div>
                        <span class="category-usage-pill">
                            <i class="fas fa-utensils"></i>
                            <?php echo $tongSanPham; ?> món
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
                        <a href="?action=quanlydanhmucsp&query=sua&iddanhmuc=<?php echo $idDanhMuc; ?>" class="btn-action edit" title="Sửa">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="modules/quanlydanhmuc/xuly.php?iddanhmuc=<?php echo $idDanhMuc; ?>" class="btn-action delete" title="Xóa" data-confirm="Bạn có chắc chắn muốn xóa danh mục này?">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
