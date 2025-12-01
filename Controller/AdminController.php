<?php 
// Controller/AdminController.php

include_once 'Models/Product.php'; 
include_once 'Models/User.php'; 
// (LƯU Ý: Các hàm quản lý Category và Order được định nghĩa trong Models/Product.php)

class AdminController {
    private $productModel;
    private $userModel;
    
    function __construct() {
        // KIỂM TRA BẢO MẬT: Phải đăng nhập và có role = 1
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
            echo "<script>alert('Bạn không có quyền truy cập trang quản trị!'); window.location='index.php?ctrl=user&act=login';</script>";
            exit();
        }
        $this->productModel = new Product();
        $this->userModel = new User();
    }

    function dashboard() {
        // Có thể bổ sung logic lấy số liệu thống kê ở đây
        include_once 'Views/admin/dashboard.php';
    }

    // ===================================
    // 1. USER MANAGEMENT (Quản lý Tài khoản)
    // ===================================

    function userList() {
        $users = $this->userModel->getAllUsers();
        include_once 'Views/admin/user_list.php';
    }
    
    function userDelete() {
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $msg = 'Đã xóa người dùng thành công!'; // Mặc định thành công

             if ($id != $_SESSION['user']['id']) { // Ngăn chặn tự xóa tài khoản đang đăng nhập
                $result = $this->userModel->deleteUser($id);
                if (!$result) {
                    $msg = 'LỖI: Không thể xóa người dùng khỏi Database.';
                }
            } else {
                $msg = 'Không thể xóa tài khoản của chính bạn!';
            }
            
            $safe_msg = addslashes($msg);
            echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=userList';</script>";
        }
    }

    // ===================================
    // 2. CATEGORY MANAGEMENT (Quản lý Danh mục)
    // ===================================

    function categoryList() {
        $categories = $this->productModel->getAllCategories();
        include_once 'Views/admin/category_list.php';
    }
    
    function categoryForm() {
        $category = null;
        if (isset($_GET['id'])) {
            $category = $this->productModel->getCategoryById($_GET['id']);
        }
        include_once 'Views/admin/category_form.php';
    }
    
    function categoryPost() {
        $id = $_POST['id'] ?? 0;
        $name = $_POST['name'];
        $status = $_POST['status'];
        $result = false;
        $msg = 'LỖI: Thao tác thất bại, vui lòng kiểm tra dữ liệu nhập.';

        if ($id > 0) {
            $result = $this->productModel->updateCategory($id, $name, $status);
            if ($result) { $msg = 'Cập nhật danh mục thành công!'; }
        } else {
            $result = $this->productModel->insertCategory($name, $status);
            if ($result) { $msg = 'Thêm danh mục mới thành công!'; }
        }
        
        $safe_msg = addslashes($msg);
        echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=categoryList';</script>";
    }

    function categoryDelete() {
        if(isset($_GET['id'])) {
            $result = $this->productModel->deleteCategory($_GET['id']);
            $msg = $result ? 'Đã xóa danh mục thành công! Lưu ý: Các sản phẩm thuộc danh mục này cũng bị xóa.' : 'LỖI: Không thể xóa danh mục khỏi Database.';
            
            $safe_msg = addslashes($msg);
            echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=categoryList';</script>";
        }
    }

    // ===================================
    // 3. PRODUCT MANAGEMENT (Quản lý Sản phẩm)
    // ===================================

   function productList() {
    $products = $this->productModel->getAllProductsAdmin();
    // THÊM DÒNG NÀY ĐỂ TRUYỀN BIẾN CATEGORIES VÀO VIEW
    $categories = $this->productModel->getAllCategories(); 
    
    include_once 'Views/admin/product_list.php';
}
    
    function productForm() {
        $product = null;
        // Lấy thông tin sản phẩm nếu đang sửa
        if (isset($_GET['id'])) {
            $product = $this->productModel->getProductById($_GET['id']);
        }
        // Lấy danh mục để đổ vào form
        $categories = $this->productModel->getAllCategories();
        include_once 'Views/admin/product_form.php';
    }
    
    function productPost() {
        $id = $_POST['id'] ?? 0;
        $categoryId = $_POST['category_id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $priceSale = $_POST['price_sale'] ?? 0;
        $image = $_POST['image'];
        $description = $_POST['description'];
        $material = $_POST['material'];
        $brand = $_POST['brand'];
        $skuCode = $_POST['sku_code'];

        $result = false;
        $msg = 'LỖI: Thao tác thất bại, vui lòng kiểm tra dữ liệu nhập.'; // Mặc định lỗi

        if ($id > 0) {
            // Chế độ Sửa
            $result = $this->productModel->updateProduct($id, $categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode);
            if ($result) { $msg = 'Cập nhật sản phẩm thành công!'; }
        } else {
            // Chế độ Thêm mới
            $result = $this->productModel->insertProduct($categoryId, $name, $price, $image, $description, $material, $brand, $skuCode);
            if ($result) { $msg = 'Thêm sản phẩm mới thành công!'; }
        }

        // Đảm bảo thông báo alert là an toàn và chuyển hướng
        $safe_msg = addslashes($msg);
        echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=productList';</script>";
    }

    function productDelete() {
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $result = $this->productModel->deleteProduct($id);
            
            $msg = $result ? 'Đã xóa sản phẩm thành công!' : 'LỖI: Không thể xóa sản phẩm khỏi Database (có thể do ràng buộc khóa ngoại).';
            
            $safe_msg = addslashes($msg);
            echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=productList';</script>";
        }
    }
    
    // ===================================
    // 4. ORDER MANAGEMENT (Quản lý Đơn hàng)
    // ===================================

    function orderList() {
        $orders = $this->productModel->getAllOrders();
        include_once 'Views/admin/order_list.php';
    }
    
    function orderDetail() {
        if (isset($_GET['id'])) {
            $orderId = $_GET['id'];
            $order = $this->productModel->getOrderById($orderId);
            $orderDetails = $this->productModel->getOrderDetails($orderId);
            
            // Xử lý cập nhật trạng thái nếu có POST
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_status'])) {
                $newStatus = (int)$_POST['new_status'];
                $result = $this->productModel->updateOrderStatus($orderId, $newStatus);
                
                $msg = $result ? 'Cập nhật trạng thái đơn hàng thành công!' : 'LỖI: Cập nhật trạng thái thất bại.';
                $safe_msg = addslashes($msg);
                
                echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=orderDetail&id=$orderId';</script>";
                exit;
            }

            include_once 'Views/admin/order_detail.php';
        } else {
            header("Location: ?ctrl=admin&act=orderList");
        }
    }
}
?>