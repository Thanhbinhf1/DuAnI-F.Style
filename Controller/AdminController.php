<?php 
// Controller/AdminController.php

include_once 'Models/Product.php'; 
include_once 'Models/User.php'; 

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

    // --- USER MANAGEMENT (Quản lý Tài khoản) ---

    function userList() {
        $users = $this->userModel->getAllUsers();
        include_once 'Views/admin/user_list.php';
    }
    
    function userDelete() {
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
             if ($id != $_SESSION['user']['id']) { // Ngăn chặn tự xóa tài khoản đang đăng nhập
                $this->userModel->deleteUser($id);
                echo "<script>alert('Đã xóa người dùng thành công!'); window.location='?ctrl=admin&act=userList';</script>";
            } else {
                echo "<script>alert('Không thể xóa tài khoản của chính bạn!'); window.location='?ctrl=admin&act=userList';</script>";
            }
        }
    }

    // --- CATEGORY MANAGEMENT (Quản lý Danh mục) ---

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

        if ($id > 0) {
            $this->productModel->updateCategory($id, $name, $status);
            $msg = 'Cập nhật danh mục thành công!';
        } else {
            $this->productModel->insertCategory($name, $status);
            $msg = 'Thêm danh mục mới thành công!';
        }
        echo "<script>alert('$msg'); window.location='?ctrl=admin&act=categoryList';</script>";
    }

    function categoryDelete() {
        if(isset($_GET['id'])) {
            $this->productModel->deleteCategory($_GET['id']);
            echo "<script>alert('Đã xóa danh mục thành công! Lưu ý: Các sản phẩm thuộc danh mục này cũng bị xóa.'); window.location='?ctrl=admin&act=categoryList';</script>";
        }
    }

    // --- PRODUCT MANAGEMENT (Quản lý Sản phẩm) ---

    function productList() {
        $products = $this->productModel->getAllProductsAdmin();
        include_once 'Views/admin/product_list.php';
    }
    
    function productForm() {
        $product = null;
        if (isset($_GET['id'])) {
            $product = $this->productModel->getProductById($_GET['id']);
        }
        $categories = $this->productModel->getAllCategories();
        include_once 'Views/admin/product_form.php';
    }
    
    function productPost() {
        $id = $_POST['id'] ?? 0;
        $categoryId = $_POST['category_id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $priceSale = $_POST['price_sale'] ?? 0;
        $image = $_POST['image']; // Tạm thời dùng link ảnh
        $description = $_POST['description'];
        $material = $_POST['material'];
        $brand = $_POST['brand'];
        $skuCode = $_POST['sku_code'];

        if ($id > 0) {
            $this->productModel->updateProduct($id, $categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode);
            $msg = 'Cập nhật sản phẩm thành công!';
        } else {
            $this->productModel->insertProduct($categoryId, $name, $price, $image, $description, $material, $brand, $skuCode);
            $msg = 'Thêm sản phẩm mới thành công!';
        }
        echo "<script>alert('$msg'); window.location='?ctrl=admin&act=productList';</script>";
    }

    function productDelete() {
        if(isset($_GET['id'])) {
            $this->productModel->deleteProduct($_GET['id']);
            echo "<script>alert('Đã xóa sản phẩm thành công!'); window.location='?ctrl=admin&act=productList';</script>";
        }
    }
    
    // --- ORDER MANAGEMENT (Quản lý Đơn hàng) ---

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
                $this->productModel->updateOrderStatus($orderId, $newStatus);
                echo "<script>alert('Cập nhật trạng thái đơn hàng thành công!'); window.location='?ctrl=admin&act=orderDetail&id=$orderId';</script>";
                exit;
            }

            include_once 'Views/admin/order_detail.php';
        } else {
            header("Location: ?ctrl=admin&act=orderList");
        }
    }
}
?>