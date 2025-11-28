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
        echo "Trang danh sách sản phẩm đang xây dựng...";
    }
}
?>