<?php
$id_baiviet = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_baiviet <= 0) {
    echo '<div style="text-align:center; padding:50px; color:white;"><h2>📰 Bài viết không tồn tại</h2></div>';
    return;
}

$sql_bv = "SELECT * FROM tbl_baiviet, tbl_danhmucbaiviet 
           WHERE tbl_baiviet.id_danhmuc = tbl_danhmucbaiviet.id_baiviet 
           AND tbl_baiviet.id_bv = '$id_baiviet' 
           LIMIT 1";
$query_bv = mysqli_query($mysqli, $sql_bv);
$row_bv = mysqli_fetch_array($query_bv);

if (!$row_bv) {
    echo '<div style="text-align:center; padding:50px; color:white;"><h2>📰 Không tìm thấy bài viết</h2></div>';
    return;
}
?>

<style>
    .news-detail {
        max-width: 700px;
        margin: 0 auto;
        padding: 15px;
    }
    
    /* Header */
    .news-detail-header {
        text-align: center;
        padding: 20px 0;
        border-bottom: 1px solid #e0e0e0;
        margin-bottom: 25px;
    }
    
    .news-detail-category {
        color: #ff6b6b;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
    }
    
    .news-detail-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        line-height: 1.4;
        margin: 0 0 12px 0;
    }
    
    .news-detail-date {
        color: #ccc;
        font-size: 0.9rem;
    }
    
    /* Image */
    .news-detail-image {
        text-align: center;
        margin-bottom: 25px;
    }
    
    .news-detail-image img {
        width: 100%;
        max-width: 600px;
        max-height: 350px;
        object-fit: cover;
        border-radius: 8px;
    }
    
    /* Content */
    .news-detail-content {
        color: #eee;
        line-height: 1.8;
        font-size: 1rem;
    }
    
    .news-detail-content p {
        margin-bottom: 16px;
    }
    
    /* Summary highlight */
    .news-detail-summary {
        background: #2a2a2a;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        font-style: italic;
        color: #ddd;
        border-left: 3px solid #ff6b6b;
    }
    
    /* Navigation */
    .news-detail-nav {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
    }
    
    .news-detail-btn {
        padding: 10px 20px;
        text-decoration: none;
        color: #fff;
        font-size: 0.95rem;
        border: 1px solid #555;
        border-radius: 4px;
        transition: all 0.2s;
        background: #333;
    }
    
    .news-detail-btn:hover {
        background: #444;
        border-color: #666;
    }
</style>

<div class="news-detail">
    <!-- Header -->
    <div class="news-detail-header">
        <div class="news-detail-category"><?php echo $row_bv['tendanhmucbv']; ?></div>
        <h1 class="news-detail-title"><?php echo $row_bv['tenbaiviet']; ?></h1>
        <div class="news-detail-date">
            📅 <?php echo date('d/m/Y', strtotime('now')); ?> • Tin tức khuyến mãi
        </div>
    </div>
    
    <!-- Image -->
    <div class="news-detail-image">
        <img src="uploads/<?php echo $row_bv['hinhanh']; ?>" 
             alt="<?php echo $row_bv['tenbaiviet']; ?>"
             onerror="this.src='uploads/news-placeholder.jpg'">
    </div>
    
    <!-- Summary -->
    <div class="news-detail-summary">
        <?php echo nl2br($row_bv['tomtat']); ?>
    </div>
    
    <!-- Content -->
    <div class="news-detail-content">
        <?php echo nl2br($row_bv['noidung']); ?>
    </div>
    
    <!-- Navigation -->
    <div class="news-detail-nav">
        <a href="index.php?quanly=danhmucbaiviet&id=<?php echo $row_bv['id_danhmuc']; ?>" class="news-detail-btn">
            ← Quay lại
        </a>
        <a href="index.php" class="news-detail-btn">
            🏠 Trang chủ
        </a>
    </div>
</div>

