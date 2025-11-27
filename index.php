<?php 
session_start();

include_once './Models/Database.php';

include_once './Views/users/layout_header.php';

if (isset($_GET['ctrl'])&& isset($_GET['act'])) {
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