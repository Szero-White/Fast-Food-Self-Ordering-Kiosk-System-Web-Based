<div class="content-card crud-hero info">
    <div class="card-body-custom crud-hero-body">
        <div class="crud-title-group">
            <div class="crud-icon info">
                <i class="fas fa-newspaper"></i>
            </div>
            <div>
                <h4 class="crud-title">Thêm bài viết mới</h4>
                <p class="crud-subtitle">Tạo bài viết hoặc chương trình khuyến mãi cho website</p>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-pen me-2 crud-card-title-icon info"></i>Nội dung bài viết</h5>
    </div>
    <div class="card-body-custom">
        <form method="POST" action="modules/quanlybaiviet/xuly.php" enctype="multipart/form-data">
            <?php echo admin_csrf_field(); ?>
            <div class="form-group-custom">
                <label class="form-label-custom">Tiêu đề bài viết <span class="crud-required">*</span></label>
                <input type="text" name="tenbaiviet" class="form-control-custom" placeholder="Nhập tiêu đề bài viết..." required>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Danh mục bài viết <span class="crud-required">*</span></label>
                <select name="danhmuc" class="form-control-custom" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php
                    $sql_danhmuc = "SELECT * FROM tbl_danhmucbaiviet ORDER BY id_baiviet DESC";
                    $query_danhmuc = mysqli_query($mysqli, $sql_danhmuc);
                    while ($row_danhmuc = mysqli_fetch_array($query_danhmuc)) {
                    ?>
                    <option value="<?php echo (int)$row_danhmuc['id_baiviet']; ?>">
                        <?php echo htmlspecialchars($row_danhmuc['tendanhmucbv'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Tóm tắt</label>
                <textarea rows="4" name="tomtat" class="form-control-custom" placeholder="Tóm tắt nội dung bài viết..." data-editor></textarea>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Nội dung chi tiết</label>
                <textarea rows="10" name="noidung" class="form-control-custom" placeholder="Nội dung chi tiết bài viết..." data-editor></textarea>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Hình ảnh đại diện</label>
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="image-upload" role="button" tabindex="0" data-upload-target="hinhanh">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Bấm để chọn hình ảnh hoặc kéo thả vào đây</p>
                            <small class="crud-upload-help">Hỗ trợ: JPG, PNG, WEBP. Khuyến nghị ảnh ngang.</small>
                        </div>
                        <input type="file" name="hinhanh" id="hinhanh" accept="image/*" class="crud-upload-input" data-preview-target="preview-bv">
                    </div>
                    <div class="col-md-4">
                        <div id="preview-bv" class="crud-preview">
                            <p class="crud-muted">Ảnh đã chọn: <span data-file-name></span></p>
                            <img src="" alt="Ảnh bài viết xem trước">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 mt-4 flex-wrap">
                <button type="submit" name="thembaiviet" class="btn-custom btn-custom-success">
                    <i class="fas fa-plus"></i>
                    <span>Thêm bài viết</span>
                </button>
                <a href="?action=quanlybaiviet&query=them" class="btn-custom btn-custom-secondary text-decoration-none">
                    <i class="fas fa-redo"></i>
                    <span>Nhập lại</span>
                </a>
                <a href="?action=quanlybaiviet&query=them" class="btn-custom btn-custom-secondary text-decoration-none">
                    <i class="fas fa-arrow-left"></i>
                    <span>Quay lại</span>
                </a>
            </div>
        </form>
    </div>
</div>
