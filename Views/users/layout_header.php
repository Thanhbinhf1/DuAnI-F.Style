<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F.Style Store</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS của bạn -->
    <link rel="stylesheet" href="./Public/Css/home.css">
</head>
<body style="font-family: 'Inter', sans-serif;">
<header class="border-bottom bg-white shadow-sm">
    <nav class="navbar navbar-expand-lg navbar-light container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="./Public/Img/logo.png" alt="F.Style Logo" style="height: 40px;" class="me-2">
            <span class="fw-bold">F.Style</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNavbar" aria-controls="mainNavbar"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="index.php">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="?ctrl=product&act=list">Sản phẩm</a></li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="catDropdown" role="button" data-bs-toggle="dropdown">
                        Danh mục
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?ctrl=product&act=list&cat=1">Đồ nam</a></li>
                        <li><a class="dropdown-item" href="?ctrl=product&act=list&cat=2">Đồ nữ</a></li>
                        <li><a class="dropdown-item" href="?ctrl=product&act=list&cat=3">Quần Jeans</a></li>
                        <li><a class="dropdown-item" href="?ctrl=product&act=list&cat=4">Phụ kiện</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="?ctrl=product&act=list&type=sale">Săn Sale 🔥</a></li>
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link" href="?ctrl=page&act=about">Giới thiệu</a></li>
                <li class="nav-item"><a class="nav-link" href="?ctrl=page&act=contact">Liên hệ</a></li>
            </ul>

            <form class="d-flex me-3" action="?ctrl=product&act=list" method="get">
                <input type="hidden" name="ctrl" value="product">
                <input type="hidden" name="act" value="list">
                <input class="form-control form-control-sm me-2" type="search" name="keyword" placeholder="Tìm sản phẩm...">
                <button class="btn btn-outline-dark btn-sm" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </form>

            <div class="d-flex align-items-center gap-3">
                <a href="?ctrl=cart&act=view" class="text-dark position-relative">
                    <i class="fa-solid fa-cart-shopping fa-lg"></i>
                    <?php $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                    <?php if($cartCount > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?=$cartCount?>
                        </span>
                    <?php endif; ?>
                </a>

                <?php if(isset($_SESSION['user'])): ?>
                    <div class="dropdown">
                        <a class="text-dark dropdown-toggle text-decoration-none" href="#" data-bs-toggle="dropdown">
                            <i class="fa-regular fa-user"></i>
                            <span class="ms-1"><?=htmlspecialchars($_SESSION['user']['fullname'] ?? $_SESSION['user']['username'])?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="?ctrl=user&act=profile">Tài khoản của tôi</a></li>
                            <li><a class="dropdown-item" href="?ctrl=user&act=edit">Chỉnh sửa thông tin</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="?ctrl=user&act=logout">Đăng xuất</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="?ctrl=user&act=login" class="btn btn-outline-dark btn-sm">Đăng nhập</a>
                    <a href="?ctrl=user&act=register" class="btn btn-dark btn-sm">Đăng ký</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>

<main class="py-3">
