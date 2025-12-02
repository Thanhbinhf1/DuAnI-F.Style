<?php
include_once 'Models/Order.php';

class OrderController {
    private $model;

    function __construct() {
        $this->model = new Order();
    }

    // 1. Hiện trang thanh toán
    function checkout() {
        // Bắt buộc đăng nhập mới được thanh toán
        if (!isset($_SESSION['user'])) {
            echo "<script>alert('Vui lòng đăng nhập để thanh toán!'); window.location='?ctrl=user&act=login';</script>";
            return;
        }

        // Kiểm tra giỏ hàng có trống không
        if (empty($_SESSION['cart'])) {
            echo "<script>alert('Giỏ hàng trống!'); window.location='index.php';</script>";
            return;
        }

        // Lấy thông tin user để điền sẵn vào form
        $user = $_SESSION['user'];
        
        // Tính tổng tiền
        $totalPrice = 0;
        foreach ($_SESSION['cart'] as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        include_once 'Views/users/checkout.php';
    }

    // 2. Xử lý lưu đơn hàng
    function saveOrder() {
    // Chỉ cho phép POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php");
        exit;
    }

    // Bắt buộc đăng nhập
    if (!isset($_SESSION['user'])) {
        echo "<script>alert('Vui lòng đăng nhập để đặt hàng!'); window.location='?ctrl=user&act=login';</script>";
        exit;
    }

    // Giỏ hàng phải có sản phẩm
    if (empty($_SESSION['cart'])) {
        echo "<script>alert('Giỏ hàng trống!'); window.location='index.php';</script>";
        exit;
    }

    $userId  = $_SESSION['user']['id'];

    // Lấy dữ liệu từ form
    $fullname = trim($_POST['fullname'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $note     = trim($_POST['note'] ?? '');
    $payment  = $_POST['payment_method'] ?? 'COD'; // 'COD' hoặc 'BANK'

    // Validate đơn giản
    $errors = [];

    if ($fullname === '') {
        $errors[] = 'Vui lòng nhập họ và tên người nhận.';
    }

    if ($phone === '') {
        $errors[] = 'Vui lòng nhập số điện thoại.';
    } elseif (!preg_match('/^0[0-9]{9}$/', $phone)) {
        // Ví dụ: 10 số, bắt đầu bằng 0
        $errors[] = 'Số điện thoại không hợp lệ.';
    }

    if ($address === '') {
        $errors[] = 'Vui lòng nhập địa chỉ nhận hàng.';
    }

    if (!in_array($payment, ['COD', 'BANK'])) {
        $payment = 'COD';
    }

    // Nếu có lỗi -> báo và quay lại
    if (!empty($errors)) {
        $msg = implode("\\n", $errors);
        echo "<script>alert('$msg'); history.back();</script>";
        exit;
    }

    // Tính tổng tiền từ giỏ hàng
    $totalMoney = 0;
    foreach ($_SESSION['cart'] as $item) {
        $totalMoney += $item['price'] * $item['quantity'];
    }

    // Tạo đơn hàng
    $orderId = $this->model->createOrder(
        $userId,
        $fullname,
        $phone,
        $address,
        $totalMoney,
        $payment,
        $note
    );

    if ($orderId) {
        // Lưu chi tiết từng sản phẩm
        foreach ($_SESSION['cart'] as $item) {
            $this->model->createOrderDetail(
                $orderId,
                $item['id'],
                $item['quantity'],
                $item['price']
            );
        }

        // Xóa giỏ hàng sau khi tạo đơn
        unset($_SESSION['cart']);

        // PHÂN LUỒNG THEO HÌNH THỨC THANH TOÁN
        if ($payment === 'BANK') {
            // Chuyển sang trang quét mã QR cho đơn hàng vừa tạo
            header("Location: ?ctrl=order&act=payment&id=" . $orderId);
            exit;
        } else { // COD
            echo "<script>alert('Đặt hàng thành công! Đơn hàng của bạn sẽ được xử lý trong thời gian sớm nhất.'); 
                  window.location='?ctrl=user&act=profile';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Lỗi hệ thống! Vui lòng thử lại sau.'); history.back();</script>";
        exit;
    }
}


    // 2. THÊM HÀM MỚI: Hiển thị trang thanh toán QR
    function payment() {
        if (isset($_GET['id'])) {
            $orderId = $_GET['id'];
            // Lấy thông tin đơn hàng để hiện số tiền và nội dung CK
            $order = $this->model->getOrderById($orderId);
            
            if ($order) {
                include_once 'Views/users/payment_qr.php';
            } else {
                echo "Đơn hàng không tồn tại!";
            }
        }
    }
}
?>