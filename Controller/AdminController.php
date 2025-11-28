<?php
// File: Controller/AdminController.php

class AdminController {
    function __construct() {
        // TODO: Logic kiểm tra quyền ADMIN
        // if (!isAdmin()) { header('Location: index.php'); exit; }
    }

    function index() {
        // Đây là trang Dashboard tổng quan
        // Có thể load các thống kê tại đây (ví dụ: số sản phẩm, số đơn hàng, doanh thu)
        include_once 'Views/admin/dashboard.php';
    }
}
?>