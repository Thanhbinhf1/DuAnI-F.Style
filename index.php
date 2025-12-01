
<?php 
session_start();

include_once './Models/Database.php';

// 1. Xác định môi trường (Admin hay User)
$is_admin_area = (isset($_GET['ctrl']) && $_GET['ctrl'] == 'admin');

// 2. Load Layout tương ứng
if ($is_admin_area) {
    // Load layout Admin
    include_once './Views/admin/layout_header.php';
} else {
    // Load layout User
    include_once './Views/users/layout_header.php';
}
// 3. Xử lý Controller và Action
if (isset($_GET['ctrl']) && isset($_GET['act'])) {
    $controller_name = ucwords($_GET['ctrl']) . 'Controller';
    $controller_path = './Controller/' . $controller_name . '.php';

    if (file_exists($controller_path)) {
        include_once $controller_path;
        
        $crl = new $controller_name();
        $act = $_GET['act'];
        
        if (method_exists($crl, $act)) {
            $crl->$act();
        } else {
            // Xử lý lỗi 404 cho action
            echo "<div class='container' style='margin-top: 50px;'><h1>404</h1><p>Action <b>$act</b> not found in controller <b>$controller_name</b>!</p></div>"; 
        }
    } else {
        // Xử lý lỗi 404 cho controller
        echo "<div class='container' style='margin-top: 50px;'><h1>404</h1><p>Controller <b>$controller_name</b> not found!</p></div>"; 
    }

} else {
    // Mặc định là trang chủ (chỉ áp dụng cho User side)
    if (!$is_admin_area) {
        include_once './Controller/PageController.php';
        $crl = new PageController();
        $crl->home();
    } else {
        // Trường hợp truy cập admin không có act (mặc định vào dashboard)
        header("Location: ?ctrl=admin&act=dashboard");
        exit();
    }
}

// 4. Load Footer tương ứng
if ($is_admin_area) {
    include_once './Views/admin/layout_footer.php';
} else {
    include_once './Views/users/layout_footer.php';
}
?>