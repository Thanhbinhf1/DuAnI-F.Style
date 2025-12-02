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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?ctrl=user&act=login");
            exit;
        }

        if (!verify_csrf($_POST['csrf_token'] ?? null)) {
            $error = "Phiên làm việc không hợp lệ, vui lòng thử lại.";
            include_once 'Views/users/user_login.php';
            return;
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            $error = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
            include_once 'Views/users/user_login.php';
            return;
        }

        $user = $this->model->checkUser($username);

        if (!$user || !password_verify($password, $user['password'])) {
            $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
            include_once 'Views/users/user_login.php';
            return;
        }

        // Đăng nhập OK -> lưu đầy đủ role
        $_SESSION['user'] = [
            'id'       => $user['id'],
            'username' => $user['username'],
            'fullname' => $user['fullname'],
            'email'    => $user['email'],
            'phone'    => $user['phone'] ?? null,
            'address'  => $user['address'] ?? null,
            'role'     => $user['role'] ?? 0
        ];

        // Nếu là admin -> sang khu admin, ngược lại về trang chủ
        if (!empty($user['role']) && (int)$user['role'] === 1) {
            header("Location: ?ctrl=admin&act=home");
        } else {
            header("Location: index.php");
        }
        exit;
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
