<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F.Style Store</title>
    <link rel="stylesheet" href="./Public/Css/home.css">
</head>

<body>
    <header>
        <div class="logo-section">
            <img src="./Public/Img/logo.png" alt="Logo" style="height: 60px;">

            <div class="search-box">
                <form action="index.php" method="get">
                    <input type="hidden" name="ctrl" value="product">
                    <input type="hidden" name="act" value="search">
                    <input type="text" name="keyword" placeholder="Tìm kiếm...">
                    <button type="submit">Tìm</button>
                </form>
            </div>

            <div class="user-actions">
                <?php if(isset($_SESSION['user'])) { ?>
                <span><b><?=$_SESSION['user']['fullname']?></b></span>
                <a href="?ctrl=user&act=logout" style="color: black; margin-left: 10px;">Đăng xuất</a>
                <?php } else { ?>
                <a href="?ctrl=user&act=login">Đăng nhập</a>
                <a href="?ctrl=user&act=register">Đăng ký</a>
                <?php } ?>

                <a href="?ctrl=cart&act=view">Giỏ hàng</a>
            </div>
        </div>

        <nav>
    <ul>
        <li><a href="index.php">Trang chủ</a></li>
        
        <li class="dropdown">
            <a href="?ctrl=product&act=list">Sản phẩm <span class="arrow">▼</span></a>
            <ul class="dropdown-content">
                <li><a href="?ctrl=product&act=list&cat=1">Áo Thời Trang</a></li>
                <li><a href="?ctrl=product&act=list&cat=3">Quần Jean & Kaki</a></li>
                <li><a href="?ctrl=product&act=list&cat=5">Phụ Kiện</a></li>
                <li><a href="?ctrl=product&act=list&type=sale">Săn Sale Giá Sốc 🔥</a></li>
                <li><a href="?ctrl=product&act=list">Tất cả sản phẩm</a></li>
            </ul>
        </li>

        <li><a href="?ctrl=page&act=about">Giới thiệu</a></li>
        <li><a href="?ctrl=page&act=contact">Liên hệ</a></li>
    </ul>
</nav>
    </header>

    <main>