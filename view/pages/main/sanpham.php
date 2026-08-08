<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .wrapper_chitiet {
            display: flex;
            align-items: flex-start;
            color: white;
            padding: 40px;
            gap: 50px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .hinhanh_sanpham {
            flex: 0 0 40%;
            text-align: center;
        }

        .hinhanh_sanpham img {
            width: 100%;
            max-width: 400px;
            height: auto;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .chitiet_sanpham {
            flex: 1;
        }
        .chitiet_sanpham h3 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #f093fb;
        }
        .chitiet_sanpham p {
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
    </style>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <?php
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        echo "<div style='text-align:center; padding:50px; color:red; font-size:1.5rem;'>Sản phẩm không tồn tại!</div>";
        exit();
    }
    
    $id = intval($_GET['id']);
    $sql_chitiet = "SELECT * FROM tbl_sanpham,tbl_danhmuc WHERE tbl_sanpham.id_danhmuc=tbl_danhmuc.id_danhmuc AND tbl_sanpham.id_sanpham = $id LIMIT 1";
    $query_chitiet = mysqli_query($mysqli, $sql_chitiet);
    
    if (mysqli_num_rows($query_chitiet) == 0) {
        echo "<div style='text-align:center; padding:50px; color:red; font-size:1.5rem;'>Sản phẩm không tồn tại!</div>";
        exit();
    }
    
    while ($row_chitiet = mysqli_fetch_array($query_chitiet)) {

    ?>
        <div class="wrapper_chitiet">
            <div class="hinhanh_sanpham">
                <img src="uploads/<?php echo $row_chitiet['hinhanh'] ?>" alt="Hình ảnh sản phẩm">
            </div>
            <div class="chitiet_sanpham">
                <h3>Tên sản phẩm : <?php echo $row_chitiet['tensanpham'] ?></h3>
                <p>Mã sản phẩm : <?php echo $row_chitiet['masp'] ?></p>
                <p>Giá sản phẩm : <?php echo number_format($row_chitiet['giasp'], 0, ',', '.'); ?>d</p>
                <p>Số lượng sản phẩm : <?php echo $row_chitiet['soluong'] ?></p>
                <p>Danh mục sản phẩm : <?php echo $row_chitiet['tendanhmuc'] ?></p>
                
                <!-- Thong tin bo sung -->
                <div style="margin-top: 20px; padding: 15px; background: rgba(255,255,255,0.1); border-radius: 10px;">
                    <h4 style="color: #f093fb; margin-bottom: 10px;">📝 Mô tả:</h4>
                    <p style="color: white; line-height: 1.6;"><?php echo nl2br($row_chitiet['tomtat']); ?></p>
                </div>
                
                <div style="margin-top: 25px; display: flex; gap: 15px; align-items: center;">
                    <a href="index.php?quanly=danhmucsanpham&id=<?php echo $row_chitiet['id_danhmuc']; ?>" class="btn btn-light" style="padding: 12px 25px; border-radius: 25px; text-decoration: none;">
                        ← Xem thêm <?php echo $row_chitiet['tendanhmuc']; ?>
                    </a>
                    <a href="index.php" class="btn btn-outline-light" style="padding: 12px 25px; border-radius: 25px; text-decoration: none;">
                        🏠 Về Trang Chủ
                    </a>
                </div>
                
                <!-- Thong bao -->
                <div style="margin-top: 20px; padding: 15px; background: linear-gradient(135deg, #f39c12 0%, #e74c3c 100%); border-radius: 10px; color: white;">
                    <strong>💡 Mẹo:</strong> Ghé quầy để đặt món và nhận ưu đãi đặc biệt!
                </div>
            </div>
        </div>
    <?php
    }
    ?>


</body>

</html>
