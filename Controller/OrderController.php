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
            // ... (Đoạn lấy dữ liệu giữ nguyên) ...
            $userId = $_SESSION['user']['id'];
            $fullname = $_POST['fullname'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $note = $_POST['note'];
            $payment = $_POST['payment_method']; // 'COD' hoặc 'BANK'
            
            // Tính tổng tiền
            $totalMoney = 0;
            foreach ($_SESSION['cart'] as $item) {
                $totalMoney += $item['price'] * $item['quantity'];
            }

            // Tạo đơn hàng
            $orderId = $this->model->createOrder($userId, $fullname, $phone, $address, $totalMoney, $payment, $note);

            if ($orderId) {
                // Lưu chi tiết
                foreach ($_SESSION['cart'] as $item) {
                    $this->model->createOrderDetail($orderId, $item['id'], $item['quantity'], $item['price']);
                }
                unset($_SESSION['cart']); // Xóa giỏ hàng

                // --- LOGIC PHÂN LUỒNG ---
                if ($payment == 'BANK') {
                    // Nếu là chuyển khoản -> Chuyển sang trang quét mã
                    header("Location: ?ctrl=order&act=payment&id=$orderId");
                } else {
                    // Nếu là COD -> Xong luôn
                    echo "<script>alert('Đặt hàng thành công!'); window.location='?ctrl=user&act=profile';</script>";
                }
                // ------------------------
            } else {
                echo "<script>alert('Lỗi hệ thống!'); history.back();</script>";
            }
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