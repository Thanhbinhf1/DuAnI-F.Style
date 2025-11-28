<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f4f7f6; }
        .sidebar { height: 100%; width: 250px; position: fixed; top: 0; left: 0; background-color: #2c3e50; padding-top: 20px; color: white; }
        .sidebar a { padding: 10px 15px; text-decoration: none; font-size: 18px; color: #ecf0f1; display: block; transition: background-color 0.3s; }
        .sidebar a:hover { background-color: #34495e; color: #fff; }
        .sidebar h3 { text-align: center; margin-bottom: 30px; color: #1abc9c; }
        .content { margin-left: 250px; padding: 20px; }
        .content h1 { border-bottom: 2px solid #ddd; padding-bottom: 10px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; color: #333; }
        .action-link { margin-right: 10px; color: #3498db; text-decoration: none; }
        .btn { padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .btn-primary { background-color: #2980b9; color: white; }
    </style>
</head>
<body>

<div class="sidebar">
    <h3>F.Style ADMIN</h3>
    <a href="?ctrl=Admin&act=index">Dashboard</a>
    <a href="?ctrl=AdminProduct&act=listProducts">Quản lý Sản phẩm</a>
    <a href="?ctrl=AdminProduct&act=addProduct">Thêm Sản phẩm</a>
    <a href="?ctrl=AdminOrder&act=listOrders">Quản lý Đơn hàng</a>
    <a href="?ctrl=AdminUser&act=listUsers">Quản lý Tài khoản</a>
    <a href="index.php">Về trang khách</a>
</div>

<div class="content">
    <?php 
        // Nội dung trang cụ thể sẽ được nhúng vào đây
        if (isset($view_file)) {
            include_once $view_file;
        }
    ?>
    
</div>

</body>
</html>