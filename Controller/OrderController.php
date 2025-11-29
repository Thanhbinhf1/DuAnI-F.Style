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
            
            // Tính lại tổng tiền cho chắc chắn
            $totalMoney = 0;
            foreach ($_SESSION['cart'] as $item) {
                $totalMoney += $item['price'] * $item['quantity'];
            }

            // A. Lưu vào bảng ORDERS
            $orderId = $this->model->createOrder($userId, $fullname, $phone, $address, $totalMoney);

            // B. Lưu vào bảng ORDER_DETAILS
            if ($orderId) {
                foreach ($_SESSION['cart'] as $item) {
                    $this->model->createOrderDetail($orderId, $item['id'], $item['quantity'], $item['price']);
                }

                // C. Xóa giỏ hàng và thông báo
                unset($_SESSION['cart']);
                echo "<script>alert('Đặt hàng thành công!'); window.location='?ctrl=user&act=profile';</script>";
            } else {
                echo "<script>alert('Lỗi khi tạo đơn hàng!'); history.back();</script>";
            }
        }
    }
}
?>