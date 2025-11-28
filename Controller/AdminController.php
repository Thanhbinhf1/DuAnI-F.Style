<?php 
// Controller/AdminController.php
class AdminController {
    function __construct() {
        // KIỂM TRA BẢO MẬT: Phải đăng nhập và có role = 1
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
            // Chuyển hướng về trang login nếu không phải admin
            echo "<script>alert('Bạn không có quyền truy cập trang quản trị!'); window.location='index.php?ctrl=user&act=login';</script>";
            exit();
        }
    }

    function dashboard() {
        // Logic lấy dữ liệu tổng quan cho trang quản trị 
        
        include_once 'Views/admin/dashboard.php';
    }
    
    // Thêm các hàm quản lý khác tại đây
}
?>