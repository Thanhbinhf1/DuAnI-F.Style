<?php 
include_once 'Models/Product.php';

class PageController {
    function home() {
        $productModel = new Product();
        
        // Lấy 3 danh sách sản phẩm khác nhau
        $spMoi = $productModel->getNewProducts();
        $spHot = $productModel->getHotProducts();
        $spGiaTot = $productModel->getSaleProducts();
        
        // Gửi tất cả sang View
        include_once 'Views/users/Page_home.php';
    }
}
?>