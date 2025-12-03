<?php 
// Controller/AdminController.php

include_once 'Models/Product.php'; 
include_once 'Models/User.php'; 
include_once 'Models/Category.php';
include_once 'Models/Order.php';
include_once 'csrf.php'; 

class AdminController {
    private $productModel;
    private $userModel;
    private $categoryModel;
    private $orderModel;
    
    function __construct() {
        // KIỂM TRA BẢO MẬT: Phải đăng nhập và có role = 1
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
            echo "<script>alert('Bạn không có quyền truy cập trang quản trị!'); window.location='index.php?ctrl=user&act=login';</script>";
            exit();
        }
        $this->productModel = new Product();
        $this->userModel = new User();
        $this->categoryModel = new Category();
        $this->orderModel = new Order();
    }

    function dashboard() {
        $stats = [
            'products' => $this->productModel->countTotalProducts(),
            'new_orders' => $this->orderModel->countNewOrders(),
            'users' => $this->userModel->countTotalUsers(),
            'income' => $this->orderModel->calculateTotalIncome(),
            'monthly_income' => $this->orderModel->getMonthlyIncome()
        ];
        $recent_activities = $this->orderModel->getRecentActivityOrders();
        include_once 'Views/admin/dashboard.php';
    }

    // --- USER MANAGEMENT ---

    function userList() {
        $users = $this->userModel->getAllUsers();
        include_once 'Views/admin/user_list.php';
    }
    
    function userDelete() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
                die('Invalid CSRF token');
            }
            $id = $_POST['id'];
            $msg = 'Đã xóa người dùng thành công!';

            if ($id != $_SESSION['user']['id']) { 
                $result = $this->userModel->deleteUser($id);
                if (!$result) {
                    $msg = 'LỖI: Không thể xóa người dùng khỏi Database.';
                }
            } else {
                $msg = 'Không thể xóa tài khoản của chính bạn!';
            }
            
            $safe_msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
            echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=userList';</script>";
        }
    }

    function userUpdateRole() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
                die('Invalid CSRF token');
            }
            $id = $_POST['user_id'];
            $role = $_POST['role'];
            $msg = 'Cập nhật vai trò thành công!';

            if ($id != $_SESSION['user']['id']) { 
                $result = $this->userModel->updateUserRole($id, $role);
                if (!$result) {
                    $msg = 'LỖI: Không thể cập nhật vai trò.';
                }
            } else {
                $msg = 'Bạn không thể thay đổi vai trò của chính mình!';
            }
            
            $safe_msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
            echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=userList';</script>";
        }
    }

    // --- CATEGORY MANAGEMENT ---

    function categoryList() {
        $categories = $this->categoryModel->getAllCategories();
        include_once 'Views/admin/category_list.php';
    }
    
    function categoryForm() {
        $category = null;
        if (isset($_GET['id'])) {
            $category = $this->categoryModel->getCategoryById($_GET['id']);
        }
        include_once 'Views/admin/category_form.php';
    }
    
    function categoryPost() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
                die('Invalid CSRF token');
            }
            $id = $_POST['id'] ?? 0;
            $name = $_POST['name'];
            $status = $_POST['status'];

            $result = false;
            $msg = 'LỖI: Thao tác thất bại, vui lòng kiểm tra dữ liệu nhập.';

            if ($id > 0) {
                $result = $this->categoryModel->updateCategory($id, $name, $status);
                if ($result) { $msg = 'Cập nhật danh mục thành công!'; }
            } else {
                $result = $this->categoryModel->insertCategory($name, $status);
                if ($result) { $msg = 'Thêm danh mục mới thành công!'; }
            }

            $safe_msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
            echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=categoryList';</script>";
        }
    }

    function categoryDelete() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
                die('Invalid CSRF token');
            }
            $id = $_POST['id'];
            $result = $this->categoryModel->deleteCategory($id);
            $msg = $result ? 'Đã xóa danh mục thành công! Lưu ý: Các sản phẩm thuộc danh mục này cũng bị xóa.' : 'LỖI: Không thể xóa danh mục khỏi Database (do ràng buộc khóa ngoại).';
            
            $safe_msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
            echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=categoryList';</script>";
        }
    }

    // --- PRODUCT MANAGEMENT ---

    function productList() {
        $products = $this->productModel->getAllProductsAdmin();
        $categories = $this->categoryModel->getAllCategories();
        include_once 'Views/admin/product_list.php';
    }
    
    function productForm() {
        $product = null;
        if (isset($_GET['id'])) {
            $product = $this->productModel->getProductById($_GET['id']);
        }
        $categories = $this->categoryModel->getAllCategories();
        include_once 'Views/admin/product_form.php';
    }
    
    // Đã thêm kiểm tra trùng tên và giá khuyến mãi
    function productPost() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
                die('Invalid CSRF token');
            }
            
            // --- Xử lý dữ liệu form ---
            $id = $_POST['id'] ?? 0;
            $categoryId = $_POST['category_id'];
            $name = trim($_POST['name']);
            $price = (float)$_POST['price'];
            $priceSale = (float)$_POST['price_sale'] ?? 0;
            $description = $_POST['description'];
            $material = $_POST['material'];
            $brand = $_POST['brand'];
            $skuCode = $_POST['sku_code'];
            
            $msg = '';
            
            // 1. Kiểm tra trùng tên (Yêu cầu hàm checkProductNameExist() trong Product Model)
            if ($this->productModel->checkProductNameExist($name, $id)) {
                $msg = 'LỖI: Tên sản phẩm đã tồn tại trong hệ thống. Vui lòng chọn tên khác.';
            } 
            // 2. Kiểm tra Giá khuyến mãi
            elseif ($priceSale > 0 && $priceSale >= $price) {
                $msg = 'LỖI: Giá khuyến mãi phải thấp hơn Giá gốc.';
            }
            
            if (!empty($msg)) {
                $safe_msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
                echo "<script>alert('$safe_msg'); history.back();</script>";
                exit;
            }

            // --- Xử lý upload ảnh ---
            $image = $_POST['image_current'] ?? ''; 
            // ... (Logic upload ảnh được bỏ qua ở đây nhưng cần tồn tại trong file gốc)

            if ($id == 0 && $image == '') {
                echo "<script>alert('LỖI: Vui lòng thêm ảnh cho sản phẩm mới.'); history.back();</script>";
                exit;
            }

            // --- Tương tác với Model ---
            $result = false;
            $final_msg = 'LỖI: Thao tác thất bại.'; 

            if ($id > 0) {
                $result = $this->productModel->updateProduct($id, $categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode);
                if ($result) { $final_msg = 'Cập nhật sản phẩm thành công!'; }
            } else {
                $result = $this->productModel->insertProduct($categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode);
                if ($result) { $final_msg = 'Thêm sản phẩm mới thành công!'; }
            }

            $safe_msg = htmlspecialchars($final_msg, ENT_QUOTES, 'UTF-8');
            echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=productList';</script>";
        }
    }

    // Hàm ẨN/HIỆN sản phẩm (Đã hoạt động)
    function productToggleStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
                die('Invalid CSRF token');
            }
            $id = $_POST['id'];
            $currentStatus = (int)$_POST['current_status'];
            $newStatus = $currentStatus == 1 ? 0 : 1;
            
            // Yêu cầu hàm toggleProductStatus() trong Product Model
            $result = $this->productModel->toggleProductStatus($id, $newStatus);
            
            $statusText = $newStatus == 1 ? 'HIỆN' : 'ẨN';
            $msg = $result ? "Đã chuyển trạng thái sản phẩm #$id sang $statusText thành công!" : 'LỖI: Không thể cập nhật trạng thái sản phẩm.';
            
            $safe_msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
            echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=productList';</script>";
        }
    }
    
    // --- ORDER MANAGEMENT ---

    function orderList() {
        // Fix lỗi thiếu action OrderList và lỗi Undefined variable $products trong order_list.php
        $orders = $this->orderModel->getAllOrders();
        include_once 'Views/admin/order_list.php';
    }
    
    function orderDetail() {
        if (isset($_GET['id'])) {
            $orderId = $_GET['id'];
            $order = $this->orderModel->getOrderById($orderId);
            $orderDetails = $this->orderModel->getOrderDetails($orderId);
            
            if (!$order) {
                echo "<script>alert('LỖI: Không tìm thấy đơn hàng này!'); window.location='?ctrl=admin&act=orderList';</script>";
                exit;
            }
            
            // Xử lý cập nhật trạng thái nếu có POST
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_status'])) {
                if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
                    die('Invalid CSRF token');
                }
                $newStatus = (int)$_POST['new_status'];
                $result = $this->orderModel->updateOrderStatus($orderId, $newStatus);

                $msg = $result ? 'Cập nhật trạng thái đơn hàng thành công!' : 'LỖI: Cập nhật trạng thái thất bại.';
                $safe_msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
                
                echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=orderDetail&id=$orderId';</script>";
                exit;
            }

            include_once 'Views/admin/order_detail.php';
            
        } else {
            header("Location: ?ctrl=admin&act=orderList");
        }
    }

    function confirmPayment() {
        if (isset($_GET['id'])) {
            $orderId = $_GET['id'];
            $result = $this->orderModel->updatePaymentStatus($orderId, 1);
            $msg = $result ? 'Xác nhận thanh toán thành công!' : 'LỖI: Xác nhận thanh toán thất bại.';
            $safe_msg = addslashes($msg);
            
            echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=orderDetail&id=$orderId';</script>";
            exit;
        } else {
            header("Location: ?ctrl=admin&act=orderList");
        }
    }
    
    // --- HÀM THỐNG KÊ (ĐÃ FIX LỖI) ---
    function statistics() {
        // Yêu cầu các hàm thống kê từ Model
        $saleStats = $this->orderModel->getSaleStatistics();
        $productStats = [
            'top_selling'  => $this->productModel->getTopSellingProducts(10),
            'slow_selling' => $this->productModel->getSlowSellingProducts(10),
            'orders_daily' => $this->orderModel->countOrdersByInterval('DAY'),
        ];
        
        $stats = array_merge($saleStats, $productStats);
        
        // Sẽ tìm file này ở Views/admin/statistics.php
        include_once 'Views/admin/statistics.php';
    }
}
?>