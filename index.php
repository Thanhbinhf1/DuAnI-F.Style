<?php
session_start();
<<<<<<< HEAD
ob_start(); // Bật bộ đệm đầu ra
=======
ob_start();

// BASE_URL: Thư mục gốc của ứng dụng
define('BASE_URL', '/DuAnI-F.Style/');
>>>>>>> main

include_once './Models/Database.php';
include_once './csrf.php';

<<<<<<< HEAD
// Xác định controller và action
// Nếu không có ctrl, mặc định là 'page'. Nếu không có act, mặc định là 'home'
=======
// Xác định controller & action
>>>>>>> main
$ctrl = isset($_GET['ctrl']) ? strtolower($_GET['ctrl']) : 'page';
$act  = $_GET['act'] ?? 'home';

$is_admin = ($ctrl === 'admin');

try {
<<<<<<< HEAD
    // 1. Include Layout Header (Trừ trang Admin)
    if ($ctrl !== 'admin') {
        include_once './Views/users/layout_header.php';
    }

    // 2. Xử lý logic chọn Controller
    if ($ctrl === 'admin') {
        // --- LOGIC ADMIN ---
        $adminFile = './Controller/AdminController.php';
        if (file_exists($adminFile)) {
            include_once $adminFile;
            if (class_exists('AdminController')) {
                $controller = new AdminController();
                // Admin Header thường load sau khi khởi tạo controller (để check login)
                include_once './Views/admin/layout_header.php';
            } else {
                throw new Exception('Không tìm thấy class AdminController');
            }
        } else {
            throw new Exception('Không tìm thấy file AdminController.php');
=======
    // Chọn controller
    if ($is_admin) {
        $ctrlFile = './Controller/AdminController.php';
        if (!file_exists($ctrlFile)) {
            throw new Exception('Không tìm thấy AdminController.php');
>>>>>>> main
        }
        include_once $ctrlFile;
        if (!class_exists('AdminController')) {
            throw new Exception('Không tìm thấy class AdminController');
        }
        $controller = new AdminController();
    } else {
<<<<<<< HEAD
        // --- LOGIC USER (Page, Product, Cart...) ---
        // Viết hoa chữ cái đầu: product -> ProductController
        $fileCtrl = './Controller/' . ucfirst($ctrl) . 'Controller.php';
        
        if (!file_exists($fileCtrl)) {
            // Nếu Controller không tồn tại -> Chuyển về PageController (Trang chủ)
=======
        $ctrlFile = './Controller/' . ucfirst($ctrl) . 'Controller.php';
        if (!file_exists($ctrlFile)) {
            // Sai ctrl -> về trang chủ
>>>>>>> main
            include_once './Controller/PageController.php';
            $controller = new PageController();
            $act = 'home'; 
        } else {
            include_once $ctrlFile;
            $className = ucfirst($ctrl) . 'Controller';
            
            if (!class_exists($className)) {
                throw new Exception('Không tìm thấy class ' . $className);
            }
            $controller = new $className();
        }
    }

<<<<<<< HEAD
    // 3. Gọi Action (Hàm)
=======
    // Load layout tương ứng
    if ($is_admin) {
        include_once './Views/admin/layout_header.php';
    } else {
        include_once './Views/users/layout_header.php';
    }

    // Gọi action
>>>>>>> main
    if (!method_exists($controller, $act)) {
        // Nếu action không tồn tại, thử về action 'home' của controller đó
        if (method_exists($controller, 'home')) {
            $act = 'home';
        } else {
            throw new Exception("Không tìm thấy action '$act' trong controller");
        }
    }
<<<<<<< HEAD

    // Chạy hành động
    $controller->$act();

    // 4. Include Footer (Chỉ hiện cho User)
    if ($ctrl !== 'admin') {
        include_once './Views/users/layout_footer.php';
    }

} catch (Throwable $e) {
    // --- ĐÂY LÀ PHẦN CATCH BẠN BỊ THIẾU ---
    // Phần này bắt lỗi và hiện ra màn hình thay vì làm sập web
    echo "<div style='color:red; background:#ffe6e6; padding:20px; text-align:center; border:1px solid red; margin:20px;'>";
    echo "<h3>Đã xảy ra lỗi hệ thống:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
=======
    $controller->$act();

    // Footer
    if ($is_admin) {
        include_once './Views/admin/layout_footer.php';
    } else {
        include_once './Views/users/layout_footer.php';
    }

} catch (Exception $e) {
    echo '<div style="max-width:800px;margin:40px auto;padding:16px;
                     border:1px solid #eee;border-radius:8px;background:#fff3f3;color:#c00;">
            <h3>Có lỗi xảy ra</h3>
            <p>' . htmlspecialchars($e->getMessage()) . '</p>
          </div>';
>>>>>>> main
}

ob_end_flush(); // Kết thúc bộ đệm
?>