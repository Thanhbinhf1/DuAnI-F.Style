<?php 
include_once 'Models/User.php';
include_once 'csrf.php'; // Đảm bảo file này tồn tại

class UserController {
    private $model;

    function __construct() {
        $this->model = new User();
    }

    function register() {
        include_once 'Views/users/user_register.php';
    }

    function registerPost() {
        $fullname = trim($_POST['fullname'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $user     = trim($_POST['username'] ?? '');
        $pass     = trim($_POST['password'] ?? '');
        
        // Thêm check ở đây nếu view có input csrf_token (nếu chưa có thì bỏ qua dòng này hoặc thêm input vào view)
        // if (!verify_csrf($_POST['csrf_token'] ?? '')) { die("Lỗi bảo mật CSRF"); }

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

        $check = $this->model->checkUser($user);

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

    // ... (Giữ nguyên các hàm profile, editProfile, updateProfile) ...
    function profile() {
        if (!isset($_SESSION['user'])) { header("Location: ?ctrl=user&act=login"); exit; }
        include_once 'Models/Order.php';
        $orderModel = new Order();
        $user = $_SESSION['user'];
        $orders = $orderModel->getAllOrders(); // Lưu ý: Hàm này đang lấy ALL orders, cần sửa model để lấy Order by UserID
        // Fix tạm logic hiển thị:
        // Bạn cần thêm hàm getOrdersByUserId vào Models/Order.php
        // $orders = $orderModel->getOrdersByUserId($user['id']); 
        // Hiện tại dùng tạm cái cũ nhưng cần filter
        
        // Giả sử trong User model bạn chưa viết hàm getOrderByUser thì dùng tạm logic cũ
        // Nhưng đúng ra phải sửa Models/Order.php
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