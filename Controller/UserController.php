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
?>