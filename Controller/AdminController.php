<?php 
// Controller/AdminController.php

// 1. CHỈ LOAD 1 MODEL DUY NHẤT
include_once 'Models/AdminModel.php'; 
include_once 'csrf.php'; 

class AdminController {
    private $model; 
    
    function __construct() {
        // KIỂM TRA BẢO MẬT: Phải đăng nhập và có role = 1 (Admin)
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
            echo "<script>alert('Bạn không có quyền truy cập trang quản trị!'); window.location='index.php?ctrl=user&act=login';</script>";
            exit();
        }
        
        // Khởi tạo AdminModel (Chứa tất cả chức năng)
        $this->model = new AdminModel(); 
    }

    // --- DASHBOARD ---
    function dashboard() {
        $stats = $this->model->getDashboardStats(); 
        $chartData = $this->model->getMonthlyIncome(); 
        $recent_activities = $this->model->getRecentActivityOrders();
        
        include_once 'Views/admin/dashboard.php';
    }

    // =========================================================================
    // QUẢN LÝ NGƯỜI DÙNG (USER)
    // =========================================================================

    function userList() {
        $users = $this->model->getAllUsers(); 
        include_once 'Views/admin/user_list.php';
    }

    function userDetail() {
        if (isset($_GET['id'])) {
            $userId = $_GET['id'];
            // Lấy thông tin & lịch sử
            $user = $this->model->getUserInfo($userId);
            $orders = $this->model->getUserHistory($userId);
            
            if (!$user) {
                $this->redirectWithAlert('Người dùng không tồn tại!', '?ctrl=admin&act=userList');
            }

            include_once 'Views/admin/user_detail.php';
        } else {
            $this->redirectWithAlert('', '?ctrl=admin&act=userList');
        }
    }
    
    function userDelete() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrf();
            $id = $_POST['id'];
            $msg = 'Đã xóa người dùng thành công!';

            if ($id != $_SESSION['user']['id']) { 
                $result = $this->model->deleteUser($id);
                if (!$result) $msg = 'LỖI: Không thể xóa người dùng khỏi Database.';
            } else {
                $msg = 'Không thể xóa tài khoản của chính bạn!';
            }
            
            $this->redirectWithAlert($msg, '?ctrl=admin&act=userList');
        }
    }

    function userUpdateRole() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrf();
            $id = $_POST['user_id'];
            $role = $_POST['role'];
            $msg = 'Cập nhật vai trò thành công!';

            if ($id != $_SESSION['user']['id']) { 
                $result = $this->model->updateUserRole($id, $role);
                if (!$result) $msg = 'LỖI: Không thể cập nhật vai trò.';
            } else {
                $msg = 'Bạn không thể thay đổi vai trò của chính mình!';
            }
            
            $this->redirectWithAlert($msg, '?ctrl=admin&act=userList');
        }
    }

    // =========================================================================
    // QUẢN LÝ DANH MỤC (CATEGORY)
    // =========================================================================

    function categoryList() {
        $categories = $this->model->getAllCategories();
        include_once 'Views/admin/category_list.php';
    }
    
    function categoryForm() {
        $category = null;
        if (isset($_GET['id'])) {
            $category = $this->model->getCategoryById($_GET['id']);
        }
        include_once 'Views/admin/category_form.php';
    }
    
function categoryPost() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrf();
            $id = $_POST['id'] ?? 0;
            $name = trim($_POST['name']); // Xóa khoảng trắng thừa
            $status = $_POST['status'];

            // --- 1. KIỂM TRA TÊN RỖNG ---
            if (empty($name)) {
                $this->redirectWithAlert('LỖI: Tên danh mục không được để trống.');
                return;
            }

            // --- 2. KIỂM TRA TRÙNG TÊN (Server-side) ---
            if ($this->model->checkCategoryNameExist($name, $id)) {
                $this->redirectWithAlert("LỖI: Tên danh mục '$name' đã tồn tại. Vui lòng chọn tên khác!");
                return;
            }

            // --- 3. LƯU DỮ LIỆU ---
            $result = false;
            $msg = 'LỖI: Thao tác thất bại.';

            if ($id > 0) {
                $result = $this->model->updateCategory($id, $name, $status);
                if ($result) $msg = 'Cập nhật danh mục thành công!';
            } else {
                $result = $this->model->insertCategory($name, $status);
                if ($result) $msg = 'Thêm danh mục mới thành công!';
            }

            $this->redirectWithAlert($msg, '?ctrl=admin&act=categoryList');
        }
    }
    function categoryToggleStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrf();
            $id = $_POST['id'];
            $currentStatus = (int)$_POST['current_status'];
            $newStatus = $currentStatus == 1 ? 0 : 1;
            
            $result = $this->model->toggleCategoryStatus($id, $newStatus);
            $msg = $result ? "Cập nhật trạng thái thành công!" : 'LỖI: Cập nhật thất bại.';
            
            $this->redirectWithAlert($msg, '?ctrl=admin&act=categoryList');
        }
    }

    // =========================================================================
    // QUẢN LÝ SẢN PHẨM (PRODUCT)
    // =========================================================================

    function productList() {
        $products = $this->model->getAllProductsAdmin();
        $categories = $this->model->getAllCategories(); 
        include_once 'Views/admin/product_list.php';
    }
    
    function productForm() {
        $product = null;
        $galleryImages = [];
        
        if (isset($_GET['id'])) {
            $product = $this->model->getProductById($_GET['id']);
            if ($product) {
                $galleryImages = $this->model->getGalleryImages($_GET['id']);
            }
        }
        $categories = $this->model->getAllCategories();
        include_once 'Views/admin/product_form.php';
    }
    
    function productPost() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrf();
            
            // 1. Lấy dữ liệu
            $id = $_POST['id'] ?? 0;
            $categoryId = $_POST['category_id'];
            $name = $_POST['name'];
            $price = $_POST['price'];
            $priceSale = $_POST['price_sale'] ?? 0;
            $description = $_POST['description'];
            $material = $_POST['material'];
            $brand = $_POST['brand'];
            $skuCode = $_POST['sku_code'];

            // --- [VALIDATION SERVER-SIDE] ---
            // 1.1 Kiểm tra giá
            if ((float)$price <= 0) {
                $this->redirectWithAlert('LỖI: Giá gốc phải lớn hơn 0.');
                return;
            }
            if ((float)$priceSale > 0 && (float)$priceSale >= (float)$price) {
                $this->redirectWithAlert('LỖI: Giá khuyến mãi phải NHỎ HƠN giá gốc.');
                return;
            }

            // 1.2 Kiểm tra trùng tên
            if ($this->model->checkProductNameExist($name, $id)) {
                $this->redirectWithAlert("LỖI: Tên sản phẩm '$name' đã tồn tại. Vui lòng đặt tên khác.");
                return;
            }
            
            // 2. Xử lý ảnh chính
            $image = $_POST['image_current'] ?? ''; 
            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
                $uploadResult = $this->handleUpload($_FILES['image_file']);
                if ($uploadResult['success']) {
                    $image = $uploadResult['path'];
                } else {
                    $this->redirectWithAlert($uploadResult['message']);
                    return;
                }
            }
            
            if ($id == 0 && empty($image)) {
                $this->redirectWithAlert('LỖI: Vui lòng thêm ảnh cho sản phẩm mới.');
                return;
            }

            // 3. Insert/Update
            $result = false;
            $productId = $id;

            if ($id > 0) {
                $result = $this->model->updateProduct($id, $categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode);
                $msg = 'Cập nhật sản phẩm thành công!';
            } else {
                $newId = $this->model->insertProduct($categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode);
                if ($newId) {
                    $result = true;
                    $productId = $newId;
                    $msg = 'Thêm sản phẩm mới thành công!';
                }
            }

            // 4. Xử lý Gallery ảnh
            if ($result && isset($_FILES['gallery_files'])) {
                if (count($_FILES['gallery_files']['name']) > 0 && !empty($_FILES['gallery_files']['name'][0])) {
                    $this->model->deleteGalleryImages($productId); // Xóa cũ
                    
                    $galleryPaths = [];
                    $files = $_FILES['gallery_files'];
                    $count = count($files['name']);
                    
                    for ($i = 0; $i < $count; $i++) {
                        if ($files['error'][$i] == 0) {
                            $singleFile = [
                                'name' => $files['name'][$i],
                                'type' => $files['type'][$i],
                                'tmp_name' => $files['tmp_name'][$i],
                                'error' => $files['error'][$i],
                                'size' => $files['size'][$i]
                            ];
                            $upRes = $this->handleUpload($singleFile);
                            if ($upRes['success']) {
                                $galleryPaths[] = $upRes['path'];
                            }
                        }
                    }
                    if (!empty($galleryPaths)) {
                        $this->model->insertGalleryImages($productId, $galleryPaths);
                    }
                }
            }

            $msg = $result ? $msg : 'LỖI: Thao tác thất bại.';
            $this->redirectWithAlert($msg, '?ctrl=admin&act=productList');
        }
    }

    function productToggleStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrf();
            $id = $_POST['id'];
            $currentStatus = (int)$_POST['current_status'];
            $newStatus = $currentStatus == 1 ? 0 : 1;
            
            $result = $this->model->toggleProductStatus($id, $newStatus);
            $msg = $result ? "Cập nhật trạng thái thành công!" : 'LỖI: Cập nhật thất bại.';
            
            $this->redirectWithAlert($msg, '?ctrl=admin&act=productList');
        }
    }
    
    // =========================================================================
    // QUẢN LÝ ĐƠN HÀNG (ORDER)
    // =========================================================================

    function orderList() {
        $orders = $this->model->getAllOrders();
        include_once 'Views/admin/order_list.php';
    }

    function orderCancelledList() {
        $orders = $this->model->getCancelledOrders();
        include_once 'Views/admin/order_cancelled.php';
    }
    
    function orderDetail() {
        if (isset($_GET['id'])) {
            $orderId = $_GET['id'];
            $order = $this->model->getOrderById($orderId);
            $orderDetails = $this->model->getOrderDetails($orderId);
            
            if (!$order) {
                $this->redirectWithAlert('LỖI: Không tìm thấy đơn hàng!', '?ctrl=admin&act=orderList');
                exit;
            }
            
            include_once 'Views/admin/order_detail.php';
        } else {
            $this->redirectWithAlert('', '?ctrl=admin&act=orderList');
        }
    }

    function orderUpdateStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_status'])) {
            $this->verifyCsrf();
            
            $orderId = $_POST['id'];
            $newStatus = (int)$_POST['new_status'];
            
            $order = $this->model->getOrderById($orderId);
            $currentStatus = (int)$order['status'];
            
            // Logic kiểm tra trạng thái
            $msg = '';
            $isValid = true;

            if ($currentStatus == 3) {
                $isValid = false; $msg = 'LỖI: Đơn hàng đã HỦY, không thể thay đổi.';
            } elseif ($currentStatus == 2 && $newStatus != 2) {
                $isValid = false; $msg = 'LỖI: Đơn hàng đã HOÀN TẤT, không thể đổi lại.';
            } elseif ($currentStatus == 1 && $newStatus == 0) {
                $isValid = false; $msg = 'LỖI: Đơn đang giao không thể quay lại chờ xác nhận.';
            }
            
            if ($isValid) {
                $result = $this->model->updateOrderStatus($orderId, $newStatus);
                $msg = $result ? 'Cập nhật trạng thái thành công!' : 'LỖI: Cập nhật thất bại.';
            }

            // Redirect về đúng trang đã gọi (list hoặc detail)
            $redirectUrl = "?ctrl=admin&act=orderList"; // Mặc định về list
            if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'orderDetail') !== false) {
                 $redirectUrl = "?ctrl=admin&act=orderDetail&id=$orderId";
            }

            $this->redirectWithAlert($msg, $redirectUrl);
        }
    }

    function confirmPayment() {
        if (isset($_GET['id'])) {
            $orderId = $_GET['id'];
            $result = $this->model->updatePaymentStatus($orderId, 1);
            $msg = $result ? 'Xác nhận thanh toán thành công!' : 'LỖI: Thất bại.';
            
            $this->redirectWithAlert($msg, "?ctrl=admin&act=orderDetail&id=$orderId");
        }
    }
    
    function statistics() {
        $stats = $this->model->getSaleStatistics();
        $stats['top_selling'] = $this->model->getTopSellingProducts(10);
        include_once 'Views/admin/statistics.php';
    }

    // =========================================================================
    // HÀM BỔ TRỢ (HELPERS)
    // =========================================================================

    private function verifyCsrf() {
        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            die('Invalid CSRF token');
        }
    }

    private function redirectWithAlert($msg, $url = null) {
        $safe_msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
        $script = "<script>";
        if ($msg) $script .= "alert('$safe_msg');";
        if ($url) {
            $script .= " window.location='$url';";
        } else {
            $script .= " history.back();";
        }
        $script .= "</script>";
        echo $script;
        exit;
    }

    private function handleUpload($file) {
        $target_dir = "Public/Uploads/Products/";
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

        $allowed_types = ['jpg', 'png', 'jpeg', 'gif', 'webp'];
        $filename = $file["name"];
        $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_types)) {
            return ['success' => false, 'message' => 'LỖI: Định dạng ảnh không hợp lệ.'];
        }

        if (getimagesize($file["tmp_name"]) === false) {
            return ['success' => false, 'message' => 'LỖI: File không phải là ảnh hợp lệ.'];
        }

        $new_filename = uniqid('prod_', true) . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return ['success' => true, 'path' => $target_file];
        } else {
            return ['success' => false, 'message' => 'LỖI: Không thể lưu file.'];
        }
        
    }
    
}
?>