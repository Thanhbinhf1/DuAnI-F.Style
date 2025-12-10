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

   // ... (Đoạn đầu giữ nguyên) ...
        
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
            
            // --- XỬ LÝ UPLOAD ẢNH ---
            $image = null;
            if (isset($_FILES['comment_image']) && $_FILES['comment_image']['error'] == 0) {
                // Tạo thư mục nếu chưa có
                $targetDir = "Public/Uploads/Comments/";
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                // Đặt tên file ngẫu nhiên để tránh trùng
                $fileName = time() . "_" . basename($_FILES["comment_image"]["name"]);
                $targetFilePath = $targetDir . $fileName;
                
                // Kiểm tra định dạng ảnh
                $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
                $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'webp');
                
                if (in_array($fileType, $allowTypes)) {
                    if (move_uploaded_file($_FILES["comment_image"]["tmp_name"], $targetFilePath)) {
                        $image = $fileName;
                    }
                }
            }
            // ------------------------

            if ($rating < 1) $rating = 1;
            if ($rating > 5) $rating = 5;

            // Gọi hàm insertComment mới (có thêm tham số $image)
            if ($content !== '' || $image !== null) {
                $this->model->insertComment($userId, $id, $content, $rating, $image);
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

        // 1. Lấy thông tin sản phẩm chính
        $sp = $this->model->getProductById($id);
        if (!$sp) {
            header("Location: index.php");
            exit;
        }
        $this->_enrichProductsWithFavorites($sp, $favoriteIds);

        // Tăng lượt xem
        $this->model->increaseView($id);

        // 2. Lấy biến thể (Màu/Size)
        $variants = $this->model->getProductVariants($id);
        
        // 3. Lấy ảnh bộ sưu tập (Gallery)
        if (method_exists($this->model, 'getProductGallery')) {
            $gallery = $this->model->getProductGallery($id);
        } else {
            $gallery = []; 
        }

        // 4. Lấy sản phẩm liên quan
        $spLienQuan = $this->model->getRelatedProducts($sp['category_id'], $id);
        $this->_enrichProductsWithFavorites($spLienQuan, $favoriteIds);

        // 5. Lấy bình luận và đánh giá
        $comments = $this->model->getCommentsByProduct($id);
        $averageRating = $this->model->getAverageRating($id);

        include_once 'Views/users/product_detail.php';
    }
function index() { 
        // ... Load các model cũ ...
        include_once 'Models/Product.php';
        
        // THÊM ĐOẠN NÀY ĐỂ GỌI BANNER
        include_once 'Models/Banner.php'; 
        $bannerModel = new Banner();
        $banners = $bannerModel->getActiveBanners(); 
        // -----------------------------

        $productModel = new Product();
        $spHot = $productModel->getHotProducts();
        $spMoi = $productModel->getNewProducts();
        $spGiaTot = $productModel->getSaleProducts();
        
        include_once 'Views/users/Page_home.php';
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

    // --- HÀM MỚI THÊM: Xử lý đánh giá nhiều sản phẩm từ trang hóa đơn ---
    // Hàm này phải nằm TRONG class ProductController (trước dấu đóng } cuối cùng)
// Xử lý đánh giá nhiều sản phẩm từ trang hóa đơn (KHÔNG CẦN ẢNH)
    function submitMultiReviews() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id'];
            $orderId = $_POST['order_id'] ?? 0;
            $reviews = $_POST['reviews'] ?? []; // Mảng chứa rating và content

            foreach ($reviews as $productId => $data) {
                $rating = (int)$data['rating'];
                $content = trim($data['content']);

                // Chỉ lưu nếu có nội dung hoặc rating hợp lệ
                if ($rating > 0 || !empty($content)) {
                    // Gọi hàm insertComment (Model có thể nhận 4 hoặc 5 tham số đều được)
                    // Tham số thứ 5 là ảnh, ta không truyền gì thì nó sẽ là null
                    $this->model->insertComment($userId, $productId, $content, $rating);
                }
            }
            
            echo "<script>alert('Cảm ơn bạn đã đánh giá!'); window.location='?ctrl=order&act=detail&id=$orderId';</script>";
        } else {
            header("Location: index.php");
        }
    }
}
?>