
<?php 
session_start();
ob_start();

include_once './Models/Database.php';
include_once './csrf.php';

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

include_once './Views/users/layout_footer.php';
ob_end_flush();
