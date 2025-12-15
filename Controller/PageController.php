<?php 
include_once 'Models/Product.php';
include_once 'Models/Contact.php';
include_once 'Models/Favorite.php';
include_once 'Models/Banner.php'; 

// Thêm các thư viện PHPMailer và file config
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// SỬA LỖI ĐƯỜNG DẪN: Sử dụng __DIR__ để định vị file tuyệt đối
$baseDir = __DIR__ . '/../'; // Đi lên 1 cấp từ Controller/ đến thư mục gốc DuAnI-F.Style-Ai-pham/

require_once $baseDir . 'vendor/PHPMailer/src/Exception.php';
require_once $baseDir . 'vendor/PHPMailer/src/PHPMailer.php';
require_once $baseDir . 'vendor/PHPMailer/src/SMTP.php';

// Include config để lấy thông tin email
include_once $baseDir . 'config.php';

class PageController {
    private $productModel;
    private $contactModel;
    private $bannerModel; 

    public function __construct() {
        $this->productModel  = new Product();
        $this->contactModel  = new Contact();
        $this->bannerModel   = new Banner(); 
    }

    /**
     * Helper function to enrich products with favorite status.
     */
    private function _enrichProductsWithFavorites(&$products, $favoriteIds) {
        if (empty($products)) {
            return;
        }
        foreach ($products as &$product) {
            $product['is_favorited'] = in_array($product['id'], $favoriteIds);
        }
        unset($product);
    }

    // Trang chủ
    public function home() {
        $banners = $this->bannerModel->getActiveBanners();

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
                // 1. Lưu vào database
                $this->contactModel->create($name, $email, $phone, $subject, $message);
                
                // --- 2. GỬI EMAIL THÔNG BÁO ---
                $recipientEmail = 'luyenluongpro0@gmail.com'; 
                $shopName = 'F.Style';
                
                $mail = new PHPMailer(true);
                try {
                    // Cấu hình Server (từ config.php)
                    $mail->isSMTP();
                    $mail->Host       = MAIL_HOST; 
                    $mail->SMTPAuth   = true;
                    $mail->Username   = MAIL_USER; 
                    $mail->Password   = MAIL_PASS; 
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = MAIL_PORT; 
                    $mail->CharSet    = 'UTF-8';

                    // Người gửi
                    $mail->setFrom(MAIL_USER, $shopName . ' (Hệ thống)');
                    
                    // Người nhận (email của shop/người quản lý)
                    $mail->addAddress($recipientEmail, 'Quản lý F.Style'); 

                    // Nội dung email
                    $mail->isHTML(true);
                    $mail->Subject = 'PHẢN HỒI LIÊN HỆ MỚI: ' . htmlspecialchars($subject);
                    $mail->Body    = "
                        <h2>Phản hồi liên hệ mới từ khách hàng</h2>
                        <p><b>Họ và tên:</b> " . htmlspecialchars($name) . "</p>
                        <p><b>Email:</b> " . htmlspecialchars($email) . "</p>
                        <p><b>Số điện thoại:</b> " . htmlspecialchars($phone) . "</p>
                        <p><b>Tiêu đề:</b> " . htmlspecialchars($subject) . "</p>
                        <p><b>Nội dung:</b></p>
                        <p style='border: 1px solid #ccc; padding: 10px;'>" . nl2br(htmlspecialchars($message)) . "</p>
                        <p>Vui lòng kiểm tra database và phản hồi lại sớm nhất có thể.</p>
                    ";
                    $mail->AltBody = "Phản hồi liên hệ mới: Tên: $name, Email: $email, SĐT: $phone, Tiêu đề: $subject, Nội dung: $message";

                    $mail->send();
                } catch (Exception $e) {
                    // Xử lý lỗi: Email không gửi được nhưng vẫn lưu vào DB
                }
                // --- KẾT THÚC GỬI EMAIL ---
                
                $successMessage = "Cảm ơn bạn! F.Style đã nhận được thông tin và sẽ phản hồi sớm nhất có thể.";
                $old = ['name' => '', 'email' => '', 'phone' => '', 'subject' => '', 'message' => ''];
            }
        }

        include_once 'Views/users/Page_contact.php';
    }
    

}
?>