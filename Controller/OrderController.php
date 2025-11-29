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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user']['id'];
            $fullname = $_POST['fullname'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $note = $_POST['note']; // Lấy ghi chú
            $payment = $_POST['payment_method']; // Lấy phương thức thanh toán
            
            // Tính tổng tiền
            $totalMoney = 0;
            foreach ($_SESSION['cart'] as $item) {
                $totalMoney += $item['price'] * $item['quantity'];
            }

            // Gọi hàm createOrder với đầy đủ tham số mới
            $orderId = $this->model->createOrder($userId, $fullname, $phone, $address, $totalMoney, $payment, $note);

            if ($orderId) {
                foreach ($_SESSION['cart'] as $item) {
                    $this->model->createOrderDetail($orderId, $item['id'], $item['quantity'], $item['price']);
                }
                unset($_SESSION['cart']);
                echo "<script>alert('Đặt hàng thành công! Mã đơn: #$orderId'); window.location='?ctrl=user&act=profile';</script>";
            } else {
                echo "<script>alert('Lỗi hệ thống!'); history.back();</script>";
            }
        }
    }
}
?>