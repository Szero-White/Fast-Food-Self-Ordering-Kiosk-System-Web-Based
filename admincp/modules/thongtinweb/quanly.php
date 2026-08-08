<?php
$sql_gt = "SELECT * FROM tbl_gioithieu WHERE id = 1";
$query_gt = mysqli_query($mysqli, $sql_gt);
$dong = mysqli_fetch_array($query_gt);

// Nếu chưa có dữ liệu, tạo mặc định
if (!$dong) {
    $dong = array('id' => 1, 'noidung' => '', 'hinhanh' => '');
}
?>

<!-- Page Header -->
<div class="content-card" style="background: linear-gradient(135deg, rgba(17,153,142,0.1) 0%, rgba(56,239,125,0.1) 100%); border: 1px solid rgba(17,153,142,0.2);">
    <div class="card-body-custom">
        <div class="d-flex align-items-center gap-3">
            <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-info-circle" style="color: white; font-size: 24px;"></i>
            </div>
            <div>
                <h4 style="margin: 0; font-weight: 700; color: #333;">Quản lý giới thiệu</h4>
                <p style="margin: 0; color: #888; font-size: 14px;">Cập nhật thông tin trang giới thiệu</p>
            </div>
        </div>
    </div>
</div>

<?php // Data already loaded above ?>
<!-- Edit Form -->
<div class="row">
    <div class="col-lg-8">
        <div class="content-card">
            <div class="card-header-custom">
                <h5><i class="fas fa-edit me-2" style="color: #11998e;"></i>Nội dung giới thiệu</h5>
            </div>
            <div class="card-body-custom">
                <form method="POST" action="modules/thongtinweb/xuly.php?id=1" enctype="multipart/form-data">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Thông tin giới thiệu</label>
                        <textarea name="noidung" class="form-control-custom" rows="15" style="resize: vertical; min-height: 300px; font-family: 'Segoe UI', sans-serif; line-height: 1.6;"><?php echo $dong['noidung'] ?></textarea>
                        <small style="color: #888;">Hỗ trợ HTML. Nội dung sẽ hiển thị trên trang giới thiệu cho khách hàng xem.</small>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Hình ảnh banner</label>
                        <div class="image-upload" style="border: 2px dashed #ddd; border-radius: 12px; padding: 30px; text-align: center; transition: all 0.3s ease;">
                            <input type="file" name="hinhanh" id="hinhanh" style="display: none;" accept="image/*">
                            <label for="hinhanh" style="cursor: pointer; display: block;">
                                <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: #11998e; margin-bottom: 15px;"></i>
                                <p style="color: #666; margin-bottom: 10px;">Click để chọn ảnh mới</p>
                                <p style="color: #888; font-size: 13px;">hoặc kéo thả ảnh vào đây</p>
                            </label>
                        </div>
                        <?php if($dong['hinhanh']) { ?>
                        <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 12px;">
                            <p style="color: #888; font-size: 13px; margin-bottom: 10px;">Ảnh hiện tại:</p>
                            <img src="../uploads/<?php echo $dong['hinhanh'] ?>" style="max-width: 300px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        </div>
                        <?php } ?>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" name="themlienhe" class="btn-custom btn-custom-primary" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                            <i class="fas fa-save me-2"></i>Lưu thay đổi
                        </button>
                        <a href="index.php" class="btn-custom btn-custom-secondary text-decoration-none d-inline-flex align-items-center">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="content-card" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
            <div class="card-body-custom">
                <h6 style="font-weight: 700; color: #333; margin-bottom: 15px;"><i class="fas fa-lightbulb me-2" style="color: #ffc107;"></i>Gợi ý</h6>
                <ul style="color: #666; font-size: 14px; line-height: 1.8; padding-left: 20px;">
                    <li>Giới thiệu về nhà hàng</li>
                    <li>Lịch sử hình thành</li>
                    <li>Thông tin liên hệ</li>
                    <li>Giờ mở cửa</li>
                    <li>Địa chỉ chi tiết</li>
                </ul>
                <div style="margin-top: 20px; padding: 15px; background: white; border-radius: 12px; border-left: 4px solid #11998e;">
                    <p style="color: #11998e; font-size: 13px; margin: 0;"><i class="fas fa-info-circle me-2"></i>Nội dung sẽ hiển thị trên trang /index.php?quanly=gioithieu cho khách hàng xem.</p>
                </div>
            </div>
        </div>
    </div>
</div>
