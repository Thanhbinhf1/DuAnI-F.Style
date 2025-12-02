<?php
session_start();
ob_start(); // Bật bộ đệm để tránh lỗi header already sent

include_once './Models/Database.php';

// Xác định controller và action
$ctrl = isset($_GET['ctrl']) ? strtolower($_GET['ctrl']) : 'page';
$act  = isset($_GET['act'])  ? $_GET['act'] : 'home';

try {
    // Include layout header
    if ($ctrl !== 'admin') {
        include_once './Views/users/layout_header.php';
    }

    // Xử lý controller
    if ($ctrl === 'admin') {
        $adminFile = './Controller/AdminController.php';
        if (file_exists($adminFile)) {
            include_once $adminFile;
            if (class_exists('AdminController')) {
                $controller = new AdminController();
                // Header admin được include SAU khi controller được tạo (để chạy check login)
                include_once './Views/admin/layout_header.php';
            } else {
                throw new Exception('Không tìm thấy class AdminController');
            }
        } else {
            throw new Exception('Không tìm thấy file AdminController.php');
        }
    } else {
        // Các controller thường: page, product, cart, order, user...
        $fileCtrl = './Controller/' . ucfirst($ctrl) . 'Controller.php';
        if (!file_exists($fileCtrl)) {
            // Nếu controller không tồn tại -> về home
            include_once './Controller/PageController.php';
            $controller = new PageController();
            $act = 'home';
        } else {
            include_once $fileCtrl;
            $className = ucfirst($ctrl) . 'Controller';
            if (!class_exists($className)) {
                throw new Exception('Không tìm thấy class ' . $className);
            }
            $controller = new $className();
        }
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

    // Include layout footer
    if ($ctrl === 'admin') {
        include_once './Views/admin/layout_footer.php';
    } else {
        include_once './Views/users/layout_footer.php';
    }
} catch (Exception $e) {
    // Thông báo lỗi đơn giản
    $isUserLayout = ($ctrl !== 'admin');
    if ($isUserLayout) {
        echo '<div style="max-width:800px;margin:40px auto;padding:20px;border:1px solid #eee;border-radius:8px;background:#fff3f3;color:#c00;">';
        echo '<h3>Có lỗi xảy ra!</h3>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '</div>';
    } else {
        echo 'Có lỗi xảy ra: ' . htmlspecialchars($e->getMessage());
    }
}

include_once './Views/users/layout_footer.php';
