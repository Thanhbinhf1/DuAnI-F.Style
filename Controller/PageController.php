<?php 
include_once 'Models/Product.php';
include_once 'Models/Contact.php';
include_once 'Models/Favorite.php';

class PageController {
    private $productModel;
    private $contactModel;

    public function __construct() {
        $this->productModel  = new Product();
        $this->contactModel  = new Contact();
    }

    /**
     * Helper function to enrich products with favorite status.
     * @param array $products The list of products to enrich.
     * @param array $favoriteIds The list of favorite product IDs for the current user.
     */
    private function _enrichProductsWithFavorites(&$products, $favoriteIds) {
        if (empty($products)) {
            // Gán is_favorited = false cho tất cả nếu không có ds yêu thích
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
        // Lấy danh sách ID sản phẩm yêu thích của user (nếu đã đăng nhập)
        $favModel = new Favorite();
        $favoriteIds = [];
        if (isset($_SESSION['user']['id'])) {
            $favoriteIds = $favModel->getFavoriteProductIds($_SESSION['user']['id']);
        }

        // Lấy danh sách sản phẩm
        $spMoi    = $this->productModel->getNewProducts();
        $spHot    = $this->productModel->getHotProducts();
        $spGiaTot = $this->productModel->getSaleProducts();

        // Gán trạng thái is_favorited cho từng sản phẩm
        $this->_enrichProductsWithFavorites($spMoi, $favoriteIds);
        $this->_enrichProductsWithFavorites($spHot, $favoriteIds);
        $this->_enrichProductsWithFavorites($spGiaTot, $favoriteIds);

        include_once 'Views/users/Page_home.php';
    }

    // Trang giới thiệu
    public function about() {
        include_once 'Views/users/Page_about.php';
    }

    // Trang liên hệ (GET: hiển thị form, POST: xử lý gửi)
    public function contact() {
        $errors = [];
        $successMessage = "";
        $old = [
            'name'    => '',
            'email'   => '',
            'phone'   => '',
            'subject' => '',
            'message' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name    = trim($_POST['name'] ?? '');
            $email   = trim($_POST['email'] ?? '');
            $phone   = trim($_POST['phone'] ?? '');
            $subject = trim($_POST['subject'] ?? '');
            $message = trim($_POST['message'] ?? '');

            // Lưu lại dữ liệu cũ để fill lại form nếu lỗi
            $old = compact('name', 'email', 'phone', 'subject', 'message');

            // Validate đơn giản
            if ($name === '') {
                $errors['name'] = 'Vui lòng nhập họ tên.';
            }
            if ($email === '') {
                $errors['email'] = 'Vui lòng nhập email.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email không hợp lệ.';
            }
            if ($message === '') {
                $errors['message'] = 'Vui lòng nhập nội dung liên hệ.';
            }

            // Nếu không có lỗi -> lưu DB
            if (empty($errors)) {
                $this->contactModel->create($name, $email, $phone, $subject, $message);
                $successMessage = "Cảm ơn bạn! F.Style đã nhận được thông tin và sẽ phản hồi sớm nhất có thể.";
                // Xóa dữ liệu cũ trên form
                $old = [
                    'name'    => '',
                    'email'   => '',
                    'phone'   => '',
                    'subject' => '',
                    'message' => ''
                ];
            }
        }

        // Gửi biến sang View
        include_once 'Views/users/Page_contact.php';
    }
}
?>
