<!-- Page Header -->
<div class="content-card" style="background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%); border: 1px solid rgba(102,126,234,0.2);">
    <div class="card-body-custom">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-list" style="color: white; font-size: 24px;"></i>
                </div>
                <div>
                    <h4 style="margin: 0; font-weight: 700; color: #333;">Thêm danh mục</h4>
                    <p style="margin: 0; color: #888; font-size: 14px;">Thêm danh mục mới cho thực đơn</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Form -->
<div class="row">
    <div class="col-lg-6">
        <div class="content-card">
            <div class="card-header-custom">
                <h5><i class="fas fa-folder-plus me-2" style="color: #667eea;"></i>Thông tin danh mục</h5>
            </div>
            <div class="card-body-custom">
                <form method="POST" action="modules/quanlydanhmuc/xuly.php">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Tên danh mục <span style="color: #e74c3c;">*</span></label>
                        <input type="text" name="tendanhmuc" class="form-control-custom" placeholder="VD: Món chính, Đồ uống..." required>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Thứ tự hiển thị</label>
                        <input type="number" name="thutu" class="form-control-custom" placeholder="VD: 1, 2, 3...">
                        <small style="color: #888;">Số nhỏ hơn sẽ hiển thị trước</small>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" name="themdanhmuc" class="btn-custom btn-custom-primary">
                            <i class="fas fa-plus me-2"></i>Thêm danh mục
                        </button>
                        <a href="?action=quanlydanhmucsp&query=them" class="btn-custom btn-custom-secondary text-decoration-none d-inline-flex align-items-center">
                            <i class="fas fa-redo me-2"></i>Nhập lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>