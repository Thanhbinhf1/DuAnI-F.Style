<?php 
include_once 'Models/User.php';
include_once 'csrf.php'; // Đảm bảo file này tồn tại

class UserController {
    function saveAddress() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user'])) {
            $id = $_SESSION['user']['id'];
            $province_id = $_POST['province_id'];
            $district_id = $_POST['district_id'];
            $ward_id     = $_POST['ward_id'];
            $street      = $_POST['street_address'];
            $full_addr   = $_POST['full_address_str']; // Chuỗi địa chỉ đầy đủ

            include_once 'Models/User.php';
            $userModel = new User();
            $userModel->updateAddress($id, $province_id, $district_id, $ward_id, $street, $full_addr);

            // Cập nhật lại Session để các trang khác nhận diện ngay
            $_SESSION['user']['province_id'] = $province_id;
            $_SESSION['user']['district_id'] = $district_id;
            $_SESSION['user']['ward_id'] = $ward_id;
            $_SESSION['user']['street_address'] = $street;
            $_SESSION['user']['address'] = $full_addr;

            echo "<script>alert('Đã lưu địa chỉ mặc định!'); window.location='?ctrl=user&act=profile';</script>";
        }
    }
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