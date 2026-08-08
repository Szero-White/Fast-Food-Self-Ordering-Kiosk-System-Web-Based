<?php
// Get statistics
$sql_sp = "SELECT COUNT(*) as tong FROM tbl_sanpham";
$query_sp = mysqli_query($mysqli, $sql_sp);
$row_sp = mysqli_fetch_assoc($query_sp);

$sql_bv = "SELECT COUNT(*) as tong FROM tbl_baiviet";
$query_bv = mysqli_query($mysqli, $sql_bv);
$row_bv = mysqli_fetch_assoc($query_bv);

$sql_lh = "SELECT COUNT(*) as tong FROM tbl_lienhe WHERE trangthai = 'chua_xem'";
$query_lh = mysqli_query($mysqli, $sql_lh);
$row_lh = mysqli_fetch_assoc($query_lh);

$sql_dh = "SELECT COUNT(*) as tong FROM tbl_donhang WHERE trangthai = 'moi'";
$query_dh = mysqli_query($mysqli, $sql_dh);
$row_dh = mysqli_fetch_assoc($query_dh);
?>

<!-- Welcome Banner -->
<div class="content-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <div class="card-body-custom">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4 style="font-weight: 700; margin-bottom: 8px;">
                    <i class="fas fa-hand-sparkles me-2"></i>Chào mừng trở lại, Admin!
                </h4>
                <p style="margin: 0; opacity: 0.9;">Chúc bạn một ngày làm việc hiệu quả. Hãy quản lý nhà hàng của bạn một cách chuyên nghiệp.</p>
            </div>
            <div class="text-end">
                <p style="margin: 0; font-size: 14px; opacity: 0.8;">Hôm nay</p>
                <h5 style="margin: 0; font-weight: 600;" id="currentDate"></h5>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon blue">
                <i class="fas fa-utensils"></i>
            </div>
            <div class="stat-trend">
                <i class="fas fa-arrow-up"></i> +12%
            </div>
        </div>
        <div class="stat-body">
            <h3><?php echo $row_sp['tong']; ?></h3>
            <p>Tổng món ăn</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="stat-trend">
                <i class="fas fa-arrow-up"></i> +8%
            </div>
        </div>
        <div class="stat-body">
            <h3><?php echo $row_bv['tong']; ?></h3>
            <p>Bài viết</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon orange">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-trend down">
                <i class="fas fa-arrow-down"></i> 3 mới
            </div>
        </div>
        <div class="stat-body">
            <h3><?php echo $row_dh['tong']; ?></h3>
            <p>Đơn hàng mới</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon cyan">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="stat-trend <?php echo $row_lh['tong'] > 0 ? 'down' : ''; ?>">
                <?php echo $row_lh['tong'] > 0 ? '<i class="fas fa-exclamation"></i> Cần xử lý' : '<i class="fas fa-check"></i> OK'; ?>
            </div>
        </div>
        <div class="stat-body">
            <h3><?php echo $row_lh['tong']; ?></h3>
            <p>Liên hệ chưa xem</p>
        </div>
    </div>
</div>

<!-- Quick Actions & Guide -->
<div class="row">
    <div class="col-lg-7">
        <div class="content-card">
            <div class="card-header-custom">
                <h5><i class="fas fa-bolt me-2" style="color: #f39c12;"></i>Thao tác nhanh</h5>
            </div>
            <div class="card-body-custom">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <a href="index.php?action=quanlymonan&query=them" class="d-flex align-items-center gap-3 p-3 text-decoration-none" style="background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%); border-radius: 12px; border: 1px solid rgba(102,126,234,0.2);">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-plus" style="color: white; font-size: 20px;"></i>
                            </div>
                            <div>
                                <h6 style="margin: 0; font-weight: 600; color: #333;">Thêm món ăn</h6>
                                <small style="color: #888;">Thêm món mới vào thực đơn</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="index.php?action=quanlybaiviet&query=them" class="d-flex align-items-center gap-3 p-3 text-decoration-none" style="background: linear-gradient(135deg, rgba(17,153,142,0.1) 0%, rgba(56,239,125,0.1) 100%); border-radius: 12px; border: 1px solid rgba(17,153,142,0.2);">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-pen" style="color: white; font-size: 20px;"></i>
                            </div>
                            <div>
                                <h6 style="margin: 0; font-weight: 600; color: #333;">Viết bài mới</h6>
                                <small style="color: #888;">Tạo bài viết mới</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="index.php?action=quanlydonhang&query=lietke" class="d-flex align-items-center gap-3 p-3 text-decoration-none" style="background: linear-gradient(135deg, rgba(240,147,251,0.1) 0%, rgba(245,87,108,0.1) 100%); border-radius: 12px; border: 1px solid rgba(240,147,251,0.2);">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clipboard-list" style="color: white; font-size: 20px;"></i>
                            </div>
                            <div>
                                <h6 style="margin: 0; font-weight: 600; color: #333;">Xem đơn hàng</h6>
                                <small style="color: #888;">Kiểm tra đơn hàng mới</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="index.php?action=thongke&query=xem" class="d-flex align-items-center gap-3 p-3 text-decoration-none" style="background: linear-gradient(135deg, rgba(79,172,254,0.1) 0%, rgba(0,242,254,0.1) 100%); border-radius: 12px; border: 1px solid rgba(79,172,254,0.2);">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-chart-pie" style="color: white; font-size: 20px;"></i>
                            </div>
                            <div>
                                <h6 style="margin: 0; font-weight: 600; color: #333;">Xem thống kê</h6>
                                <small style="color: #888;">Báo cáo doanh thu</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="content-card">
            <div class="card-header-custom">
                <h5><i class="fas fa-lightbulb me-2" style="color: #f39c12;"></i>Hướng dẫn sử dụng</h5>
            </div>
            <div class="card-body-custom">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex gap-3">
                        <div style="width: 40px; height: 40px; background: rgba(102,126,234,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-utensils" style="color: #667eea;"></i>
                        </div>
                        <div>
                            <h6 style="margin: 0; font-weight: 600; font-size: 14px;">Quản lý thực đơn</h6>
                            <p style="margin: 0; font-size: 13px; color: #888;">Thêm, sửa, xóa các món ăn và phân loại theo danh mục.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <div style="width: 40px; height: 40px; background: rgba(17,153,142,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-newspaper" style="color: #11998e;"></i>
                        </div>
                        <div>
                            <h6 style="margin: 0; font-weight: 600; font-size: 14px;">Quản lý bài viết</h6>
                            <p style="margin: 0; font-size: 13px; color: #888;">Tạo và chỉnh sửa các bài viết, tin tức về nhà hàng.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <div style="width: 40px; height: 40px; background: rgba(240,147,251,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-shopping-cart" style="color: #f093fb;"></i>
                        </div>
                        <div>
                            <h6 style="margin: 0; font-weight: 600; font-size: 14px;">Quản lý đơn hàng</h6>
                            <p style="margin: 0; font-size: 13px; color: #888;">Xem và cập nhật trạng thái đơn hàng từ khách hàng.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Set current date
const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
document.getElementById('currentDate').textContent = new Date().toLocaleDateString('vi-VN', dateOptions);
</script>