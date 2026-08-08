<?php
$sql_sua_sp = "SELECT * FROM tbl_sanpham WHERE id_sanpham = '$_GET[idsanpham]' limit 1";
$query_sua_sp = mysqli_query($mysqli, $sql_sua_sp);
?>

<!-- Page Header -->
<div class="content-card" style="background: linear-gradient(135deg, rgba(255,107,107,0.1) 0%, rgba(238,90,82,0.1) 100%); border: 1px solid rgba(255,107,107,0.2);">
    <div class="card-body-custom">
        <div class="d-flex align-items-center gap-3">
            <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-edit" style="color: white; font-size: 24px;"></i>
            </div>
            <div>
                <h4 style="margin: 0; font-weight: 700; color: #333;">Sửa thông tin món ăn</h4>
                <p style="margin: 0; color: #888; font-size: 14px;">Cập nhật thông tin món ăn trong thực đơn</p>
            </div>
        </div>
    </div>
</div>

<?php
while ($row = mysqli_fetch_array($query_sua_sp)) {
?>
<!-- Edit Product Form -->
<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-utensils me-2" style="color: #ff6b6b;"></i>Sửa: <?php echo $row['tensanpham'] ?></h5>
    </div>
    <div class="card-body-custom">
        <form method="POST" action="modules/quanlysp/xuly.php?idsanpham=<?php echo $row['id_sanpham'] ?>" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Tên món ăn <span style="color: #e74c3c;">*</span></label>
                        <input type="text" name="tensanpham" class="form-control-custom" value="<?php echo $row['tensanpham'] ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Mã món <span style="color: #e74c3c;">*</span></label>
                        <input type="text" name="masp" class="form-control-custom" value="<?php echo $row['masp'] ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Giá (VNĐ) <span style="color: #e74c3c;">*</span></label>
                        <input type="number" name="giasp" class="form-control-custom" value="<?php echo $row['giasp'] ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Số lượng <span style="color: #e74c3c;">*</span></label>
                        <input type="number" name="soluong" class="form-control-custom" value="<?php echo $row['soluong'] ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Thứ tự hiển thị</label>
                        <input type="number" name="thutu" class="form-control-custom" value="<?php echo $row['thutu'] ?>">
                    </div>
                </div>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Danh mục <span style="color: #e74c3c;">*</span></label>
                <select name="danhmuc" class="form-control-custom" required>
                    <?php
                    $sql_danhmuc = "SELECT * FROM tbl_danhmuc ORDER BY id_danhmuc DESC";
                    $query_danhmuc = mysqli_query($mysqli, $sql_danhmuc);
                    while ($row_danhmuc = mysqli_fetch_array($query_danhmuc)) {
                        $selected = ($row_danhmuc['id_danhmuc'] == $row['id_danhmuc']) ? 'selected' : '';
                    ?>
                    <option value="<?php echo $row_danhmuc['id_danhmuc'] ?>" <?php echo $selected ?>><?php echo $row_danhmuc['tendanhmuc'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Tóm tắt</label>
                <textarea rows="4" name="tomtat" class="form-control-custom"><?php echo $row['tomtat'] ?></textarea>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Nội dung chi tiết</label>
                <textarea rows="8" name="noidung" class="form-control-custom" data-editor><?php echo $row['noidung'] ?></textarea>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Hình ảnh món ăn</label>
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <img src="../uploads/<?php echo $row['hinhanh'] ?>" style="width: 100%; max-width: 200px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        <p class="mt-2" style="color: #888; font-size: 13px;">Hình ảnh hiện tại</p>
                    </div>
                    <div class="col-md-8">
                        <div class="image-upload" onclick="document.getElementById('hinhanh').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click để chọn hình ảnh mới</p>
                            <small style="color: #aaa;">Để trống nếu không đổi hình</small>
                            <input type="file" name="hinhanh" id="hinhanh" accept="image/*" style="display: none;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 mt-4">
                <button type="submit" name="suasanpham" class="btn-custom btn-custom-primary" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);">
                    <i class="fas fa-save me-2"></i>Lưu thay đổi
                </button>
                <a href="?action=quanlymonan&query=them" class="btn-custom btn-custom-secondary text-decoration-none d-inline-flex align-items-center">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </form>
    </div>
</div>
<?php
}
?>