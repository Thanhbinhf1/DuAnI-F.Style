<?php
include_once 'Models/Order.php';
include_once 'Models/Product.php';

class OrderController {
    private $model;
    private $productModel;
    

    function __construct() {
        $this->model = new Order();
        $this->productModel = new Product();
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
        $cartWarnings = $_SESSION['cart_warnings'] ?? [];
        unset($_SESSION['cart_warnings']);
        
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
        // --- BỔ SUNG: Xử lý giảm giá ---
$discountAmount = isset($_POST['discount_amount']) ? (float)$_POST['discount_amount'] : 0;
// Validate lại discount ở server (để tránh hack html) nếu có thời gian, 
// nhưng ít nhất phải trừ tiền ở đây:
$finalTotal = $totalMoney - $discountAmount;
if ($finalTotal < 0) $finalTotal = 0;
$voucherCode = $_POST['voucher_code_used'] ?? null;
if($voucherCode) {
    // Logic cập nhật số lượng voucher đã dùng (giảm quantity của voucher đi 1)
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
    $this->model->createOrderDetail($orderId, $item['id'], $item['quantity'], $item['price']);
    
    // --- BỔ SUNG: TRỪ TỒN KHO ---
    // Gọi sang ProductModel để trừ số lượng
    if ($item['variant_id'] > 0) {
        $this->productModel->decreaseVariantStock($item['variant_id'], $item['quantity']);
    } else {
        $this->productModel->decreaseProductStock($item['id'], $item['quantity']);
    }
}

            // Xóa giỏ hàng sau khi tạo đơn
            unset($_SESSION['cart']);

            // PHÂN LUỒNG THEO HÌNH THỨC THANH TOÁN
            if ($payment === 'BANK') {
                // Chuyển sang trang quét mã QR cho đơn hàng vừa tạo
                header("Location: ?ctrl=order&act=payment&id=" . $orderId);
                exit;
            } else { // COD
                // SỬA: Chuyển hướng sang trang HÓA ĐƠN để hiện Popup đánh giá
                echo "<script>
                        alert('Đặt hàng thành công!'); 
                        window.location='?ctrl=order&act=printInvoice&id=" . $orderId . "';
                      </script>";
                exit;
            }
        } else {
            echo "<script>alert('Lỗi hệ thống! Vui lòng thử lại sau.'); history.back();</script>";
            exit;
        }
    }


    // 3. Hiển thị trang thanh toán QR
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

    function detail() {
        if (!isset($_SESSION['user'])) { header("Location: ?ctrl=user&act=login"); exit; }
        $orderId = $_GET['id'] ?? 0;
        $order = $this->model->getOrderById($orderId);

        if (!$order || $order['user_id'] != $_SESSION['user']['id']) {
            echo "<script>alert('Không tìm thấy đơn hàng của bạn.'); window.location='?ctrl=user&act=profile';</script>";
            return;
        }

        $orderDetails = $this->model->getOrderDetails($orderId) ?: [];

        $statusMap = [
            0 => ['label' => 'Chờ xử lý', 'color' => '#f39c12'],
            1 => ['label' => 'Đang giao', 'color' => '#3498db'],
            2 => ['label' => 'Đã giao', 'color' => '#27ae60'],
            3 => ['label' => 'Đã hủy', 'color' => '#e74c3c']
        ];

        $paymentLabel = ((int)$order['payment_status'] === 1) ? 'Đã thanh toán' : 'Chưa thanh toán';
        $carrierName = $order['carrier_name'] ?? 'Giao Hàng Nhanh';
        $trackingId = $order['tracking_code'] ?? ('FS' . str_pad($orderId, 6, '0', STR_PAD_LEFT));
        $trackingUrl = 'https://tracking.ghn.dev/?code=' . urlencode($trackingId);

        $status = (int)$order['status'];
        $statusStep = 0;
        
        if ($status === 1) { $statusStep = 2; } // Đang giao
        if ($status === 2) { $statusStep = 3; } // Đã giao thành công
        
        $isCancelled = ($status === 3);

        $createdAt = strtotime($order['created_at']);
        $events = [
            [
                'title' => 'Đặt hàng thành công',
                'description' => 'Đơn hàng đã được tạo và chờ xử lý.',
                'time' => date('H:i d/m/Y', $createdAt),
                'done' => true
            ],
            [
                'title' => 'Xác nhận & đóng gói',
                'description' => 'Kho đang chuẩn bị sản phẩm để bàn giao.',
                'time' => date('H:i d/m/Y', $createdAt + 3600),
                'done' => ($statusStep >= 1 && !$isCancelled)
            ],
            [
                'title' => 'Đang giao hàng',
                'description' => 'Đơn hàng đang được vận chuyển bởi ' . $carrierName . '.',
                'time' => date('H:i d/m/Y', $createdAt + 7200),
                'done' => ($statusStep >= 2 && !$isCancelled),
                'carrier' => $carrierName,
                'tracking' => $trackingId,
                'tracking_link' => $trackingUrl
            ],
            [
                'title' => $isCancelled ? 'Đã hủy' : 'Hoàn tất',
                'description' => $isCancelled ? 'Đơn hàng đã bị hủy.' : 'Đơn hàng đã giao thành công.',
                'time' => date('H:i d/m/Y', $createdAt + 10800),
                'done' => ($statusStep >= 3 || $isCancelled)
            ]
        ];

        include_once 'Views/users/order_detail.php';
    }

    function reorder() {
        if (!isset($_SESSION['user'])) { header("Location: ?ctrl=user&act=login"); exit; }
        $orderId = $_GET['id'] ?? 0;
        $order = $this->model->getOrderById($orderId);

        if (!$order || $order['user_id'] != $_SESSION['user']['id']) {
            echo "<script>alert('Không thể mua lại đơn hàng này.'); window.location='?ctrl=user&act=profile';</script>";
            return;
        }

        $items = $this->model->getOrderDetails($orderId);
        if (empty($items)) {
            echo "<script>alert('Không tìm thấy sản phẩm trong đơn hàng.'); history.back();</script>";
            return;
        }

        if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }

        foreach ($items as $item) {
            $key = $item['product_id'] . '_0';
            $qty = (int)$item['quantity'];
            if (isset($_SESSION['cart'][$key])) {
                $_SESSION['cart'][$key]['quantity'] += $qty;
            } else {
                $_SESSION['cart'][$key] = [
                    'id' => $item['product_id'],
                    'variant_id' => 0,
                    'name' => $item['product_name'],
                    'image' => $item['product_image'],
                    'price' => $item['price'],
                    'quantity' => $qty,
                    'stock' => 99,
                    'info' => ''
                ];
            }
        }

        echo "<script>alert('Đã thêm lại sản phẩm vào giỏ hàng.'); window.location='?ctrl=cart&act=view';</script>";
    }

    function printInvoice() {
        if (!isset($_SESSION['user'])) { header("Location: ?ctrl=user&act=login"); exit; }
        $orderId = $_GET['id'] ?? 0;
        $order = $this->model->getOrderById($orderId);

        if (!$order || $order['user_id'] != $_SESSION['user']['id']) {
            echo "<script>alert('Không tìm thấy đơn hàng để in.'); window.location='?ctrl=user&act=profile';</script>";
            return;
        }

       // Đổi thành getOrderDetailsForReview để lấy cả hình ảnh sản phẩm
$orderDetails = $this->model->getOrderDetailsForReview($orderId) ?: [];
        include_once 'Views/users/order_invoice.php';
    }

    function tracking() {
        if (!isset($_SESSION['user'])) { header("Location: ?ctrl=user&act=login"); exit; }
        $orderId = $_GET['id'] ?? 0;
        $order = $this->model->getOrderById($orderId);

        if (!$order || $order['user_id'] != $_SESSION['user']['id']) {
            echo "<script>alert('Không tìm thấy thông tin vận chuyển.'); window.location='?ctrl=user&act=profile';</script>";
            return;
        }

        $carrierName = $order['carrier_name'] ?? 'Giao Hàng Nhanh';
        $trackingId = $order['tracking_code'] ?? ('FS' . str_pad($orderId, 6, '0', STR_PAD_LEFT));
        $trackingUrl = 'https://tracking.ghn.dev/?code=' . urlencode($trackingId);

        include_once 'Views/users/order_tracking.php';
    }

    // Hủy đơn hàng: chỉ cho phép khi đơn đang "Chờ xác nhận" (status = 0)
    function cancel() {
        if (!isset($_SESSION['user'])) { header("Location: ?ctrl=user&act=login"); exit; }
        $orderId = $_GET['id'] ?? 0;

        $order = $this->model->getOrderById($orderId);
        if (!$order || $order['user_id'] != $_SESSION['user']['id']) {
            echo "<script>alert('Không tìm thấy đơn hàng của bạn.'); window.location='?ctrl=user&act=profile';</script>";
            return;
        }

        if ((int)$order['status'] !== 0) {
            echo "<script>alert('Đơn hàng đã được xử lý, không thể hủy.'); window.location='?ctrl=order&act=detail&id={$orderId}';</script>";
            return;
        }
        $this->productModel->restoreStockForOrder($orderId);

        $this->model->updateOrderStatus($orderId, 3);
        echo "<script>alert('Đã hủy đơn hàng.'); window.location='?ctrl=user&act=profile#orders';</script>";
    }
}
?>