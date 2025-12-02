<?php 
include_once 'Models/User.php';

class UserController {
    private $model;

    function __construct() {
        $this->model = new User();
    }

    // ======== ĐĂNG KÝ ========

    function register() {
        // chỉ hiển thị form
        include_once 'Views/users/user_register.php';
    }

    function registerPost() {
        $fullname = trim($_POST['fullname'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $user     = trim($_POST['username'] ?? '');
        $pass     = trim($_POST['password'] ?? '');

        $error = '';

        if ($fullname === '' || $email === '' || $user === '' || $pass === '') {
            $error = 'Vui lòng nhập đầy đủ thông tin.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Email không hợp lệ.';
        } elseif ($this->model->checkUserExist($user)) {
            $error = 'Tên đăng nhập đã tồn tại, vui lòng chọn tên khác.';
        }

        if ($error !== '') {
            // Có lỗi -> load lại form & hiện $error
            include_once 'Views/users/user_register.php';
            return;
        }

        // Mã hóa mật khẩu
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

        // Lưu vào DB
        $this->model->insertUser($user, $hashed_pass, $fullname, $email);

        echo "<script>alert('Đăng ký thành công! Vui lòng đăng nhập.'); 
              window.location='?ctrl=user&act=login';</script>";
    }

    // ======== ĐĂNG NHẬP ========

    function login() {
        include_once 'Views/users/user_login.php';
    }

    function loginPost() {
        $user = $_POST['username'] ?? '';
        $pass = $_POST['password'] ?? '';

        // Lấy thông tin user từ DB
        $check = $this->model->checkUser($user);

        // Kiểm tra: Có user đó KHÔNG và Mật khẩu có khớp mã hóa KHÔNG
        if ($check && password_verify($pass, $check['password'])) {
            // Lưu user vào session
            $_SESSION['user'] = $check;

            // Nếu là admin (role = 1) -> vào trang admin
            if (!empty($check['role']) && (int)$check['role'] === 1) {
                echo "<script>alert('Đăng nhập admin thành công!'); 
                      window.location='?ctrl=admin&act=dashboard';</script>";
            } else {
                // Ngược lại là khách bình thường
                echo "<script>alert('Đăng nhập thành công!'); 
                      window.location='index.php';</script>";
            }
        } else {
            $error = "Sai tên đăng nhập hoặc mật khẩu!";
            include_once 'Views/users/user_login.php';
        }
    }

    // ======== ĐĂNG XUẤT ========

    function logout() {
        unset($_SESSION['user']);
        echo "<script>window.location='index.php';</script>";
    }

    // ======== HỒ SƠ / PROFILE ========

    function profile() {
        if (!isset($_SESSION['user'])) {
            header("Location: ?ctrl=user&act=login");
            exit;
        }

        include_once 'Models/Order.php';
        $orderModel = new Order();

        $user  = $_SESSION['user'];
        $orders = $orderModel->getOrdersByUser($user['id']);

        include_once 'Views/users/profile.php';
    }

    function editProfile() {
        if (!isset($_SESSION['user'])) {
            header("Location: ?ctrl=user&act=login");
            exit;
        }
        include_once 'Views/users/edit_profile.php';
    }

    function updateProfile() {
        if (!isset($_SESSION['user'])) {
            header("Location: ?ctrl=user&act=login");
            exit;
        }

        $id       = $_SESSION['user']['id'];
        $fullname = trim($_POST['fullname'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $phone    = trim($_POST['phone'] ?? '');
        $address  = trim($_POST['address'] ?? '');

        if ($fullname === '' || $email === '') {
            echo "<script>alert('Vui lòng nhập đầy đủ họ tên và email.'); history.back();</script>";
            exit;
        }

        $this->model->updateUser($id, $fullname, $email, $phone, $address);

        // Cập nhật lại Session
        $_SESSION['user']['fullname'] = $fullname;
        $_SESSION['user']['email']    = $email;
        $_SESSION['user']['phone']    = $phone;
        $_SESSION['user']['address']  = $address;

        echo "<script>alert('Cập nhật thành công!'); 
              window.location='?ctrl=user&act=profile';</script>";
    }
}
?>
