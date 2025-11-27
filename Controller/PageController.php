<?php 
include_once 'Models/Product.php';

class PageController {
    function home() {
        $productModel = new Product();
        $dsSanPham = $productModel->getNewProducts(); 
        
        include_once 'Views/users/page_home.php';
    }
}
?>