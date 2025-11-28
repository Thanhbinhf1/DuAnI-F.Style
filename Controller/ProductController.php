<?php
include_once 'Models/Product.php';

class ProductController {
    private $model;

    function __construct() {
        $this->model = new Product();
    }

    function detail() {
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            header("Location: index.php"); exit();
        }

        // 1. Lấy thông tin sản phẩm cha
        $sp = $this->model->getProductById($id);
        
        // 2. Lấy danh sách biến thể (Màu/Size)
        $variants = $this->model->getProductVariants($id);

        // 3. Lấy sản phẩm liên quan
        $spLienQuan = $this->model->getRelatedProducts($sp['category_id'], $id);

        include_once 'Views/users/product_detail.php';
    }
    
    // Hàm hiển thị danh sách tất cả sản phẩm (làm sau)
    function list() {
        $titleMain = "DANH MỤC SẢN PHẨM"; 
        $titleSub = "";

        if (isset($_GET['cat'])) {
            $id = $_GET['cat'];
            $products = $this->model->getProductsByCategory($id);
            $titleSub = $this->model->getCategoryName($id); 
        } 
        else if (isset($_GET['type']) && $_GET['type'] == 'sale') {
            $products = $this->model->getSaleProducts();
            $titleSub = "Săn Sale Giá Sốc";
        }
        // --- CẬP NHẬT ĐOẠN TÌM KIẾM NÀY ---
        else if (isset($_GET['keyword'])) {
         
         
       
         $key = $_GET['keyword'];
         $products = $this->model->searchProducts($key);
         $titleSub = "Tìm kiếm: " . $key;
    }
        // -----------------------------------
        else {
            $products = $this->model->getAllProductsList();
            $titleSub = "Tất cả sản phẩm";
        }

        include_once 'Views/users/product_list.php';
    }
}
?>