<?php
session_start();
ob_start();

include_once './Models/Database.php';
include_once './csrf.php';

// Xác định controller & action
$ctrl = isset($_GET['ctrl']) ? strtolower($_GET['ctrl']) : 'page';
$act  = $_GET['act'] ?? 'home';

$is_admin = ($ctrl === 'admin');

try {
    // Chọn controller
    if ($is_admin) {
        $ctrlFile = './Controller/AdminController.php';
        if (!file_exists($ctrlFile)) {
            throw new Exception('Không tìm thấy AdminController.php');
        }
        include_once $ctrlFile;
        if (!class_exists('AdminController')) {
            throw new Exception('Không tìm thấy class AdminController');
        }
        $controller = new AdminController();
    } else {
        $ctrlFile = './Controller/' . ucfirst($ctrl) . 'Controller.php';
        if (!file_exists($ctrlFile)) {
            // Sai ctrl -> về trang chủ
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

    // Load layout tương ứng
    if ($is_admin) {
        include_once './Views/admin/layout_header.php';
    } else {
        include_once './Views/users/layout_header.php';
    }

    // Gọi action
    if (!method_exists($controller, $act)) {
        if (method_exists($controller, 'home')) {
            $act = 'home';
        } else {
            throw new Exception('Không tìm thấy action ' . $act);
        }
    }
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
}

ob_end_flush();
