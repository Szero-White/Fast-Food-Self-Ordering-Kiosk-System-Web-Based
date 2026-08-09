<div class="content-card crud-hero">
    <div class="card-body-custom crud-hero-body">
        <div class="crud-title-group">
            <div class="crud-icon">
                <i class="fas fa-list"></i>
            </div>
            <div>
                <h4 class="crud-title">Thêm danh mục</h4>
                <p class="crud-subtitle">Thêm danh mục mới cho thực đơn</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="content-card">
            <div class="card-header-custom">
                <h5><i class="fas fa-folder-plus me-2 crud-card-title-icon"></i>Thông tin danh mục</h5>
            </div>
            <div class="card-body-custom">
                <form method="POST" action="modules/quanlydanhmuc/xuly.php">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Tên danh mục <span class="crud-required">*</span></label>
                        <input type="text" name="tendanhmuc" class="form-control-custom" placeholder="VD: Món chính, Đồ uống..." required>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Thứ tự hiển thị</label>
                        <input type="number" name="thutu" class="form-control-custom" placeholder="VD: 1, 2, 3...">
                        <small class="crud-muted">Số nhỏ hơn sẽ hiển thị trước</small>
                    </div>

                    <div class="d-flex gap-3 mt-4 flex-wrap">
                        <button type="submit" name="themdanhmuc" class="btn-custom btn-custom-primary">
                            <i class="fas fa-plus"></i>
                            <span>Thêm danh mục</span>
                        </button>
                        <a href="?action=quanlydanhmucsp&query=them" class="btn-custom btn-custom-secondary text-decoration-none">
                            <i class="fas fa-redo"></i>
                            <span>Nhập lại</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
