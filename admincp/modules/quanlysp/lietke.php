<?php
    $sql_lietke_sp = "SELECT * FROM tbl_sanpham,tbl_danhmuc WHERE tbl_sanpham.id_danhmuc=tbl_danhmuc.id_danhmuc ORDER BY id_sanpham DESC";
    $query_lietke_sp = mysqli_query($mysqli,$sql_lietke_sp);
?>

<!-- Page Header -->
<div class="content-card" style="background: linear-gradient(135deg, rgba(255,107,107,0.1) 0%, rgba(238,90,82,0.1) 100%); border: 1px solid rgba(255,107,107,0.2);">
    <div class="card-body-custom">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-utensils" style="color: white; font-size: 24px;"></i>
                </div>
                <div>
                    <h4 style="margin: 0; font-weight: 700; color: #333;">Quản lý thực đơn</h4>
                    <p style="margin: 0; color: #888; font-size: 14px;">Danh sách món ăn trong thực đơn</p>
                </div>
            </div>
            <a href="?action=quanlymonan&query=them" class="btn-custom btn-custom-primary text-decoration-none d-inline-flex align-items-center" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);">
                <i class="fas fa-plus me-2"></i>Thêm món mới
            </a>
        </div>
    </div>
</div>

<!-- Products Table -->
<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-list me-2" style="color: #ff6b6b;"></i>Danh sách món ăn</h5>
        <div class="d-flex gap-2">
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text" style="background: white; border-right: none;"><i class="fas fa-search" style="color: #888;"></i></span>
                <input type="text" class="form-control" placeholder="Tìm món ăn..." style="border-left: none;">
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
                        <th>Tên món</th>
                        <th>Mã món</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Danh mục</th>
                        <th style="width: 120px; text-align: center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    while($row = mysqli_fetch_array($query_lietke_sp)){
                        $i++;
                    ?>
                    <tr>
                        <td><strong>#<?php echo $i ?></strong></td>
                        <td>
                            <img src="../uploads/<?php echo $row['hinhanh'] ?>" style="width: 70px; height: 70px; object-fit: cover; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #333;"><?php echo $row['tensanpham'] ?></div>
                            <small style="color: #888;"><?php echo substr($row['tomtat'], 0, 50) ?>...</small>
                        </td>
                        <td><span style="font-family: monospace; background: rgba(255,107,107,0.1); padding: 4px 10px; border-radius: 6px; color: #ff6b6b;"><?php echo $row['masp'] ?></span></td>
                        <td><strong style="color: #27ae60;"><?php echo number_format($row['giasp'], 0, ',', '.') ?>đ</strong></td>
                        <td><?php echo $row['soluong'] ?></td>
                        <td><span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px;"><?php echo $row['tendanhmuc'] ?></span></td>
                        <td>
                            <div class="action-group" style="justify-content: center;">
                                <a href="?action=quanlymonan&query=sua&idsanpham=<?php echo $row['id_sanpham'] ?>" class="btn-action edit" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="modules/quanlysp/xuly.php?idsanpham=<?php echo $row['id_sanpham'] ?>" class="btn-action delete" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
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
