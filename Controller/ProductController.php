<?php
include_once 'Models/Product.php';

class ProductController {
    private $model;

    function __construct() {
        $this->model = new Product();
    }

    function detail() {
    if (!isset($_GET['id'])) {
        header("Location: index.php");
        exit();
    }
    $id = (int)$_GET['id'];

    // Nếu submit bình luận
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_content'])) {
        // CSRF
        if (!verify_csrf($_POST['csrf_token'] ?? null)) {
            echo "<script>alert('Phiên làm việc không hợp lệ, vui lòng thử lại.'); window.location='?ctrl=product&act=detail&id={$id}';</script>";
            exit;
        }

        if (!isset($_SESSION['user'])) {
            echo "<script>alert('Bạn cần đăng nhập để bình luận.'); window.location='?ctrl=user&act=login';</script>";
            exit;
        }

        $userId  = $_SESSION['user']['id'];
        $content = trim($_POST['comment_content']);
        $rating  = isset($_POST['rating']) ? (int)$_POST['rating'] : 5;

        if ($content !== '') {
            if ($rating < 1 || $rating > 5) $rating = 5;
            $this->model->insertComment($id, $userId, $content, $rating);
        }

        // tránh F5 gửi lại form
        header("Location: ?ctrl=product&act=detail&id={$id}");
        exit;
    }

    // 1. Thông tin sản phẩm
    $sp = $this->model->getProductById($id);
    if (!$sp) {
        header("Location: index.php");
        exit;
    }

    // 2. Biến thể Màu/Size
    $variants = $this->model->getProductVariants($id);

    // 3. Ảnh gallery
    $gallery = $this->model->getProductImages($id);

    // 4. Sản phẩm liên quan
    $spLienQuan = $this->model->getRelatedProducts($sp['category_id'], $id);

    // 5. Comment + rating
    $comments       = $this->model->getCommentsByProduct($id);
    $averageRating  = $this->model->getAverageRating($id);

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