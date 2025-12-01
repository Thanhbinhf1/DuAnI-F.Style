<?php 
session_start();
ob_start();

include_once './Models/Database.php';
include_once './csrf.php';

// Phân luồng route cho admin và user
if (isset($_GET['ctrl']) && $_GET['ctrl'] == 'admin') {
    // ---- ADMIN ROUTE ----
    include_once './Views/admin/layout_header.php';

    // Điều hướng đến controller và action của admin
    if (isset($_GET['act'])) {
        include_once './Controller/AdminController.php';
        $ctrl = new AdminController();
        $act = $_GET['act'];
        $ctrl->$act();
    } else {
        // Mặc định, vào trang dashboard
        include_once './Controller/AdminController.php';
        $ctrl = new AdminController();
        $ctrl->dashboard();
    }

    include_once './Views/admin/layout_footer.php';

} else {
    // ---- USER ROUTE (Giữ nguyên logic cũ) ----
    include_once './Views/users/layout_header.php';

    if (isset($_GET['ctrl']) && isset($_GET['act'])) {
        include_once './Controller/' . ucwords($_GET['ctrl']) . 'Controller.php';

        $crl = new (ucwords($_GET['ctrl']) . 'Controller')();
        $act = $_GET['act'];
        $crl->$act();

    } else {
        include_once './Controller/PageController.php';
        $crl = new PageController();
        $crl->home();
    }

    include_once './Views/users/layout_footer.php';
}

ob_end_flush();