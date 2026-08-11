<?php
$currentAction = $_GET['action'] ?? 'dashboard';

$menuGroups = [
    'Tổng quan' => [
        ['href' => 'index.php', 'icon' => 'fas fa-home', 'label' => 'Trang chủ', 'actions' => ['dashboard']],
        ['href' => 'index.php?action=thongke&query=xem', 'icon' => 'fas fa-chart-bar', 'label' => 'Thống kê', 'actions' => ['thongke']],
    ],
    'Nội dung website' => [
        ['href' => 'index.php?action=quanlyweb&query=capnhat', 'icon' => 'fas fa-info-circle', 'label' => 'Giới thiệu', 'actions' => ['quanlyweb']],
        ['href' => 'index.php?action=quanlyhinhanh&query=capnhat', 'icon' => 'fas fa-images', 'label' => 'Hình ảnh hệ thống', 'actions' => ['quanlyhinhanh']],
        ['href' => 'index.php?action=quanlybanner&query=them', 'icon' => 'fas fa-panorama', 'label' => 'Banner trang chủ', 'actions' => ['quanlybanner']],
    ],
    'Thực đơn' => [
        ['href' => 'index.php?action=quanlydanhmucsp&query=them', 'icon' => 'fas fa-list', 'label' => 'Danh mục món ăn', 'actions' => ['quanlydanhmucsp']],
        ['href' => 'index.php?action=quanlymonan&query=them', 'icon' => 'fas fa-utensils', 'label' => 'Món ăn', 'actions' => ['quanlymonan']],
    ],
    'Bài viết' => [
        ['href' => 'index.php?action=quanlydanhmucbaiviet&query=them', 'icon' => 'fas fa-folder', 'label' => 'Danh mục bài viết', 'actions' => ['quanlydanhmucbaiviet']],
        ['href' => 'index.php?action=quanlybaiviet&query=them', 'icon' => 'fas fa-newspaper', 'label' => 'Bài viết', 'actions' => ['quanlybaiviet']],
    ],
    'Vận hành' => [
        ['href' => 'index.php?action=quanlyhotro&query=lietke', 'icon' => 'fas fa-bell-concierge', 'label' => 'Gọi nhân viên', 'actions' => ['quanlyhotro']],
        ['href' => 'index.php?action=quanlydonhang&query=lietke', 'icon' => 'fas fa-shopping-cart', 'label' => 'Đơn hàng', 'actions' => ['quanlydonhang']],
        ['href' => 'index.php?action=quanlylienhe&query=lietke', 'icon' => 'fas fa-envelope', 'label' => 'Liên hệ', 'actions' => ['quanlylienhe']],
        ['href' => 'index.php?action=quanlychatbot&query=lietke', 'icon' => 'fas fa-robot', 'label' => 'Chatbot', 'actions' => ['quanlychatbot']],
    ],
];
?>

<ul class="nav-menu">
    <?php foreach ($menuGroups as $groupName => $items) { ?>
        <li class="nav-group">
            <span><?php echo htmlspecialchars($groupName, ENT_QUOTES, 'UTF-8'); ?></span>
        </li>

        <?php foreach ($items as $item) {
            $isActive = in_array($currentAction, $item['actions'], true)
                || ($currentAction === 'dashboard' && $item['href'] === 'index.php');
        ?>
            <li class="nav-item">
                <a class="nav-link <?php echo $isActive ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8'); ?>">
                    <i class="<?php echo htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                    <span><?php echo htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                </a>
            </li>
        <?php } ?>
    <?php } ?>
</ul>
