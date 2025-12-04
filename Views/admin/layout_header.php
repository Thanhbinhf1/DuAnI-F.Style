<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - F.Style Store</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>Public/Css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="admin-header">
        <span class="logo">F.Style | ADMIN PANEL</span>
        <div>
            <span style="font-size: 14px;">Xin chào,
                <b><?= htmlspecialchars($_SESSION['user']['fullname'] ?? 'Admin') ?></b></span>
            <a href="<?= BASE_URL ?>">⌂ Trang Khách hàng</a>
            <a href="<?= BASE_URL ?>?ctrl=user&act=logout">Đăng xuất</a>
        </div>
    </div>

    <?php
    // Logic xác định menu active
    $current_act = $_GET['act'] ?? 'dashboard';
    ?>

    <div class="admin-sidebar">
        <ul>
            <li><a href="<?= BASE_URL ?>?ctrl=admin&act=dashboard"
                    class="<?= $current_act == 'dashboard' ? 'active' : '' ?>">📊 Tổng quan</a></li>

            <li><a href="<?= BASE_URL ?>?ctrl=admin&act=productList"
                    class="<?= $current_act == 'productList' || $current_act == 'productForm' ? 'active' : '' ?>">📦 Sản
                    phẩm</a></li>

            <li><a href="<?= BASE_URL ?>?ctrl=admin&act=orderList"
                    class="<?= $current_act == 'orderList' || $current_act == 'orderDetail' ? 'active' : '' ?>">📋 Đơn
                    hàng</a></li>

            <li><a href="<?= BASE_URL ?>?ctrl=admin&act=userList"
                    class="<?= $current_act == 'userList' ? 'active' : '' ?>">👥 Người dùng</a></li>

            <li><a href="<?= BASE_URL ?>?ctrl=admin&act=categoryList"
                    class="<?= $current_act == 'categoryList' || $current_act == 'categoryForm' ? 'active' : '' ?>">🗃️
                    Danh mục</a></li>

            <li><a href="<?= BASE_URL ?>?ctrl=admin&act=statistics"
                    class="<?= $current_act == 'statistics' ? 'active' : '' ?>">📈 Thống kê & Báo cáo</a></li>

        </ul>
    </div>

    <main class="admin-content">