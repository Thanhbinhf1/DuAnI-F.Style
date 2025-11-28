<?php
include_once 'Models/Product.php';

class CartController {
    private $model;

    function __construct() {
        $this->model = new Product();
    }

    // 1. XEM GIỎ HÀNG
    function view() {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        
        // Tính tổng tiền
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        include_once 'Views/users/cart_view.php';
    }

    // 2. THÊM VÀO GIỎ
    function add() {
        if (isset($_POST['id'])) {
            $id = $_POST['id']; // ID sản phẩm cha
            $qty = (int)$_POST['quantity'];
            $variantId = isset($_POST['variant_id']) ? $_POST['variant_id'] : 0;

            // Lấy thông tin sản phẩm
            $product = $this->model->getProductById($id);

            // Thông tin mặc định
            $name = $product['name'];
            $price = $product['price'];
            $image = $product['image'];
            $info = ""; // Lưu Size/Màu

            // Nếu có chọn biến thể (Màu/Size)
            if ($variantId > 0) {
                $variant = $this->model->getVariantDetail($variantId);
                if ($variant) {
                    $price = $variant['price']; // Lấy giá chuẩn của biến thể
                    $info = "Phân loại: " . $variant['color'] . " / " . $variant['size'];
                }
            }

            // Tạo Key duy nhất (Ví dụ: 10_0 là sản phẩm thường, 10_5 là sản phẩm 10 biến thể 5)
            // Để phân biệt Áo Đen và Áo Trắng trong giỏ
            $key = $id . "_" . $variantId;

            // Kiểm tra giỏ hàng
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Nếu đã có sản phẩm này -> Tăng số lượng
            if (isset($_SESSION['cart'][$key])) {
                $_SESSION['cart'][$key]['quantity'] += $qty;
            } else {
                // Chưa có -> Thêm mới
                $_SESSION['cart'][$key] = [
                    'id' => $id,
                    'variant_id' => $variantId,
                    'name' => $name,
                    'image' => $image,
                    'price' => $price,
                    'quantity' => $qty,
                    'info' => $info
                ];
            }

            // Chuyển hướng về trang giỏ hàng
            echo "<script>window.location='?ctrl=cart&act=view';</script>";
        }
    }

    // 3. XÓA SẢN PHẨM KHỎI GIỎ
    function delete() {
        if (isset($_GET['key'])) {
            $key = $_GET['key'];
            unset($_SESSION['cart'][$key]);
        }
        echo "<script>window.location='?ctrl=cart&act=view';</script>";
    }

    // 4. CẬP NHẬT SỐ LƯỢNG (Khi bấm nút cập nhật trong giỏ)
    function update() {
        if (isset($_POST['qty'])) {
            foreach ($_POST['qty'] as $key => $q) {
                if ($q > 0) {
                    $_SESSION['cart'][$key]['quantity'] = $q;
                } else {
                    unset($_SESSION['cart'][$key]); // Nếu nhập 0 thì xóa luôn
                }
            }
        }
        echo "<script>window.location='?ctrl=cart&act=view';</script>";
    }
}
?>