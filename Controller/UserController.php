<?php 
include_once 'Models/User.php';

class UserController {
    private $model;

    function __construct() {
        $this->model = new User();
    }

    function register() {
        include_once 'Views/users/user_register.php';
    }

    function registerPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?ctrl=user&act=register");
            exit;
        }

        if (!verify_csrf($_POST['csrf_token'] ?? null)) {
            $error = "Phiên làm việc không hợp lệ, vui lòng thử lại.";
            include_once 'Views/users/user_register.php';
            return;
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $fullname = trim($_POST['fullname'] ?? '');
        $email    = trim($_POST['email'] ?? '');

        // Validate đơn giản
        if ($username === '' || $password === '' || $fullname === '' || $email === '') {
            $error = "Vui lòng nhập đầy đủ thông tin.";
            include_once 'Views/users/user_register.php';
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Email không hợp lệ.";
            include_once 'Views/users/user_register.php';
            return;
        }

        // Check trùng
        if ($this->model->checkUserExist($username, $email)) {
            $error = "Tên đăng nhập hoặc email đã tồn tại.";
            include_once 'Views/users/user_register.php';
            return;
        }

        $this->model->insertUser($username, $password, $fullname, $email);
        echo "<script>alert('Đăng ký thành công, vui lòng đăng nhập!'); window.location='?ctrl=user&act=login';</script>";
    }

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

        // Đăng nhập OK
        $_SESSION['user'] = [
            'id'       => $user['id'],
            'username' => $user['username'],
            'fullname' => $user['fullname'],
            'email'    => $user['email'],
            'phone'    => $user['phone'] ?? null,
            'address'  => $user['address'] ?? null
        ];

        header("Location: index.php");
        exit;
    }

    function logout() {
        unset($_SESSION['user']);
        header("Location: index.php");
        exit;
    }

    function profile() {
        if (!isset($_SESSION['user'])) {
            header("Location: ?ctrl=user&act=login");
            exit;
        }

        // Gọi Model Order để lấy lịch sử
        include_once 'Models/Order.php';
        $orderModel = new Order();
        $userId = $_SESSION['user']['id'];
        
        $orders = $orderModel->getOrdersByUser($userId);
        
        // Thông tin user hiện tại
        $user = $_SESSION['user'];

        include_once 'Views/users/profile.php';
    }

    // HÀM MỚI: Hiển thị form sửa
    function edit() {
        if (!isset($_SESSION['user'])) header("Location: index.php");
        $user = $_SESSION['user'];
        include_once 'Views/users/edit_profile.php';
    }

    // HÀM MỚI: Xử lý cập nhật
    function updatePost() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_SESSION['user']['id'];
            $fullname = $_POST['fullname'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];

            // Gọi Model cập nhật DB
            $this->model->updateUser($id, $fullname, $email, $phone, $address);

            // Cập nhật lại Session để hiển thị ngay lập tức
            $_SESSION['user']['fullname'] = $fullname;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['phone'] = $phone;
            $_SESSION['user']['address'] = $address;

            echo "<script>alert('Cập nhật thành công!'); window.location='?ctrl=user&act=profile';</script>";
        }
    }
}


?>