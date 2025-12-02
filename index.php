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

$crl = new PageController();
$crl->home();

include_once './Views/users/layout_footer.php';
}