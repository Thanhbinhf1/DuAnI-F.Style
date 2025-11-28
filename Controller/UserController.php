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
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $email = $_POST['email'];
        $name = $_POST['fullname'];

        if($this->model->checkUserExist($user)) {
            $error = "Tài khoản đã tồn tại!";
            include_once 'Views/users/user_register.php';
        } else {
            // MÃ HÓA MẬT KHẨU TRƯỚC KHI LƯU
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
            
            $this->model->insertUser($user, $hashed_pass, $name, $email);
            echo "<script>alert('Đăng ký thành công!'); window.location='?ctrl=user&act=login';</script>";
        }
    }

    function login() {
        include_once 'Views/users/user_login.php';
    }

    function loginPost() {
        $user = $_POST['username'];
        $pass = $_POST['password'];

        // Lấy thông tin user từ DB
        $check = $this->model->checkUser($user);

        // Kiểm tra: Có user đó KHÔNG và Mật khẩu có khớp mã hóa KHÔNG
        if ($check && password_verify($pass, $check['password'])) {
            $_SESSION['user'] = $check;
            echo "<script>alert('Đăng nhập thành công!'); window.location='index.php';</script>";
        } else {
            $error = "Sai tên đăng nhập hoặc mật khẩu!";
            include_once 'Views/users/user_login.php';
        }
    }

    function logout() {
        unset($_SESSION['user']);
        echo "<script>window.location='index.php';</script>";
    }
}
?>