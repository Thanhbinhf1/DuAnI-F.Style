<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Admin - F.Style Store</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="<?= BASE_URL ?>Public/Css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    /* Fix xung đột nhỏ */
    .admin-sidebar ul {
        padding-left: 0;
    }

    .admin-sidebar li {
        list-style: none;
    }

    a {
        text-decoration: none;
    }

    img {
        max-width: 100%;
        height: auto;
    }
    </style>
</head>

<body>
    <div class="admin-header">
        <span class="logo">F.Style | ADMIN PANEL</span>
        <div>
            <span style="font-size: 14px;">Xin chào,
                <b><?= htmlspecialchars($_SESSION['user']['fullname'] ?? 'Admin') ?></b></span>
            <a href="<?= BASE_URL ?>" target="_blank" class="btn-go-home"><i class="fas fa-home"></i> Xem trang chủ</a>
            <a href="<?= BASE_URL ?>?ctrl=user&act=logout" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Đăng
                xuất</a>
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

            <li><a href="<?= BASE_URL ?>?ctrl=admin&act=categoryList"
                    class="<?= $current_act == 'categoryList' || $current_act == 'categoryForm' ? 'active' : '' ?>">🗃️
                    Danh mục</a></li>

            <li><a href="<?= BASE_URL ?>?ctrl=admin&act=productList"
                    class="<?= $current_act == 'productList' || $current_act == 'productForm' ? 'active' : '' ?>">📦 Sản
                    phẩm</a></li>
            <li><a href="<?= BASE_URL ?>?ctrl=admin&act=commentList"
                    class="<?= $current_act == 'commentList' ? 'active' : '' ?>">💬 Bình luận</a></li>

            <li><a href="<?= BASE_URL ?>?ctrl=admin&act=bannerList"
                    class="<?= $current_act == 'bannerList' || $current_act == 'bannerForm' ? 'active' : '' ?>">🖼️
                    Banner (Slide)</a></li>

            <li><a href="<?= BASE_URL ?>?ctrl=admin&act=orderList"
                    class="<?= $current_act == 'orderList' || $current_act == 'orderDetail' ? 'active' : '' ?>">📋 Đơn
                    hàng</a></li>

            <li><a href="<?= BASE_URL ?>?ctrl=admin&act=userList"
                    class="<?= $current_act == 'userList' ? 'active' : '' ?>">👥 Người dùng</a></li>

            <li><a href="<?= BASE_URL ?>?ctrl=admin&act=statistics"
                    class="<?= $current_act == 'statistics' ? 'active' : '' ?>">📈 Thống kê</a></li>
        </ul>
    </div>

    <main class="admin-content">