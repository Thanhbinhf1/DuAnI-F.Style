<?php 
include_once 'Models/Product.php';
include_once 'Models/Contact.php';

class PageController {
    private $productModel;
    private $contactModel;

    public function __construct() {
        $this->productModel  = new Product();
        $this->contactModel  = new Contact();
    }

    // Trang chủ
    public function home() {
        $spMoi    = $this->productModel->getNewProducts();
        $spHot    = $this->productModel->getHotProducts();
        $spGiaTot = $this->productModel->getSaleProducts();

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
