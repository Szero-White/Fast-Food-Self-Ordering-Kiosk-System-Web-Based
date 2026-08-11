<div class="content-card crud-hero food">
    <div class="card-body-custom crud-hero-body">
        <div class="crud-title-group">
            <div class="crud-icon food">
                <i class="fas fa-plus"></i>
            </div>
            <div>
                <h4 class="crud-title">Thêm món ăn mới</h4>
                <p class="crud-subtitle">Tạo món mới cho thực đơn nhà hàng</p>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-utensils me-2 crud-card-title-icon food"></i>Thông tin món ăn</h5>
    </div>
    <div class="card-body-custom">
        <form method="POST" action="modules/quanlysp/xuly.php" enctype="multipart/form-data">
            <?php echo admin_csrf_field(); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Tên món ăn <span class="crud-required">*</span></label>
                        <input type="text" name="tensanpham" class="form-control-custom" placeholder="Nhập tên món ăn" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Mã món <span class="crud-required">*</span></label>
                        <input type="text" name="masp" class="form-control-custom" placeholder="VD: MON001" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Giá (VNĐ) <span class="crud-required">*</span></label>
                        <input type="number" name="giasp" class="form-control-custom" placeholder="VD: 50000" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Số lượng <span class="crud-required">*</span></label>
                        <input type="number" name="soluong" class="form-control-custom" placeholder="VD: 100" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Thứ tự hiển thị</label>
                        <input type="number" name="thutu" class="form-control-custom" placeholder="VD: 1">
                    </div>
                </div>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Danh mục <span class="crud-required">*</span></label>
                <select name="danhmuc" class="form-control-custom" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php
                    $sql_danhmuc = "SELECT * FROM tbl_danhmuc ORDER BY id_danhmuc DESC";
                    $query_danhmuc = mysqli_query($mysqli, $sql_danhmuc);
                    while ($row_danhmuc = mysqli_fetch_array($query_danhmuc)) {
                    ?>
                    <option value="<?php echo (int) $row_danhmuc['id_danhmuc']; ?>">
                        <?php echo htmlspecialchars($row_danhmuc['tendanhmuc'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Tóm tắt</label>
                <textarea rows="4" name="tomtat" class="form-control-custom" placeholder="Mô tả ngắn về món ăn..."></textarea>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Nội dung chi tiết</label>
                <textarea rows="8" name="noidung" class="form-control-custom" placeholder="Mô tả chi tiết về món ăn..." data-editor></textarea>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Hình ảnh món ăn</label>
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="image-upload" role="button" tabindex="0" data-upload-target="hinhanh">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Bấm để chọn hình ảnh hoặc kéo thả vào đây</p>
                            <small class="crud-upload-help">Hỗ trợ: JPG, PNG, WEBP. Khuyến nghị ảnh ngang, nền sáng.</small>
                        </div>
                        <input type="file" name="hinhanh" id="hinhanh" accept="image/*" class="crud-upload-input" data-preview-target="preview-sp">
                    </div>
                    <div class="col-md-4">
                        <div id="preview-sp" class="crud-preview">
                            <p class="crud-muted">Ảnh đã chọn: <span data-file-name></span></p>
                            <img id="img-preview-sp" src="" alt="Ảnh món ăn xem trước">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 mt-4 flex-wrap">
                <button type="submit" name="themsanpham" class="btn-custom btn-custom-primary">
                    <i class="fas fa-plus"></i>
                    <span>Thêm món ăn</span>
                </button>
                <a href="?action=quanlymonan&query=them" class="btn-custom btn-custom-secondary text-decoration-none">
                    <i class="fas fa-redo"></i>
                    <span>Nhập lại</span>
                </a>
                <a href="?action=quanlymonan&query=them" class="btn-custom btn-custom-secondary text-decoration-none">
                    <i class="fas fa-arrow-left"></i>
                    <span>Quay lại</span>
                </a>
            </div>
        </form>
    </div>
</div>
