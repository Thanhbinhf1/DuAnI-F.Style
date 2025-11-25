<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F.Style Store</title>
    <link rel="stylesheet" href="./public/css/style.css">
</head>

<body>
    <header>
        <div class="logo-section">
            <img src="img/logo.png" alt="Logo" style="height: 50px;">

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
                <span>Xin chào, <b><?=$_SESSION['user']['fullname']?></b></span>
                <a href="?ctrl=user&act=logout" style="color: red; margin-left: 10px;">(Thoát)</a>
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
                <li><a href="?ctrl=page&act=about">Giới thiệu</a></li>
                <li><a href="?ctrl=product&act=list">Sản phẩm</a></li>
                <li><a href="?ctrl=page&act=contact">Liên hệ</a></li>
            </ul>
        </nav>
    </header>

    <main>