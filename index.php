<?php 
include_once './Models/database.php';
$db = new Database();

include_once './Views/layout_header.php';

//điều hướng đến các Controller
if (isset($_GET['ctrl'])&& isset($_GET['act'])) {
    // kiểm tra xem có tham số crl và act không
    include_once './Controller/' . ucwords($_GET['ctrl']) . 'Controller.php';

    $crl = new (ucwords($_GET['ctrl']) . 'controller')();
    $act = $_GET['act'];
    $crl->$act();

    
}else {

include_once './Controller/PageController.php';

$crl = new PageController();
$crl->home();
}

include_once './Views/layout_footer.php';