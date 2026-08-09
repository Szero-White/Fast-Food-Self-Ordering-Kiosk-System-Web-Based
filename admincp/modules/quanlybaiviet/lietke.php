<?php
$sql_lietke_bv = "
    SELECT tbl_baiviet.*, tbl_danhmucbaiviet.tendanhmucbv
    FROM tbl_baiviet
    LEFT JOIN tbl_danhmucbaiviet ON tbl_baiviet.id_danhmuc = tbl_danhmucbaiviet.id_baiviet
    ORDER BY tbl_baiviet.id_bv DESC
";
$query_lietke_bv = mysqli_query($mysqli, $sql_lietke_bv);
?>

<div class="content-card crud-hero info">
    <div class="card-body-custom crud-hero-body">
        <div class="crud-title-group">
            <div class="crud-icon info">
                <i class="fas fa-newspaper"></i>
            </div>
            <div>
                <h4 class="crud-title">Quản lý bài viết</h4>
                <p class="crud-subtitle">Danh sách bài viết và khuyến mãi trên website</p>
            </div>
        </div>
        <a href="?action=quanlybaiviet&query=them" class="btn-custom btn-custom-success text-decoration-none">
            <i class="fas fa-plus"></i>
            <span>Viết bài mới</span>
        </a>
    </div>
</div>

<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-list me-2 crud-card-title-icon info"></i>Danh sách bài viết</h5>
        <div class="input-group crud-search">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" placeholder="Tìm bài viết..." data-search="#article-table">
        </div>
    </div>
    <div class="card-body-custom crud-table-body">
        <div class="table-container">
            <table class="custom-table" id="article-table">
                <thead>
                    <tr>
                        <th class="crud-table-index">STT</th>
                        <th class="crud-table-image">Hình ảnh</th>
                        <th>Tiêu đề</th>
                        <th>Danh mục</th>
                        <th class="crud-table-actions">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    while ($row = mysqli_fetch_array($query_lietke_bv)) {
                        $i++;
                        $idBaiViet = (int)$row['id_bv'];
                        $tenBaiViet = htmlspecialchars($row['tenbaiviet'] ?? '', ENT_QUOTES, 'UTF-8');
                        $tomTat = htmlspecialchars(mb_substr(strip_tags($row['tomtat'] ?? ''), 0, 60, 'UTF-8'), ENT_QUOTES, 'UTF-8');
                        $hinhAnh = htmlspecialchars(upload_url($row['hinhanh'] ?? ''), ENT_QUOTES, 'UTF-8');
                        $tenDanhMuc = trim((string)($row['tendanhmucbv'] ?? ''));
                    ?>
                    <tr>
                        <td><strong>#<?php echo $i; ?></strong></td>
                        <td>
                            <img src="<?php echo $hinhAnh; ?>" alt="<?php echo $tenBaiViet; ?>" class="crud-thumb">
                        </td>
                        <td>
                            <div class="crud-entity-title"><?php echo $tenBaiViet; ?></div>
                            <small class="crud-muted"><?php echo $tomTat; ?>...</small>
                        </td>
                        <td>
                            <?php if ($tenDanhMuc !== '') { ?>
                                <span class="crud-pill success"><?php echo htmlspecialchars($tenDanhMuc, ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php } else { ?>
                                <span class="crud-pill warning">Chưa phân loại</span>
                            <?php } ?>
                        </td>
                        <td>
                            <div class="action-group crud-action-center">
                                <a href="?action=quanlybaiviet&query=sua&idbaiviet=<?php echo $idBaiViet; ?>" class="btn-action edit" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="modules/quanlybaiviet/xuly.php?idbaiviet=<?php echo $idBaiViet; ?>" class="btn-action delete" title="Xóa" data-confirm="Bạn có chắc chắn muốn xóa bài viết này?">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
