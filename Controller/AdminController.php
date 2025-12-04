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

    // --- USER MANAGEMENT (Quản lý Tài khoản) ---

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

    // --- CATEGORY MANAGEMENT (Quản lý Danh mục) ---

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

    // --- PRODUCT MANAGEMENT (Quản lý Sản phẩm) ---

    function productList() {
        $products = $this->productModel->getAllProductsAdmin();
        // Đảm bảo biến $categories được truyền đi
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
    
    function productPost() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
                die('Invalid CSRF token');
            }
            
            // --- Xử lý dữ liệu form ---
            $id = $_POST['id'] ?? 0;
            $categoryId = $_POST['category_id'];
            $name = $_POST['name'];
            $price = $_POST['price'];
            $priceSale = $_POST['price_sale'] ?? 0;
            $description = $_POST['description'];
            $material = $_POST['material'];
            $brand = $_POST['brand'];
            $skuCode = $_POST['sku_code'];
            
            $skuCode = $_POST['sku_code'];
            
    // --- Xử lý upload ảnh (ĐÃ SỬA BẢO MẬT) ---
    $image = $_POST['image_current'] ?? ''; 

    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
        $target_dir = "Public/Uploads/Products/";
        $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];
        $filename = $_FILES["image_file"]["name"];
        $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        // 1. Kiểm tra đuôi file
        if (!in_array($file_extension, $allowed_types)) {
            echo "<script>alert('LỖI: Chỉ cho phép file ảnh JPG, JPEG, PNG & GIF.'); history.back();</script>";
            exit;
        }

        // 2. [QUAN TRỌNG] Kiểm tra nội dung file có phải ảnh thật không
        $check = getimagesize($_FILES["image_file"]["tmp_name"]);
        if ($check === false) {
            echo "<script>alert('LỖI: File tải lên không phải là ảnh hợp lệ (có thể là mã độc).'); history.back();</script>";
            exit;
        }

        // Đặt tên file ngẫu nhiên để tránh trùng
        $new_filename = uniqid('product_', true) . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES["image_file"]["tmp_name"], $target_file)) {
            $image = $target_file; 
        } else {
            echo "<script>alert('LỖI: Không thể lưu file vào thư mục.'); history.back();</script>";
            exit;
        }
    }
            
            // --- Kiểm tra ảnh khi thêm mới ---
            if ($id == 0 && $image == '') {
                echo "<script>alert('LỖI: Vui lòng thêm ảnh cho sản phẩm mới.'); history.back();</script>";
                exit;
            }

            // --- Tương tác với Model ---
            $result = false;
            $msg = 'LỖI: Thao tác thất bại, vui lòng kiểm tra kết nối Database hoặc dữ liệu nhập.'; 

            if ($id > 0) {
                // Chế độ Sửa
                $result = $this->productModel->updateProduct($id, $categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode);
                if ($result) { $msg = 'Cập nhật sản phẩm thành công!'; }
            } else {
                // Chế độ Thêm mới
                $result = $this->productModel->insertProduct($categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode);
                if ($result) { $msg = 'Thêm sản phẩm mới thành công!'; }
            }

            $safe_msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
            echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=productList';</script>";
        }
    }

    function productDelete() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
                die('Invalid CSRF token');
            }
            $id = $_POST['id'];
            $result = $this->productModel->deleteProduct($id);
            
            $msg = $result ? 'Đã xóa sản phẩm thành công!' : 'LỖI: Không thể xóa sản phẩm khỏi Database (có thể do ràng buộc khóa ngoại).';
            
            $safe_msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
            echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=productList';</script>";
        }
    }
    
    // --- ORDER MANAGEMENT (Quản lý Đơn hàng) ---

    function orderList() {
        $orders = $this->orderModel->getAllOrders();
        include_once 'Views/admin/order_list.php';
    }
    
    function orderDetail() {
        if (isset($_GET['id'])) {
            $orderId = $_GET['id'];
            
            // Lấy dữ liệu từ Model. Các biến này sẽ CÓ SẴN trong View.
            $order = $this->orderModel->getOrderById($orderId);
            $orderDetails = $this->orderModel->getOrderDetails($orderId);
            
            // KIỂM TRA AN TOÀN: Nếu không tìm thấy đơn hàng, chuyển hướng
            if (!$order) {
                echo "<script>alert('LỖI: Không tìm thấy đơn hàng này!'); window.location='?ctrl=admin&act=orderList';</script>";
                exit;
            }
            
            // NOTE: KHÔNG tạo biến $data ở đây nếu View không dùng nó. 
            // Ta sẽ sử dụng trực tiếp $order và $orderDetails trong View.
            
            // Xử lý cập nhật trạng thái nếu có POST (sử dụng $orderId)
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
            $result = $this->orderModel->updatePaymentStatus($orderId, 1); // 1 = Đã thanh toán

            $msg = $result ? 'Xác nhận thanh toán thành công!' : 'LỖI: Xác nhận thanh toán thất bại.';
            $safe_msg = addslashes($msg);
            
            echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=orderDetail&id=$orderId';</script>";
            exit;
        } else {
            header("Location: ?ctrl=admin&act=orderList");
        }
    }
}
?>