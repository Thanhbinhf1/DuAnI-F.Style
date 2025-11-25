<?php 
include_once 'Models/User.php';

class UserController {
    private $model;

    function __construct() {
        $this->model = new User();
    }

    // --- XỬ LÝ ĐĂNG KÝ ---
    function register() {
        include_once 'Views/user_register.php';
    }

    function registerPost() {
        // Nhận dữ liệu từ form
        $user = $_POST['username'];
        $pass = $_POST['password']; // Lưu ý: Thực tế nên mã hóa password bằng password_hash()
        $email = $_POST['email'];
        $name = $_POST['fullname'];

        // Kiểm tra xem user đã có chưa
        if($this->model->checkUserExist($user)) {
            $error = "Tài khoản đã tồn tại!";
            include_once 'Views/user_register.php';
        } else {
            // Thêm vào DB
            $this->model->insertUser($user, $pass, $name, $email);
            echo "<script>alert('Đăng ký thành công! Mời đăng nhập.'); window.location='?ctrl=user&act=login';</script>";
        }
    }

    // --- XỬ LÝ ĐĂNG NHẬP ---
    function login() {
        include_once 'Views/user_login.php';
    }

    function loginPost() {
        $user = $_POST['username'];
        $pass = $_POST['password'];

        $check = $this->model->checkUser($user, $pass);

        if ($check) {
            // Lưu thông tin vào Session
            $_SESSION['user'] = $check;
            
            // Nếu là admin thì chuyển trang quản trị (làm sau), khách thì về trang chủ
            echo "<script>alert('Đăng nhập thành công!'); window.location='index.php';</script>";
        } else {
            $error = "Sai tên đăng nhập hoặc mật khẩu!";
            include_once 'Views/user_login.php';
        }
    }

    // --- ĐĂNG XUẤT ---
    function logout() {
        unset($_SESSION['user']); // Xóa session
        echo "<script>window.location='index.php';</script>";
    }
}
?>