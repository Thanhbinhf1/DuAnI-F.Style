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
        $titleMain = "DANH MỤC SẢN PHẨM"; // Phần chữ to bên trái
        $titleSub = ""; // Phần chữ đỏ bên phải (sẽ thay đổi)

        // 1. Kiểm tra xem người dùng muốn xem gì
        if (isset($_GET['cat'])) {
            $id = $_GET['cat'];
            $products = $this->model->getProductsByCategory($id);
            // Lấy tên danh mục từ Database
            $titleSub = $this->model->getCategoryName($id); 
        } 
        else if (isset($_GET['type']) && $_GET['type'] == 'sale') {
            $products = $this->model->getSaleProducts();
            $titleSub = "Săn Sale Giá Sốc"; // Chữ hiện ra khi bấm Sale
        }
        else if (isset($_GET['keyword'])) {
             $products = $this->model->getAllProductsList(); // (Logic tìm kiếm làm sau)
             $titleSub = "Tìm kiếm: " . $_GET['keyword'];
        }
        else {
            $products = $this->model->getAllProductsList();
            $titleSub = "Tất cả sản phẩm";
        }

        // Gửi 2 biến tiêu đề sang View
        include_once 'Views/users/product_list.php';
    }
}
?>