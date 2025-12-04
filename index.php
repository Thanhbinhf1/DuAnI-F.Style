<?php
session_start();
ob_start(); // Bật bộ đệm đầu ra

define('BASE_URL', '/DuAnI-F.Style/');

// Include các file cấu hình quan trọng
include_once './Models/Database.php';
include_once './csrf.php';

// 1. Xác định Controller và Action
$ctrl = isset($_GET['ctrl']) ? strtolower($_GET['ctrl']) : 'page';
$act  = $_GET['act'] ?? 'home';

// Biến cờ xác định đây là trang Admin hay User
$is_admin = ($ctrl === 'admin');

try {
    $controller = null;

    // 2. Routing - Chọn Controller
    if ($is_admin) {
        // --- LOGIC ADMIN ---
        $ctrlFile = './Controller/AdminController.php';
        $className = 'AdminController';
        
        if (!file_exists($ctrlFile)) {
            throw new Exception('File AdminController.php không tồn tại.');
        }
        
        include_once $ctrlFile;
        
        if (!class_exists($className)) {
            throw new Exception('Class AdminController không tồn tại.');
        }
        
        $controller = new $className();

    } else {
        // --- LOGIC USER ---
        // Viết hoa chữ cái đầu: product -> ProductController
        $ctrlFile = './Controller/' . ucfirst($ctrl) . 'Controller.php';
        $className = ucfirst($ctrl) . 'Controller';

        // Nếu Controller không tồn tại -> Chuyển về PageController (Trang chủ) hoặc báo lỗi
        if (!file_exists($ctrlFile)) {
            // Option 1: Báo lỗi
            // throw new Exception("Không tìm thấy controller: $ctrl"); 
            
            // Option 2: Fallback về trang chủ (An toàn hơn cho người dùng)
            $ctrlFile = './Controller/PageController.php';
            $className = 'PageController';
            $act = 'home'; 
        }

        include_once $ctrlFile;

        if (!class_exists($className)) {
            throw new Exception("Class $className không tồn tại.");
        }

        $controller = new $className();
    }

    // 3. Kiểm tra Action có tồn tại không
    if (!method_exists($controller, $act)) {
        // Nếu action không có, thử về 'home' hoặc báo lỗi
        if (method_exists($controller, 'home')) {
            $act = 'home';
        } else {
            throw new Exception("Action '$act' không tồn tại trong $className.");
        }
    }

    // 4. Hiển thị Giao diện (Header -> Action -> Footer)
    
    // A. Load Header
    if ($is_admin) {
        include_once './Views/admin/layout_header.php';
    } else {
        include_once './Views/users/layout_header.php';
    }

    // B. Chạy Action (Nội dung chính)
    $controller->$act();

    // C. Load Footer
    if ($is_admin) {
        include_once './Views/admin/layout_footer.php';
    } else {
        include_once './Views/users/layout_footer.php';
    }

} catch (Throwable $e) {
    // Bắt tất cả lỗi (Exception và Error)
    
    // Nếu chưa load header (do lỗi xảy ra sớm), có thể load một layout đơn giản hoặc header ở đây để thông báo lỗi đẹp hơn
    // Tuy nhiên, để đơn giản ta hiển thị thông báo lỗi trực tiếp:
    
    echo "<div style='max-width:800px; margin:50px auto; padding:20px; background:#fff3f3; border:1px solid #ffcccc; color:#cc0000; font-family:sans-serif; border-radius:8px;'>";
    echo "<h3 style='margin-top:0;'>🚫 Đã xảy ra lỗi hệ thống</h3>";
    echo "<p><strong>Chi tiết:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><small>Vui lòng kiểm tra lại đường dẫn hoặc liên hệ quản trị viên.</small></p>";
    echo "<a href='".BASE_URL."' style='text-decoration:none; background:#cc0000; color:#fff; padding:8px 16px; border-radius:4px;'>Về trang chủ</a>";
    echo "</div>";
}

ob_end_flush(); 
?>