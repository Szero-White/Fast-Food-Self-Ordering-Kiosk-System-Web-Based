<?php
    $sql_lietke_danhmucsp = "SELECT * FROM tbl_danhmuc ORDER BY thutu ASC";
    $query_lietke_danhmucsp = mysqli_query($mysqli,$sql_lietke_danhmucsp);
?>

<!-- Page Header -->
<div class="content-card" style="background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%); border: 1px solid rgba(102,126,234,0.2);">
    <div class="card-body-custom">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-list-alt" style="color: white; font-size: 24px;"></i>
                </div>
                <div>
                    <h4 style="margin: 0; font-weight: 700; color: #333;">Danh mục thực đơn</h4>
                    <p style="margin: 0; color: #888; font-size: 14px;">Quản lý các danh mục món ăn</p>
                </div>
            </div>
            <a href="?action=quanlydanhmucsp&query=them" class="btn-custom btn-custom-primary text-decoration-none d-inline-flex align-items-center">
                <i class="fas fa-plus me-2"></i>Thêm danh mục
            </a>
        </div>
    </div>
</div>

<!-- Categories Grid -->
<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-th-large me-2" style="color: #667eea;"></i>Danh sách danh mục</h5>
        <div class="input-group" style="max-width: 250px;">
            <span class="input-group-text" style="background: white; border-right: none;"><i class="fas fa-search" style="color: #888;"></i></span>
            <input type="text" class="form-control" placeholder="Tìm danh mục..." style="border-left: none;">
        </div>
    </div>
    <div class="card-body-custom">
        <div class="row g-3">
            <?php
            $i = 0;
            while($row = mysqli_fetch_array($query_lietke_danhmucsp)){
                $i++;
                $colors = array(
                    'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                    'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                    'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
                    'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                    'linear-gradient(135deg, #30cfd0 0%, #330867 100%)'
                );
                $color = $colors[($i - 1) % count($colors)];
            ?>
            <div class="col-lg-4 col-md-6">
                <div style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: all 0.3s ease; position: relative; overflow: hidden;" onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
                    <div style="position: absolute; top: 0; right: 0; width: 80px; height: 80px; background: <?php echo $color ?>; opacity: 0.1; border-radius: 0 0 0 100%;"></div>
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div style="width: 50px; height: 50px; background: <?php echo $color ?>; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: white; font-weight: 700;">
                            <?php echo substr($row['tendanhmuc'], 0, 1) ?>
                        </div>
                        <span style="background: #f8f9fa; color: #667eea; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                            #<?php echo $row['thutu'] ?>
                        </span>
                    </div>
                    <h5 style="font-weight: 700; color: #333; margin-bottom: 8px; font-size: 18px;"><?php echo $row['tendanhmuc'] ?></h5>
                    <p style="color: #888; font-size: 13px; margin-bottom: 15px;">ID: <?php echo $row['id_danhmuc'] ?> | Thứ tự: <?php echo $row['thutu'] ?></p>
                    <div class="d-flex gap-2">
                        <a href="?action=quanlydanhmucsp&query=sua&iddanhmuc=<?php echo $row['id_danhmuc'] ?>" class="btn-action edit" title="Sửa" style="flex: 1; justify-content: center;">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="modules/quanlydanhmuc/xuly.php?iddanhmuc=<?php echo $row['id_danhmuc'] ?>" class="btn-action delete" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')" style="flex: 1; justify-content: center;">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>
