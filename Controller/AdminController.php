<?php 
// Controller/AdminController.php
include_once 'Models/User.php';
include_once 'Models/Product.php';
include_once 'Models/Category.php'; // THÊM MỚI
include_once 'Models/Order.php';    // THÊM MỚI

class AdminController {
    private $userModel;
    private $productModel;
    private $orderModel; 
    private $categoryModel; 

    function __construct() {
        // KIỂM TRA BẢO MẬT: Phải đăng nhập và có role = 1
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
            // Chuyển hướng về trang login nếu không phải admin
            echo "<script>alert('Bạn không có quyền truy cập trang quản trị!'); window.location='index.php?ctrl=user&act=login';</script>";
            exit();
        }
        
        $this->userModel = new User();
        $this->productModel = new Product();
        $this->orderModel = new Order(); // KHỞI TẠO MỚI
        $this->categoryModel = new Category(); // KHỞI TẠO MỚI
    }

    function dashboard() {
        // Logic lấy dữ liệu tổng quan cho trang quản trị 
        // LƯU Ý: Dữ liệu hiện tại trong Views/admin/dashboard.php là cứng (hard-coded)
        
        include_once 'Views/admin/dashboard.php';
    }
    
    // ======================================
    // 1. QUẢN LÝ NGƯỜI DÙNG (USERS)
    // ======================================
    function listUsers() {
        $users = $this->userModel->getAllUsers();
        include_once 'Views/admin/user_list.php';
    }
    
    // ======================================
    // 2. QUẢN LÝ SẢN PHẨM (PRODUCTS)
    // ======================================
    function listProducts() {
        $products = $this->productModel->getAllProductsList();
        $categories = $this->categoryModel->getAllCategories();
        include_once 'Views/admin/product_list.php';
    }
    
    // ======================================
    // 3. QUẢN LÝ ĐƠN HÀNG (ORDERS)
    // ======================================
    function listOrders() {
        $orders = $this->orderModel->getAllOrders();
        include_once 'Views/admin/order_list.php';
    }
    
    function orderDetail() {
        if (!isset($_GET['id'])) {
            header("Location: ?ctrl=admin&act=listOrders");
            exit();
        }
        $orderId = $_GET['id'];
        $data = $this->orderModel->getOrderDetail($orderId);
        
        include_once 'Views/admin/order_detail.php';
    }

    function updateOrderStatus() {
        if (isset($_POST['order_id']) && isset($_POST['status'])) {
            $orderId = $_POST['order_id'];
            $status = $_POST['status'];
            
            $this->orderModel->updateOrderStatus($orderId, $status);
            
            echo "<script>alert('Cập nhật trạng thái đơn hàng thành công!'); window.location='?ctrl=admin&act=orderDetail&id=$orderId';</script>";
        } else {
            header("Location: ?ctrl=admin&act=listOrders");
        }
    }
}
?>