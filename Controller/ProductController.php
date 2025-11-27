<?php
include_once 'Models/Product.php';

class ProductController {
    private $model;

    function __construct() {
        $this->model = new Product();
    }

    function detail() {
        // 1. Lấy ID từ trên thanh địa chỉ (ví dụ: ?ctrl=product&act=detail&id=5)
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            // Nếu không có ID thì đá về trang chủ
            header("Location: index.php"); 
            exit();
        }

        // 2. Gọi Model để lấy thông tin chi tiết
        $sp = $this->model->getProductById($id);

        if(!$sp) {
            echo "<h3>Sản phẩm không tồn tại!</h3>";
            return;
        }
        
        // 3. (Mở rộng) Lấy thêm các sản phẩm liên quan
        $spLienQuan = $this->model->getRelatedProducts($sp['category_id'], $id);

        // 4. Gọi View để hiển thị
        include_once 'Views/users/product_detail.php';
    }
    
    // Hàm hiển thị danh sách tất cả sản phẩm (làm sau)
    function list() {
        echo "Trang danh sách sản phẩm đang xây dựng...";
    }
}
?>