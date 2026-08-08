<?php
$sql_lietke_bv = "SELECT * FROM tbl_baiviet,tbl_danhmucbaiviet WHERE tbl_baiviet.id_danhmuc=tbl_danhmucbaiviet.id_baiviet ORDER BY id_baiviet DESC";
$query_lietke_bv = mysqli_query($mysqli, $sql_lietke_bv);
?>

<!-- Page Header -->
<div class="content-card" style="background: linear-gradient(135deg, rgba(17,153,142,0.1) 0%, rgba(56,239,125,0.1) 100%); border: 1px solid rgba(17,153,142,0.2);">
    <div class="card-body-custom">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-newspaper" style="color: white; font-size: 24px;"></i>
                </div>
                <div>
                    <h4 style="margin: 0; font-weight: 700; color: #333;">Quản lý bài viết</h4>
                    <p style="margin: 0; color: #888; font-size: 14px;">Danh sách bài viết trên website</p>
                </div>
            </div>
            <a href="?action=quanlybaiviet&query=them" class="btn-custom btn-custom-primary text-decoration-none d-inline-flex align-items-center" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <i class="fas fa-plus me-2"></i>Viết bài mới
            </a>
        </div>
    </div>
</div>

<!-- Articles Table -->
<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-list me-2" style="color: #11998e;"></i>Danh sách bài viết</h5>
        <div class="d-flex gap-2">
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text" style="background: white; border-right: none;"><i class="fas fa-search" style="color: #888;"></i></span>
                <input type="text" class="form-control" placeholder="Tìm bài viết..." style="border-left: none;">
            </div>
        </div>
    </div>
    <div class="card-body-custom" style="padding: 0;">
        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">STT</th>
                        <th style="width: 100px;">Hình ảnh</th>
                        <th>Tiêu đề</th>
                        <th>Danh mục</th>
                        <th style="width: 120px; text-align: center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    while ($row = mysqli_fetch_array($query_lietke_bv)) {
                        $i++;
                    ?>
                    <tr>
                        <td><strong>#<?php echo $i ?></strong></td>
                        <td>
                            <img src="../uploads/<?php echo $row['hinhanh'] ?>" style="width: 70px; height: 70px; object-fit: cover; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #333;"><?php echo $row['tenbaiviet'] ?></div>
                            <small style="color: #888;"><?php echo substr($row['tomtat'], 0, 60) ?>...</small>
                        </td>
                        <td>
                            <span style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px;">
                                <?php echo $row['tendanhmucbv'] ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-group" style="justify-content: center;">
                                <a href="?action=quanlybaiviet&query=sua&idbaiviet=<?php echo $row['id_bv'] ?>" class="btn-action edit" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="modules/quanlybaiviet/xuly.php?idbaiviet=<?php echo $row['id_bv'] ?>" class="btn-action delete" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>