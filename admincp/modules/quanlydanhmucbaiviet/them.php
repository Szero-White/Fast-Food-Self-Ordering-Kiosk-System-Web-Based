<div class="content-card crud-hero info">
    <div class="card-body-custom crud-hero-body">
        <div class="crud-title-group">
            <div class="crud-icon info">
                <i class="fas fa-folder"></i>
            </div>
            <div>
                <h4 class="crud-title">Thêm danh mục bài viết</h4>
                <p class="crud-subtitle">Tạo danh mục cho bài viết và khuyến mãi</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="content-card">
            <div class="card-header-custom">
                <h5><i class="fas fa-folder-plus me-2 crud-card-title-icon info"></i>Thông tin danh mục</h5>
            </div>
            <div class="card-body-custom">
                <form method="POST" action="modules/quanlydanhmucbaiviet/xuly.php">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Tên danh mục <span class="crud-required">*</span></label>
                        <input type="text" name="tendanhmucbaiviet" class="form-control-custom" placeholder="VD: Tin tức, Khuyến mãi..." required>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Thứ tự hiển thị</label>
                        <input type="number" name="thutu" class="form-control-custom" placeholder="VD: 1, 2, 3...">
                        <small class="crud-muted">Số nhỏ hơn sẽ hiển thị trước</small>
                    </div>

                    <div class="d-flex gap-3 mt-4 flex-wrap">
                        <button type="submit" name="themdanhmucbaiviet" class="btn-custom btn-custom-success">
                            <i class="fas fa-plus"></i>
                            <span>Thêm danh mục</span>
                        </button>
                        <a href="?action=quanlydanhmucbaiviet&query=them" class="btn-custom btn-custom-secondary text-decoration-none">
                            <i class="fas fa-redo"></i>
                            <span>Nhập lại</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
