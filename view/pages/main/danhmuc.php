<?php
$id = (int)$_GET['id'];
$sql_pro = "SELECT * FROM tbl_sanpham, tbl_danhmuc WHERE tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc AND tbl_sanpham.id_danhmuc = $id ORDER BY id_sanpham DESC";
$query_pro = mysqli_query($mysqli, $sql_pro);

$sql_cate = "SELECT * FROM tbl_danhmuc WHERE tbl_danhmuc.id_danhmuc = $id LIMIT 1";
$query_cate = mysqli_query($mysqli, $sql_cate);
$row_title = mysqli_fetch_array($query_cate);
?>

<style>
    .category-header {
        text-align: center;
        margin-bottom: 30px;
        padding: 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        color: white;
    }
    .category-title {
        font-size: 2rem;
        font-weight: bold;
        margin: 0;
    }
    
    /* Product Grid */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }
    .product-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: all 0.3s;
        position: relative;
    }
    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    }
    .product-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #e74c3c;
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: bold;
        z-index: 2;
    }
    .product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: transform 0.3s;
    }
    .product-card:hover .product-image {
        transform: scale(1.05);
    }
    .product-info {
        padding: 20px;
    }
    .product-category {
        color: #667eea;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }
    .product-name {
        font-size: 1.2rem;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 10px;
    }
    .product-desc {
        color: #7f8c8d;
        font-size: 0.9rem;
        margin-bottom: 15px;
        line-height: 1.5;
    }
    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .product-price {
        font-size: 1.3rem;
        font-weight: bold;
        color: #e74c3c;
    }
    .product-price .old {
        font-size: 0.9rem;
        color: #95a5a6;
        text-decoration: line-through;
        margin-right: 8px;
    }
    .btn-view {
        padding: 8px 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s;
    }
    .btn-view:hover {
        background: #764ba2;
        transform: translateY(-2px);
    }
</style>

<div class="category-header">
    <h1 class="category-title">🍽️ <?php echo $row_title['tendanhmuc'] ?></h1>
</div>

<!-- Products Grid -->
<div class="product-grid">
    <?php
    $counter = 0;
    while ($row_pro = mysqli_fetch_array($query_pro)) {
        $counter++;
    ?>
        <div class="product-card">
            <img src="uploads/<?php echo $row_pro['hinhanh'] ?>" alt="<?php echo $row_pro['tensanpham'] ?>" class="product-image">
            <div class="product-info">
                <div class="product-category"><?php echo $row_pro['tendanhmuc'] ?></div>
                <h3 class="product-name"><?php echo $row_pro['tensanpham'] ?></h3>
                <p class="product-desc"><?php echo substr($row_pro['tomtat'], 0, 60) . '...'; ?></p>
                <div class="product-footer">
                    <div class="product-price">
                        <?php echo number_format($row_pro['giasp'], 0, ',', '.'); ?>đ
                    </div>
                    <a href="index.php?quanly=sanpham&id=<?php echo $row_pro['id_sanpham'] ?>" class="btn-view">Xem chi tiết</a>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</div>

<?php if($counter == 0) { ?>
    <div style="text-align: center; padding: 60px; color: white;">
        <div style="font-size: 4rem; margin-bottom: 20px;">📭</div>
        <h3>Chưa có sản phẩm nào trong danh mục này</h3>
    </div>
<?php } ?>

