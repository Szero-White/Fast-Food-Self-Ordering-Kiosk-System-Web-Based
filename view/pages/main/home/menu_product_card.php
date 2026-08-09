<?php
function menu_category_icon(string $categoryName): string
{
    $name = mb_strtolower($categoryName, 'UTF-8');

    if (strpos($name, 'nước') !== false || strpos($name, 'uong') !== false) {
        return 'fas fa-mug-saucer';
    }

    if (strpos($name, 'combo') !== false || strpos($name, 'phần') !== false) {
        return 'fas fa-box-open';
    }

    if (strpos($name, 'gà') !== false || strpos($name, 'ga') !== false) {
        return 'fas fa-drumstick-bite';
    }

    if (strpos($name, 'cơm') !== false || strpos($name, 'com') !== false) {
        return 'fas fa-bowl-rice';
    }

    if (strpos($name, 'pizza') !== false) {
        return 'fas fa-pizza-slice';
    }

    return 'fas fa-utensils';
}

function render_menu_product_card(array $row, int $index = 0): void
{
    $badge = $index < 2 ? 'hot' : ($index < 4 ? 'new' : '');
    $badgeText = $index < 2 ? 'Nổi bật' : ($index < 4 ? 'Mới' : '');
    ?>
    <article class="product-card">
        <?php if ($badge !== '') { ?>
            <span class="product-badge <?php echo $badge; ?>"><?php echo $badgeText; ?></span>
        <?php } ?>
        <a class="product-image-link" href="index.php?quanly=sanpham&id=<?php echo (int) $row['id_sanpham']; ?>" aria-label="Xem chi tiết <?php echo htmlspecialchars($row['tensanpham'], ENT_QUOTES, 'UTF-8'); ?>">
            <img src="<?php echo htmlspecialchars(upload_url($row['hinhanh']), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($row['tensanpham'], ENT_QUOTES, 'UTF-8'); ?>" class="product-image">
        </a>
        <div class="product-info">
            <div class="product-category"><?php echo htmlspecialchars($row['tendanhmuc'], ENT_QUOTES, 'UTF-8'); ?></div>
            <h3 class="product-name"><?php echo htmlspecialchars($row['tensanpham'], ENT_QUOTES, 'UTF-8'); ?></h3>
            <p class="product-desc"><?php echo htmlspecialchars(mb_substr($row['tomtat'], 0, 70, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>...</p>
            <div class="product-footer">
                <div class="product-price">
                    <?php echo number_format((int) $row['giasp'], 0, ',', '.'); ?>đ
                </div>
                <form method="POST" action="" class="product-order-form">
                    <input type="hidden" name="id_sanpham" value="<?php echo (int) $row['id_sanpham']; ?>">
                    <input type="hidden" name="ten_sanpham" value="<?php echo htmlspecialchars($row['tensanpham'], ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="giasp" value="<?php echo (int) $row['giasp']; ?>">
                    <input type="hidden" name="hinhanh" value="<?php echo htmlspecialchars($row['hinhanh'], ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="number" name="soluong" value="1" min="1" max="10" class="product-quantity" aria-label="Số lượng">
                    <a class="btn-detail" href="index.php?quanly=sanpham&id=<?php echo (int) $row['id_sanpham']; ?>" title="Xem chi tiết" aria-label="Xem chi tiết">
                        <i class="fas fa-eye"></i>
                    </a>
                    <button type="submit" name="them_giohang" class="btn-add-cart" title="Thêm vào giỏ hàng" aria-label="Thêm vào giỏ hàng">
                        <i class="fas fa-cart-plus"></i>
                    </button>
                </form>
            </div>
        </div>
    </article>
    <?php
}
