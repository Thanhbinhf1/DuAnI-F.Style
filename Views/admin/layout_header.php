<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - F.Style Store</title>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f4f4; margin: 0; }
        .admin-header { background-color: #2c3e50; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .admin-header a { color: #f1c40f; text-decoration: none; margin-left: 20px; transition: 0.3s; }
        .admin-header a:hover { color: white; }
        .admin-sidebar { width: 250px; background-color: #34495e; color: white; height: calc(100vh - 60px); position: fixed; top: 60px; left: 0; padding-top: 20px; }
        .admin-sidebar ul { list-style: none; padding: 0; margin: 0; }
        .admin-sidebar ul li a { display: block; padding: 15px 30px; color: white; text-decoration: none; border-left: 5px solid transparent; transition: 0.2s; }
        .admin-sidebar ul li a:hover, .admin-sidebar ul li a.active { background-color: #2c3e50; border-left: 5px solid #e74c3c; }
        .admin-content { margin-left: 250px; padding: 30px; min-height: calc(100vh - 120px); }
        .admin-footer { margin-left: 250px; padding: 20px; text-align: center; color: #777; font-size: 14px; background: #fff; border-top: 1px solid #eee; }
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-header">
        <span style="font-size: 20px; font-weight: bold;">F.Style | ADMIN PANEL</span>
        <div>
            <span style="font-size: 14px;">Xin chào, <b><?=$_SESSION['user']['fullname'] ?? 'Admin'?></b></span>
            <a href="index.php">⌂ Trang Khách hàng</a>
            <a href="?ctrl=user&act=logout">Đăng xuất</a>
        </div>
    </div>
    
    <div class="admin-sidebar">
        <ul>
            <li><a href="?ctrl=admin&act=dashboard" class="active">📊 Tổng quan</a></li>
            <li><a href="#">📦 Sản phẩm</a></li>
            <li><a href="#">📋 Đơn hàng</a></li>
            <li><a href="#">👥 Người dùng</a></li>
            <li><a href="#">🗃️ Danh mục</a></li>
        </ul>
    </div>

    <main class="admin-content"></main>