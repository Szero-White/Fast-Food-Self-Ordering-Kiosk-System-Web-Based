<?php
$id_danhmuc = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_danhmuc > 0) {
    // Hiển thị bài viết theo danh mục cụ thể
    $sql_bv = "SELECT * FROM tbl_baiviet WHERE tbl_baiviet.id_danhmuc = '$id_danhmuc' ORDER BY id_bv DESC";
    $query_bv = mysqli_query($mysqli, $sql_bv);
    
    $sql_cate = "SELECT * FROM tbl_danhmucbaiviet WHERE tbl_danhmucbaiviet.id_baiviet = '$id_danhmuc' limit 1";
    $query_cate = mysqli_query($mysqli, $sql_cate);
    $row_title = mysqli_fetch_array($query_cate);
    $tendanhmuc = $row_title ? $row_title['tendanhmucbv'] : 'Tin tức';
} else {
    // Hiển thị tất cả bài viết (khi không có id)
    $sql_bv = "SELECT * FROM tbl_baiviet ORDER BY id_bv DESC";
    $query_bv = mysqli_query($mysqli, $sql_bv);
    $tendanhmuc = 'Tin tức & Khuyến mãi';
}
?>

<style>
    /* Section Header */
    .news-section {
        padding: 20px 0;
    }
    .page-header-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 60px 40px;
        text-align: center;
        margin: 0 auto 40px auto;
        max-width: 84rem;
        color: white;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
    }
    .page-header-box h1 {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 15px;
    }
    .page-header-box p {
        font-size: 1.2rem;
        opacity: 0.9;
    }

    /* News Grid */
    .news-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        margin: 0 auto 40px auto;
        max-width: 84rem;
    }

    /* News Card */
    .news-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        position: relative;
    }
    .news-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .news-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
        z-index: 2;
    }
    .news-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
        display: block;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        transition: transform 0.3s ease;
    }
    .news-card:hover .news-image {
        transform: scale(1.08);
    }
    .news-content {
        padding: 20px;
    }
    .news-title {
        font-size: 1.1rem;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 10px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .news-summary {
        color: #7f8c8d;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .news-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }
    .news-date {
        font-size: 0.85rem;
        color: #95a5a6;
    }
    .btn-read-more {
        padding: 8px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        border-radius: 20px;
        font-size: 0.9rem;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
    }
    .btn-read-more:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px;
        background: white;
        border-radius: 15px;
        grid-column: 1 / -1;
    }
    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 20px;
    }
    .empty-state h3 {
        color: #2c3e50;
        margin-bottom: 10px;
    }
    .empty-state p {
        color: #7f8c8d;
    }
</style>

<div class="news-section">
    <!-- Section Header -->
    <div class="page-header-box">
        <h1>📰 <?php echo $tendanhmuc; ?></h1>
        <p>Cập nhật tin tức và khuyến mãi mới nhất từ FastFood</p>
    </div>

    <!-- News Grid -->
    <div class="news-grid">
        <?php
        if (mysqli_num_rows($query_bv) > 0) {
            $counter = 0;
            while ($row_bv = mysqli_fetch_array($query_bv)) {
                $counter++;
        ?>
            <div class="news-card">
                <?php if($counter <= 2) { ?>
                    <span class="news-badge">🔥 HOT</span>
                <?php } ?>
                <img src="uploads/<?php echo $row_bv['hinhanh'] ?>" 
                     alt="<?php echo $row_bv['tenbaiviet'] ?>" 
                     class="news-image"
                     onerror="this.src='uploads/news-placeholder.jpg'">
                <div class="news-content">
                    <h3 class="news-title"><?php echo $row_bv['tenbaiviet'] ?></h3>
                    <p class="news-summary"><?php echo $row_bv['tomtat']; ?></p>
                    <div class="news-footer">
                        <span class="news-date">📅 <?php echo date('d/m/Y', strtotime('now')); ?></span>
                        <a href="index.php?quanly=baiviet&id=<?php echo $row_bv['id_bv'] ?>" class="btn-read-more">
                            Xem chi tiết →
                        </a>
                    </div>
                </div>
            </div>
        <?php
            }
        } else {
        ?>
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h3>Chưa có bài viết</h3>
                <p>Danh mục này chưa có tin tức nào. Vui lòng quay lại sau!</p>
            </div>
        <?php } ?>
    </div>
</div>

