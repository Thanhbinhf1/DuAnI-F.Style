<?php 
session_start();

include_once './Models/Database.php';
include_once './Views/users/layout_header.php';

if (isset($_GET['ctrl']) && isset($_GET['act'])) {

    // Tạo tên controller đúng chuẩn: CartController
    $class = ucwords($_GET['ctrl']) . 'Controller';
    $file = "./Controller/$class.php";

    // Kiểm tra controller có tồn tại không
    if(file_exists($file)){
        include_once $file;
    } else {
        die("❌ Không tìm thấy controller: <b>$file</b>");
    }

    // Khởi tạo controller
    $crl = new $class();
    $act = $_GET['act'];

    // Kiểm tra method có tồn tại không
    if(method_exists($crl, $act)){
        $crl->$act();
    } else {
        die("❌ Method <b>$act</b> không tồn tại trong <b>$class</b>");
    }

} else {
    include_once './Controller/PageController.php';
    $home = new PageController();
    $home->home();
}

<<<<<<< Updated upstream
include_once './Views/users/layout_footer.php';