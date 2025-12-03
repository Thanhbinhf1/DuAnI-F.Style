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

 // File: Controller/AdminController.php

// ... (các hàm khác)

// File: Controller/AdminController.php

// ... (các hàm khác)

    function categoryDelete() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
                die('Invalid CSRF token');
            }
            $id = $_POST['id'];
            $msg = '';
            
            // BƯỚC 1: KIỂM TRA RÀNG BUỘC SẢN PHẨM
            // Sử dụng hàm countProductsByCategoryId() vừa thêm
            $productCount = $this->productModel->countProductsByCategoryId($id);
            
            if ($productCount > 0) {
                // Nếu có sản phẩm, KHÔNG cho phép xóa và trả về lỗi
                $msg = 'LỖI: Không thể xóa danh mục này. Hiện còn ' . $productCount . ' sản phẩm đang thuộc danh mục này.';
                $result = false;
            } else {
                // BƯỚC 2: TIẾN HÀNH XÓA (Nếu không có sản phẩm)
                $result = $this->categoryModel->deleteCategory($id);
                $msg = $result ? 'Đã xóa danh mục thành công!' : 'LỖI: Xóa danh mục thất bại. Vui lòng kiểm tra Database.';
            }
            
            $safe_msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
            echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=categoryList';</script>";
        }
    }

// ...

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
    // File: Controller/AdminController.php

// ... (phần code khác)

    function productPost() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
                die('Invalid CSRF token');
            }
            
            // --- Xử lý dữ liệu form (Giữ nguyên) ---
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
            
            // --- KIỂM TRA LOGIC NGHIỆP VỤ (TRÙNG TÊN & GIÁ SALE) ---
            // (Giữ nguyên logic kiểm tra)
            if ($this->productModel->checkProductNameExist($name, $id)) {
                $msg = 'LỖI: Tên sản phẩm đã tồn tại trong hệ thống. Vui lòng chọn tên khác.';
            } elseif ($priceSale > 0 && $priceSale >= $price) {
                $msg = 'LỖI: Giá khuyến mãi phải thấp hơn Giá gốc.';
            }
            
            if (!empty($msg)) {
                $safe_msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
                echo "<script>alert('$safe_msg'); history.back();</script>";
                exit;
            }

            // --- Xử lý upload ẢNH CHÍNH (Giữ nguyên logic) ---
            $image = $_POST['image_current'] ?? ''; 
            // (Logic upload ảnh chính: Cần đảm bảo code của bạn xử lý $image ở đây)
            
            // --- XỬ LÝ UPLOAD ẢNH GALLERY MỚI ---
            $galleryImagesUploaded = [];
            if (isset($_FILES['gallery_files']) && is_array($_FILES['gallery_files']['name'])) {
                $target_dir = "Public/Uploads/Products/";
                $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];

                foreach ($_FILES['gallery_files']['name'] as $index => $filename) {
                    if ($_FILES['gallery_files']['error'][$index] == UPLOAD_ERR_OK) {
                        $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                        if (in_array($file_extension, $allowed_types) && getimagesize($_FILES["gallery_files"]["tmp_name"][$index]) !== false) {
                            $new_filename = uniqid('gallery_', true) . '.' . $file_extension;
                            $target_file = $target_dir . $new_filename;

                            if (move_uploaded_file($_FILES["gallery_files"]["tmp_name"][$index], $target_file)) {
                                $galleryImagesUploaded[] = $target_file;
                            }
                        }
                    }
                }
            }
            
            // --- KIỂM TRA VÀ TƯƠNG TÁC VỚI MODEL ---
            $result = false;
            $final_msg = 'LỖI: Thao tác thất bại.'; 
            $currentProductId = $id;

            if ($id > 0) {
                // Chế độ Sửa
                $result = $this->productModel->updateProduct($id, $categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode);
                $currentProductId = $id;
                if ($result) { $final_msg = 'Cập nhật sản phẩm thành công!'; }
                
            } else {
                // Chế độ Thêm mới
                $result = $this->productModel->insertProduct($categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode);
                $currentProductId = $this->productModel->getLastId(); // Lấy ID vừa tạo
                if ($result) { $final_msg = 'Thêm sản phẩm mới thành công!'; }
            }
            
            // --- LƯU GALLERY CHO SẢN PHẨM ---
            if ($result && $currentProductId) {
                if ($id > 0) {
                    // Nếu là cập nhật, xóa ảnh cũ (nếu có) trước khi thêm ảnh mới
                    $this->productModel->deleteGalleryImages($currentProductId); 
                }
                
                // Thêm các ảnh gallery mới (nếu có ảnh chính, ta có thể thêm ảnh chính vào gallery luôn)
                if (!empty($galleryImagesUploaded)) {
                     $this->productModel->insertGalleryImages($currentProductId, $galleryImagesUploaded);
                }
            }

            $safe_msg = htmlspecialchars($final_msg, ENT_QUOTES, 'UTF-8');
            echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=productList';</script>";
        }
    }

// ... (các hàm khác)
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
    
    // File: Controller/AdminController.php

// ... (các hàm khác)

    function orderDetail() {
        if (isset($_GET['id'])) {
            $orderId = $_GET['id'];
            $order = $this->orderModel->getOrderById($orderId);
            $orderDetails = $this->orderModel->getOrderDetails($orderId);
            
            if (!$order) {
                echo "<script>alert('LỖI: Không tìm thấy đơn hàng này!'); window.location='?ctrl=admin&act=orderList';</script>";
                exit;
            }
            
            // --- Xử lý cập nhật trạng thái nếu có POST ---
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_status'])) {
                if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
                    die('Invalid CSRF token');
                }
                
                $currentStatus = (int)$order['status'];
                $newStatus = (int)$_POST['new_status'];
                $isValidTransition = true;
                $errorMsg = '';
                
                // --- BẮT ĐẦU KIỂM TRA QUY TẮC WORKFLOW ---

                // 1. Nếu đơn đã Hủy (3) hoặc đã Giao (2) thì không thay đổi
                if ($currentStatus >= 2 && $currentStatus == 3) {
                    $isValidTransition = false;
                    $errorMsg = 'LỖI: Đơn hàng đã bị HỦY, không thể thay đổi trạng thái.';
                }
                elseif ($currentStatus == 2 && $newStatus != 2) {
                     $isValidTransition = false;
                    $errorMsg = 'LỖI: Đơn hàng đã HOÀN TẤT, không thể quay lại trạng thái trước.';
                }
                // 2. Chặn chuyển đổi ngược chiều (0 <- 1, 1 <- 2)
                elseif ($newStatus < $currentStatus && $newStatus != 3) { // Cho phép chuyển sang Hủy (3) từ mọi trạng thái
                    $isValidTransition = false;
                    $errorMsg = 'LỖI: Không thể quay lại trạng thái trước (Ví dụ: Đang giao -> Chờ xác nhận).';
                }
                // 3. Chặn bỏ qua bước (0 -> 2)
                elseif ($currentStatus == 0 && $newStatus == 2) {
                    $isValidTransition = false;
                    $errorMsg = 'LỖI: Cần chuyển sang Đang giao (1) trước khi chuyển sang Đã giao (2).';
                }
                
                // --- KẾT THÚC KIỂM TRA ---

                if ($isValidTransition) {
                    $result = $this->orderModel->updateOrderStatus($orderId, $newStatus);
                    $msg = $result ? 'Cập nhật trạng thái đơn hàng thành công!' : 'LỖI: Cập nhật trạng thái thất bại.';
                } else {
                    $msg = $errorMsg;
                    $result = false; // Đánh dấu là lỗi để hiển thị thông báo
                }

                $safe_msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
                
                // Tải lại dữ liệu $order sau khi update (để view hiển thị trạng thái mới)
                $order = $this->orderModel->getOrderById($orderId);
                
                echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=orderDetail&id=$orderId';</script>";
                exit;
            }

            include_once 'Views/admin/order_detail.php';
            
        } else {
            header("Location: ?ctrl=admin&act=orderList");
        }
    }

// ...

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
    // File: Controller/AdminController.php

// ... (sau hàm categoryPost hoặc categoryDelete) ...

    // --- HÀM MỚI: ẨN/HIỆN DANH MỤC ---
    function categoryToggleStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
                die('Invalid CSRF token');
            }
            $id = $_POST['id'];
            $currentStatus = (int)$_POST['current_status'];
            
            // Đảo trạng thái: 1 -> 0, 0 -> 1
            $newStatus = $currentStatus == 1 ? 0 : 1;
            
            $result = $this->categoryModel->toggleCategoryStatus($id, $newStatus);
            
            $statusText = $newStatus == 1 ? 'HIỆN' : 'ẨN';
            $msg = $result ? "Đã chuyển trạng thái danh mục #$id sang $statusText thành công!" : 'LỖI: Không thể cập nhật trạng thái danh mục.';
            
            $safe_msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
            echo "<script>alert('$safe_msg'); window.location='?ctrl=admin&act=categoryList';</script>";
        }
    }

// ...
}
?>