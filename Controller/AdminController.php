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
        $stats = [
            'products' => $this->productModel->countTotalProducts(),
            'new_orders' => $this->productModel->countNewOrders(),
            'users' => $this->userModel->countTotalUsers(),
            'income' => $this->productModel->calculateTotalIncome(),
            'monthly_income' => $this->productModel->getMonthlyIncome()
        ];
        $recent_activities = $this->productModel->getRecentActivityOrders();
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
            $msg = 'Đã xóa người dùng thành công!'; // Mặc định thành công

            if ($id != $_SESSION['user']['id']) { 
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
            $msg = $result ? 'Đã xóa danh mục thành công! Lưu ý: Các sản phẩm thuộc danh mục này cũng bị xóa.' : 'LỖI: Không thể xóa danh mục khỏi Database (do ràng buộc khóa ngoại).';
            
            $safe_msg = addslashes($msg);
            echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=categoryList';</script>";
        }
    }

    // --- PRODUCT MANAGEMENT (Quản lý Sản phẩm) ---

    function productList() {
        $products = $this->productModel->getAllProductsAdmin();
        // Đảm bảo biến $categories được truyền đi
        $categories = $this->productModel->getAllCategories();
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
        $priceSale = $_POST['price_sale'] ?? 0; // Lấy giá sale
        $image = $_POST['image']; 
        $description = $_POST['description'];
        $material = $_POST['material'];
        $brand = $_POST['brand'];
        $skuCode = $_POST['sku_code'];

        $result = false;
        // Mặc định là thông báo thất bại
        $msg = 'LỖI: Thao tác thất bại, vui lòng kiểm tra kết nối Database hoặc dữ liệu nhập.'; 

        if ($id > 0) {
            // Chế độ Sửa (10 tham số)
            $result = $this->productModel->updateProduct($id, $categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode);
            if ($result) { $msg = 'Cập nhật sản phẩm thành công!'; }
        } else {
            // Chế độ Thêm mới (9 tham số - ĐÃ SỬA ĐỂ TRUYỀN $priceSale)
            $result = $this->productModel->insertProduct($categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode);
            if ($result) { $msg = 'Thêm sản phẩm mới thành công!'; }
        }

        $safe_msg = addslashes($msg);
        echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=productList';</script>";
    }

    function productDelete() {
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $result = $this->productModel->deleteProduct($id);
            
            // KIỂM TRA KẾT QUẢ ĐỂ BÁO LỖI RÕ RÀNG HƠN
            $msg = $result ? 'Đã xóa sản phẩm thành công!' : 'LỖI: Không thể xóa sản phẩm khỏi Database (có thể do ràng buộc khóa ngoại).';
            
            $safe_msg = addslashes($msg);
            echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=productList';</script>";
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
            
            // Lấy dữ liệu từ Model. Các biến này sẽ CÓ SẴN trong View.
            $order = $this->productModel->getOrderById($orderId);
            $orderDetails = $this->productModel->getOrderDetails($orderId);
            
            // KIỂM TRA AN TOÀN: Nếu không tìm thấy đơn hàng, chuyển hướng
            if (!$order) {
                echo "<script>alert('LỖI: Không tìm thấy đơn hàng này!'); window.location='?ctrl=admin&act=orderList';</script>";
                exit;
            }
            
            // NOTE: KHÔNG tạo biến $data ở đây nếu View không dùng nó. 
            // Ta sẽ sử dụng trực tiếp $order và $orderDetails trong View.
            
            // Xử lý cập nhật trạng thái nếu có POST (sử dụng $orderId)
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

    function confirmPayment() {
        if (isset($_GET['id'])) {
            $orderId = $_GET['id'];
            $result = $this->productModel->updatePaymentStatus($orderId, 1); // 1 = Đã thanh toán

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