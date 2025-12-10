<?php 
include_once 'Models/User.php';
include_once 'csrf.php'; 

// *** KHAI BÁO CÁC FILE PHPMailer ***
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// BẮT BUỘC phải require 3 file chính
require_once 'vendor/PHPMailer/src/PHPMailer.php';
require_once 'vendor/PHPMailer/src/SMTP.php';
require_once 'vendor/PHPMailer/src/Exception.php';

// [QUAN TRỌNG] Đảm bảo hằng số BASE_URL đã được định nghĩa ở index.php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/'); 
}

// Hàm gửi email SỬ DỤNG PHPMailer (kết nối trực tiếp SMTP)
function sendEmail_PHPMailer($to, $subject, $body) {
    // TẠO INSTANCE PHPMailer
    $mail = new PHPMailer(true);
    
    try {
        // Cấu hình Server (Gmail)
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.gmail.com';                     
        $mail->SMTPAuth   = true;                                   
        
        // ====================================================
        // THAY THẾ BẰNG THÔNG TIN GMAIL VÀ APP PASSWORD CỦA BẠN
        // ====================================================
        $mail->Username   = 'truongquangquy2512@gmail.com'; // Ví dụ: luyenhuu@gmail.com
        $mail->Password   = 'rvnzachoylhyjsrq';   // Mật khẩu 16 ký tự mà Google đã tạo

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
        $mail->Port       = 587;                                    
        // ====================================================

        // Thiết lập mã hóa Tiếng Việt và Người gửi
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('no-reply@fstyle.com', 'F.Style Store'); // Địa chỉ hiển thị chuyên nghiệp
        
        $mail->isHTML(false); 
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Người nhận (To)
        $mail->addAddress($to);     
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("LỖI GỬI MAIL (PHPMailer): {$mail->ErrorInfo}");
        return false;
    }
}


class UserController {
    private $model;

    function __construct() {
        $this->model = new User();
    }
    
    // ===================================
    //  CHỨC NĂNG: QUÊN MẬT KHẨU (CODE-BASED)
    // ===================================

    // 1. Trang nhập email để lấy lại mật khẩu
    function forgotPassword() {
        $error = $_GET['error'] ?? '';
        $success = '';
        $oldEmail = $_GET['email'] ?? ''; 
        include_once 'Views/users/user_forgot_password.php';
    }

    // 2. Xử lý gửi MÃ XÁC NHẬN (Code)
    function sendResetLink() {
        $email = trim($_POST['email'] ?? '');
        $error = '';
        $success = '';
        $oldEmail = $email;

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Vui lòng nhập Email hợp lệ.';
        } else {
            $user = $this->model->getUserByEmail($email);

            if ($user) {
                // TẠO MÃ XÁC NHẬN NGẪU NHIÊN 6 CHỮ SỐ
                $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT); 
                $expiry = date('Y-m-d H:i:s', time() + 600); // Hết hạn sau 10 phút
                $userId = $user['id'];

                $this->model->setResetCode($userId, $code, $expiry);
                
                // --- Xử lý gửi email bằng PHPMailer ---
                $subject = "Mã xác nhận Đặt lại mật khẩu F.Style: " . $code;
                $body = "Chào " . ($user['fullname'] ?? $user['username']) . ",\n\n"
                      . "Mã xác nhận ĐẶT LẠI MẬT KHẨU của bạn là:\n\n"
                      . "MÃ XÁC NHẬN: " . $code . "\n\n"
                      . "Mã này sẽ hết hạn trong 10 phút. Vui lòng nhập mã này vào trang web để tiếp tục.\n\n"
                      . "Nếu bạn không yêu cầu, vui lòng bỏ qua email này.";
                
                $mailSent = sendEmail_PHPMailer($email, $subject, $body);

                if ($mailSent) {
                    $success = "Hệ thống đã gửi mã xác nhận 6 chữ số đến Email: $email. Vui lòng kiểm tra hộp thư.";
                    echo "<script>alert('{$success}'); window.location='?ctrl=user&act=verifyCodeForm&email=" . urlencode($email) . "';</script>";
                    exit;
                } else {
                    // Xử lý khi gửi mail thất bại (vẫn chuyển sang trang nhập mã nhưng nên log lỗi)
                    $errorMsg = "LỖI: Không thể gửi Email. Vui lòng kiểm tra lại Username/App Password trong file code.";
                    echo "<script>alert('Lỗi gửi mail: Mã xác nhận đã được tạo. Vui lòng thử nhập mã thủ công.'); window.location='?ctrl=user&act=verifyCodeForm&email=" . urlencode($email) . "';</script>";
                    exit;
                }
            } else {
                // Luôn báo thành công để tránh lộ thông tin user
                $success = "Hệ thống đã gửi mã xác nhận 6 chữ số đến Email: $email. Vui lòng kiểm tra hộp thư.";
                echo "<script>alert('{$success}'); window.location='?ctrl=user&act=verifyCodeForm&email=" . urlencode($email) . "';</script>";
                exit;
            }
        }

        include_once 'Views/users/user_forgot_password.php';
    }

    // 3. Trang hiển thị Form nhập Mã xác nhận
    function verifyCodeForm() {
        $email = $_GET['email'] ?? '';
        $error = $_GET['error'] ?? '';
        if (empty($email)) { header("Location: ?ctrl=user&act=forgotPassword"); exit; }
        include_once 'Views/users/user_verify_code.php';
    }

    // 4. Xử lý kiểm tra Mã xác nhận
    function verifyCodePost() {
        $email = trim($_POST['email'] ?? '');
        $code = trim($_POST['code'] ?? '');
        $error = '';

        if (empty($email) || empty($code)) {
            $error = 'Vui lòng nhập Email và Mã xác nhận.';
        } elseif (strlen($code) !== 6 || !is_numeric($code)) {
            $error = 'Mã xác nhận không hợp lệ.';
        } else {
            $user = $this->model->getUserByCodeAndEmail($code, $email);

            if ($user) {
                // Mã chính xác -> Chuyển sang trang đặt mật khẩu mới, mang theo mã
                header("Location: " . BASE_URL . "?ctrl=user&act=resetPassword&code=" . $code);
                exit;
            } else {
                $error = 'Mã xác nhận không đúng hoặc đã hết hạn (10 phút).';
            }
        }
        
        // Quay lại trang nhập mã với lỗi
        $oldEmail = urlencode($email);
        $encodedError = urlencode($error);
        header("Location: " . BASE_URL . "?ctrl=user&act=verifyCodeForm&email={$oldEmail}&error={$encodedError}");
        exit;
    }

    // 5. Trang đặt mật khẩu mới (Sau khi nhập mã thành công)
    function resetPassword() {
        $code = $_GET['code'] ?? ''; 
        $error = $_GET['error'] ?? '';

        // Kiểm tra lại code có hợp lệ không trước khi cho hiển thị form
        if (empty($code) || !$this->model->getUserByCodeAndEmail($code, '')) {
            $error = 'Yêu cầu đặt lại mật khẩu không hợp lệ hoặc mã đã hết hạn. Vui lòng bắt đầu lại.';
        }
        
        include_once 'Views/users/user_reset_password.php';
    }

    // 6. Xử lý cập nhật mật khẩu mới
    function updatePassword() {
        $code = $_POST['code'] ?? '';
        $newPass = $_POST['password'] ?? '';
        $confirmPass = $_POST['password_confirm'] ?? '';
        $error = '';

        if (empty($code)) {
            $error = 'Phiên làm việc đã hết hạn. Vui lòng bắt đầu lại.';
        } elseif ($newPass !== $confirmPass) {
            $error = 'Mật khẩu mới và xác nhận mật khẩu không khớp.';
        } elseif (strlen($newPass) < 6) { 
            $error = 'Mật khẩu phải có ít nhất 6 ký tự.';
        } else {
            $hashed_pass = password_hash($newPass, PASSWORD_DEFAULT);
            $result = $this->model->updatePasswordByCode($code, $hashed_pass);

            if ($result) {
                $base_url = defined('BASE_URL') ? BASE_URL : '/';
                echo "<script>alert('Đặt lại mật khẩu thành công! Vui lòng đăng nhập.'); window.location='" . $base_url . "?ctrl=user&act=login';</script>";
                exit;
            } else {
                $error = 'Lỗi hệ thống khi cập nhật mật khẩu. Mã xác nhận có thể đã bị thay đổi.';
            }
        }
        
        // Quay lại trang reset password với lỗi
        $encodedError = urlencode($error);
        header("Location: " . BASE_URL . "?ctrl=user&act=resetPassword&code={$code}&error={$encodedError}");
        exit;
    }


    // ... (Các hàm cũ: register, login, logout, profile, etc. giữ nguyên)
    function register() {
        include_once 'Views/users/user_register.php';
    }

    function registerPost() {
        $fullname = trim($_POST['fullname'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $user     = trim($_POST['username'] ?? '');
        $pass     = trim($_POST['password'] ?? '');
        
        $error = '';
        if (empty($fullname) || empty($email) || empty($user) || empty($pass)) {
            $error = 'Vui lòng nhập đầy đủ thông tin.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Email không hợp lệ.';
        } elseif ($this->model->checkUserExist($user)) {
            $error = 'Tên đăng nhập đã tồn tại.';
        }

        if ($error !== '') {
            include_once 'Views/users/user_register.php';
            return;
        }

        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
        $this->model->insertUser($user, $hashed_pass, $fullname, $email);

        echo "<script>alert('Đăng ký thành công!'); window.location='?ctrl=user&act=login';</script>";
    }

    function login() {
        include_once 'Views/users/user_login.php';
    }

    function loginPost() {
        $user = trim($_POST['username'] ?? '');
        $pass = trim($_POST['password'] ?? '');

        // Validate đơn giản
        if (empty($user) || empty($pass)) {
             $error = "Vui lòng nhập đầy đủ thông tin.";
             include_once 'Views/users/user_login.php';
             return;
        }

        $check = $this->model->login($user);

        if ($check && password_verify($pass, $check['password'])) {
            $_SESSION['user'] = $check;
            
            // Chống Session Fixation
            session_regenerate_id(true);

            if ((int)$check['role'] === 1) {
                header("Location: ?ctrl=admin&act=dashboard");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Sai tên đăng nhập hoặc mật khẩu!";
            include_once 'Views/users/user_login.php';
        }
    }

    function logout() {
        unset($_SESSION['user']);
        header("Location: index.php");
        exit;
    }

   
    function profile() {
        if (!isset($_SESSION['user'])) { header("Location: ?ctrl=user&act=login"); exit; }
        include_once 'Models/Order.php';

        $orderModel = new Order();
        $user = $_SESSION['user'];
        $orders = $orderModel->getOrdersByUserId($user['id']);

        $reviews = []; 
        $invoices = [];
        
        include_once 'Views/users/profile.php';
    }
    
    // Các hàm khác giữ nguyên cấu trúc nhưng thêm header() redirect thay vì echo script
    function editProfile() {
         if (!isset($_SESSION['user'])) { header("Location: ?ctrl=user&act=login"); exit; }
         include_once 'Views/users/edit_profile.php';
    }

    function updateProfile() {
        if (!isset($_SESSION['user'])) { header("Location: ?ctrl=user&act=login"); exit; }
        
        $id       = $_SESSION['user']['id'];
        $fullname = trim($_POST['fullname']);
        $email    = trim($_POST['email']);
        $phone    = trim($_POST['phone']);
        $address  = trim($_POST['address']);

        $this->model->updateUser($id, $fullname, $email, $phone, $address);
        
        // Cập nhật lại session
        $_SESSION['user']['fullname'] = $fullname;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['phone'] = $phone;
        $_SESSION['user']['address'] = $address;

        echo "<script>alert('Cập nhật thành công!'); window.location='?ctrl=user&act=profile';</script>";
    }
}
?>