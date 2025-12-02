<?php
include_once 'Models/Product.php';

class ProductController {
    private $model;

    function __construct() {
        $this->model = new Product();
    }

    // Trang chi tiết sản phẩm + bình luận
    function detail() {
        if (!isset($_GET['id'])) {
            header("Location: index.php");
            exit();
        }
        $id = (int)$_GET['id'];

        // Nếu submit bình luận
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_content'])) {
            if (!isset($_SESSION['user'])) {
                echo "<script>alert('Bạn cần đăng nhập để bình luận!'); 
                      window.location='?ctrl=user&act=login';</script>";
                exit;
            }

            $userId  = $_SESSION['user']['id'];
            $content = trim($_POST['comment_content']);
            $rating  = isset($_POST['rating']) ? (int)$_POST['rating'] : 5;
            if ($rating < 1 || $rating > 5) $rating = 5;

            if ($content !== '') {
                $this->model->insertComment($userId, $id, $content, $rating);
            }

            // Tránh F5 gửi lại form
            header("Location: ?ctrl=product&act=detail&id=" . $id);
            exit;
        }

        // Lấy thông tin sản phẩm
        $sp = $this->model->getProductById($id);
        if (!$sp) {
            header("Location: index.php");
            exit;
        }

        // Tăng lượt xem để xác định HOT
        $this->model->increaseView($id);

        // Biến thể (Màu/Size)
        $variants = $this->model->getProductVariants($id);

        // Sản phẩm liên quan
        $spLienQuan = $this->model->getRelatedProducts($sp['category_id'], $id);

        // Bình luận + rating
        $comments   = $this->model->getCommentsByProduct($id);
        $ratingInfo = $this->model->getAverageRating($id);

        include_once 'Views/users/product_detail.php';
    }

    // Trang danh sách / lọc / tìm kiếm + phân trang
    function list() {
        $titleMain = "DANH MỤC SẢN PHẨM"; 
        $titleSub  = "";

        $cat     = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
        $type    = isset($_GET['type']) ? $_GET['type'] : null; // new, hot, sale
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

        // 1. Lọc theo keyword (ưu tiên nhất)
        if ($keyword !== '') {
            $allProducts = $this->model->searchProducts($keyword);
            $titleSub = "Tìm kiếm: " . htmlspecialchars($keyword);
        }
        // 2. Lọc theo danh mục
        elseif ($cat > 0) {
            $allProducts = $this->model->getProductsByCategory($cat);
            $titleSub = $this->model->getCategoryName($cat);
        }
        // 3. Theo loại sản phẩm
        elseif ($type === 'sale') {
            $allProducts = $this->model->getSaleProducts(0); // 0 = không giới hạn
            $titleSub = "Sản phẩm giá tốt";
        } elseif ($type === 'hot') {
            $allProducts = $this->model->getHotProducts(0);
            $titleSub = "Sản phẩm hot";
        } elseif ($type === 'new') {
            $allProducts = $this->model->getNewProducts(0);
            $titleSub = "Hàng mới về";
        }
        // 4. Mặc định: tất cả
        else {
            $allProducts = $this->model->getAllProductsList();
            $titleSub = "Tất cả sản phẩm";
        }

        // Phân trang (12 sản phẩm / trang)
        $perPage = 12;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $totalItems  = is_array($allProducts) ? count($allProducts) : 0;
        $totalPages  = $totalItems > 0 ? (int)ceil($totalItems / $perPage) : 1;
        if ($page > $totalPages) $page = $totalPages;

        $offset   = ($page - 1) * $perPage;
        $products = array_slice($allProducts, $offset, $perPage);

        // Truyền tất cả sang View
        include_once 'Views/users/product_list.php';
    }
}
?>
