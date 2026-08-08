<?php
$sql_sua_bv = "SELECT * FROM tbl_baiviet WHERE id_bv = '$_GET[idbaiviet]' limit 1";
$query_sua_bv = mysqli_query($mysqli, $sql_sua_bv);
?>

<!-- Page Header -->
<div class="content-card" style="background: linear-gradient(135deg, rgba(17,153,142,0.1) 0%, rgba(56,239,125,0.1) 100%); border: 1px solid rgba(17,153,142,0.2);">
    <div class="card-body-custom">
        <div class="d-flex align-items-center gap-3">
            <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-edit" style="color: white; font-size: 24px;"></i>
            </div>
            <div>
                <h4 style="margin: 0; font-weight: 700; color: #333;">Sửa bài viết</h4>
                <p style="margin: 0; color: #888; font-size: 14px;">Cập nhật nội dung bài viết</p>
            </div>
        </div>
    </div>
</div>

<?php
while ($row = mysqli_fetch_array($query_sua_bv)) {
?>
<!-- Edit Article Form -->
<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-newspaper me-2" style="color: #11998e;"></i>Sửa: <?php echo $row['tenbaiviet'] ?></h5>
    </div>
    <div class="card-body-custom">
        <form method="POST" action="modules/quanlybaiviet/xuly.php?idbaiviet=<?php echo $row['id_bv'] ?>" enctype="multipart/form-data">
            <div class="form-group-custom">
                <label class="form-label-custom">Tiêu đề bài viết <span style="color: #e74c3c;">*</span></label>
                <input type="text" name="tenbaiviet" class="form-control-custom" value="<?php echo $row['tenbaiviet'] ?>" required>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Danh mục bài viết <span style="color: #e74c3c;">*</span></label>
                <select name="danhmuc" class="form-control-custom" required>
                    <?php
                    $sql_danhmucbv = "SELECT * FROM tbl_danhmucbaiviet ORDER BY id_baiviet DESC";
                    $query_danhmucbv = mysqli_query($mysqli, $sql_danhmucbv);
                    while ($row_danhmucbv = mysqli_fetch_array($query_danhmucbv)) {
                        $selected = ($row_danhmucbv['id_baiviet'] == $row['id_danhmuc']) ? 'selected' : '';
                    ?>
                    <option value="<?php echo $row_danhmucbv['id_baiviet'] ?>" <?php echo $selected ?>><?php echo $row_danhmucbv['tendanhmucbv'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Tóm tắt</label>
                <textarea rows="4" name="tomtat" class="form-control-custom" data-editor><?php echo $row['tomtat'] ?></textarea>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Nội dung chi tiết</label>
                <textarea rows="10" name="noidung" class="form-control-custom" data-editor><?php echo $row['noidung'] ?></textarea>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Hình ảnh đại diện</label>
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
                <button type="submit" name="suabaiviet" class="btn-custom btn-custom-primary" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <i class="fas fa-save me-2"></i>Lưu thay đổi
                </button>
                <a href="?action=quanlybaiviet&query=them" class="btn-custom btn-custom-secondary text-decoration-none d-inline-flex align-items-center">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </form>
    </div>
</div>
<?php
}
?>