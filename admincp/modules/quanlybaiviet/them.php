<!-- Page Header -->
<div class="content-card" style="background: linear-gradient(135deg, rgba(17,153,142,0.1) 0%, rgba(56,239,125,0.1) 100%); border: 1px solid rgba(17,153,142,0.2);">
    <div class="card-body-custom">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-newspaper" style="color: white; font-size: 24px;"></i>
                </div>
                <div>
                    <h4 style="margin: 0; font-weight: 700; color: #333;">Thêm bài viết mới</h4>
                    <p style="margin: 0; color: #888; font-size: 14px;">Tạo bài viết cho website</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Article Form -->
<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-pen me-2" style="color: #11998e;"></i>Nội dung bài viết</h5>
    </div>
    <div class="card-body-custom">
        <form method="POST" action="modules/quanlybaiviet/xuly.php" enctype="multipart/form-data">
            <div class="form-group-custom">
                <label class="form-label-custom">Tiêu đề bài viết <span style="color: #e74c3c;">*</span></label>
                <input type="text" name="tenbaiviet" class="form-control-custom" placeholder="Nhập tiêu đề bài viết..." required>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Danh mục bài viết <span style="color: #e74c3c;">*</span></label>
                <select name="danhmuc" class="form-control-custom" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php
                    $sql_danhmuc = "SELECT * FROM tbl_danhmucbaiviet ORDER BY id_baiviet DESC";
                    $query_danhmuc = mysqli_query($mysqli,$sql_danhmuc);
                    while ($row_danhmuc = mysqli_fetch_array($query_danhmuc)) {
                    ?>
                    <option value="<?php echo $row_danhmuc['id_baiviet'] ?>"><?php echo $row_danhmuc['tendanhmucbv'] ?></option>
                    <?php
                    }
                    ?>
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
                        <div class="image-upload" onclick="document.getElementById('hinhanh').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click để chọn hình ảnh hoặc kéo thả vào đây</p>
                            <small style="color: #aaa;">Hỗ trợ: JPG, PNG (Tối đa 2MB)</small>
                            <input type="file" name="hinhanh" id="hinhanh" accept="image/*" style="display: none;" onchange="previewImageBV(this)">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div id="preview-bv" style="display: none;">
                            <p style="color: #888; font-size: 13px; margin-bottom: 10px;">Ảnh đã chọn:</p>
                            <img id="img-preview-bv" src="" style="width: 100%; max-width: 200px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        </div>
                    </div>
                </div>
            </div>
            <script>
            function previewImageBV(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('img-preview-bv').src = e.target.result;
                        document.getElementById('preview-bv').style.display = 'block';
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            </script>

            <div class="d-flex gap-3 mt-4">
                <button type="submit" name="thembaiviet" class="btn-custom btn-custom-primary" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <i class="fas fa-plus me-2"></i>Thêm bài viết
                </button>
                <a href="?action=quanlybaiviet&query=them" class="btn-custom btn-custom-secondary text-decoration-none d-inline-flex align-items-center">
                    <i class="fas fa-redo me-2"></i>Nhập lại
                </a>
                <a href="?action=quanlybaiviet&query=them" class="btn-custom btn-custom-secondary text-decoration-none d-inline-flex align-items-center">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </form>
    </div>
</div>