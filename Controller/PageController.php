<?php 
include_once 'Models/Product.php';
include_once 'Models/Contact.php';
include_once 'Models/Favorite.php';
include_once 'Models/Banner.php'; // 1. BỔ SUNG MODEL BANNER

class PageController {
    private $productModel;
    private $contactModel;
    private $bannerModel; // 2. KHAI BÁO THUỘC TÍNH

    public function __construct() {
        $this->productModel  = new Product();
        $this->contactModel  = new Contact();
        $this->bannerModel   = new Banner(); // 3. KHỞI TẠO
    }

    /**
     * Helper function to enrich products with favorite status.
     */
    private function _enrichProductsWithFavorites(&$products, $favoriteIds) {
        if (empty($products)) {
            foreach ($products as &$product) {
                $product['is_favorited'] = false;
            }
            unset($product);
            return;
        }
        foreach ($products as &$product) {
            $product['is_favorited'] = in_array($product['id'], $favoriteIds);
        }
        unset($product);
    }

    // Trang chủ
    public function home() {
        // --- 4. LẤY DANH SÁCH BANNER TỪ DATABASE (QUAN TRỌNG NHẤT) ---
        $banners = $this->bannerModel->getActiveBanners();
        // -------------------------------------------------------------

        // Lấy danh sách ID sản phẩm yêu thích của user
        $favModel = new Favorite();
        $favoriteIds = [];
        if (isset($_SESSION['user']['id'])) {
            $favoriteIds = $favModel->getFavoriteProductIds($_SESSION['user']['id']);
        }

        // Lấy danh sách sản phẩm
        $spMoi    = $this->productModel->getNewProducts();
        $spHot    = $this->productModel->getHotProducts();
        $spGiaTot = $this->productModel->getSaleProducts();

        // Gán trạng thái is_favorited
        $this->_enrichProductsWithFavorites($spMoi, $favoriteIds);
        $this->_enrichProductsWithFavorites($spHot, $favoriteIds);
        $this->_enrichProductsWithFavorites($spGiaTot, $favoriteIds);

        include_once 'Views/users/Page_home.php';
    }

    // Trang giới thiệu
    public function about() {
        include_once 'Views/users/Page_about.php';
    }

    // Trang liên hệ
    public function contact() {
        $errors = [];
        $successMessage = "";
        $old = ['name' => '', 'email' => '', 'phone' => '', 'subject' => '', 'message' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name    = trim($_POST['name'] ?? '');
            $email   = trim($_POST['email'] ?? '');
            $phone   = trim($_POST['phone'] ?? '');
            $subject = trim($_POST['subject'] ?? '');
            $message = trim($_POST['message'] ?? '');

            $old = compact('name', 'email', 'phone', 'subject', 'message');

            if ($name === '') $errors['name'] = 'Vui lòng nhập họ tên.';
            if ($email === '') {
                $errors['email'] = 'Vui lòng nhập email.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email không hợp lệ.';
            }
            if ($message === '') $errors['message'] = 'Vui lòng nhập nội dung liên hệ.';

            if (empty($errors)) {
                $this->contactModel->create($name, $email, $phone, $subject, $message);
                $successMessage = "Cảm ơn bạn! F.Style đã nhận được thông tin và sẽ phản hồi sớm nhất có thể.";
                $old = ['name' => '', 'email' => '', 'phone' => '', 'subject' => '', 'message' => ''];
            }
        }

        include_once 'Views/users/Page_contact.php';
    }

}
?>