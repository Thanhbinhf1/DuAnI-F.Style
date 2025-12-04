<?php
include_once 'Models/Product.php';
include_once 'Models/Favorite.php';

class ProductController {
    private $model;

    function __construct() {
        $this->model = new Product();
    }

    /**
     * Helper function to enrich products with favorite status.
     * @param array $products The list of products to enrich.
     * @param array $favoriteIds The list of favorite product IDs for the current user.
     */
    private function _enrichProductsWithFavorites(&$products, $favoriteIds) {
        if (empty($products)) return;

        // Ensure it's an array of arrays
        $isSingle = !is_array(reset($products));
        if ($isSingle) {
            $products['is_favorited'] = in_array($products['id'], $favoriteIds);
        } else {
            foreach ($products as &$product) {
                $product['is_favorited'] = in_array($product['id'], $favoriteIds);
            }
            unset($product);
        }
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
            
            if ($rating < 1) $rating = 1;
            if ($rating > 5) $rating = 5;

            if ($content !== '') {
                $this->model->insertComment($userId, $id, $content, $rating);
            }

            header("Location: ?ctrl=product&act=detail&id=" . $id);
            exit;
        }

        // Lấy danh sách ID sản phẩm yêu thích của user
        $favModel = new Favorite();
        $favoriteIds = [];
        if (isset($_SESSION['user']['id'])) {
            $favoriteIds = $favModel->getFavoriteProductIds($_SESSION['user']['id']);
        }

        // Lấy thông tin sản phẩm
        $sp = $this->model->getProductById($id);
        if (!$sp) {
            header("Location: index.php");
            exit;
        }
        $this->_enrichProductsWithFavorites($sp, $favoriteIds);

        $this->model->increaseView($id);

        $variants = $this->model->getProductVariants($id);
        $spLienQuan = $this->model->getRelatedProducts($sp['category_id'], $id);
        $this->_enrichProductsWithFavorites($spLienQuan, $favoriteIds);

        $comments   = $this->model->getCommentsByProduct($id);
        $ratingInfo = $this->model->getAverageRating($id);

        include_once 'Views/users/product_detail.php';
    }

    // Trang danh sách / lọc / tìm kiếm + phân trang
    function list() {
        $titleMain = "DANH MỤC SẢN PHẨM"; 
        $titleSub  = "";

        $cat     = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
        $type    = isset($_GET['type']) ? $_GET['type'] : null;
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

        if ($keyword !== '') {
            $allProducts = $this->model->searchProducts($keyword);
            $titleSub = "Tìm kiếm: " . htmlspecialchars($keyword);
        } elseif ($cat > 0) {
            $allProducts = $this->model->getProductsByCategory($cat);
            $titleSub = $this->model->getCategoryName($cat);
        } elseif ($type === 'sale') {
            $allProducts = $this->model->getSaleProducts(0);
            $titleSub = "Sản phẩm giá tốt";
        } elseif ($type === 'hot') {
            $allProducts = $this->model->getHotProducts(0);
            $titleSub = "Sản phẩm hot";
        } elseif ($type === 'new') {
            $allProducts = $this->model->getNewProducts(0);
            $titleSub = "Hàng mới về";
        } else {
            $allProducts = $this->model->getAllProductsList();
            $titleSub = "Tất cả sản phẩm";
        }

        // Lấy ds ID yêu thích và gán trạng thái
        $favModel = new Favorite();
        $favoriteIds = [];
        if (isset($_SESSION['user']['id'])) {
            $favoriteIds = $favModel->getFavoriteProductIds($_SESSION['user']['id']);
        }
        $this->_enrichProductsWithFavorites($allProducts, $favoriteIds);

        // Phân trang
        $perPage = 12;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $totalItems  = is_array($allProducts) ? count($allProducts) : 0;
        $totalPages  = $totalItems > 0 ? (int)ceil($totalItems / $perPage) : 1;
        if ($page > $totalPages) $page = $totalPages;

        $offset   = ($page - 1) * $perPage;
        $products = array_slice($allProducts, $offset, $perPage);

        include_once 'Views/users/product_list.php';
    }
}
?>
