<?php
include_once 'Models/Product.php';

class CartController {
    private $model;

    function __construct() {
        $this->model = new Product();
    }

    function view() {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
        include_once 'Views/users/cart_view.php';
    }

    // Thêm vào giỏ hàng
    function add() {
        $this->processAdd(false); // False = Không chuyển hướng ngay (để ở lại trang chi tiết hoặc về danh sách)
        // Quay lại trang cũ hoặc giỏ hàng
        echo "<script>alert('Đã thêm vào giỏ hàng!'); history.back();</script>";
    }

    // Mua ngay (Thêm -> Chuyển đến giỏ hàng luôn)
    function buyNow() {
        $this->processAdd(true); 
    }

    // Hàm xử lý chung cho Add và BuyNow
    private function processAdd($isBuyNow) {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $qty = (int)$_POST['quantity'];
            $variantId = isset($_POST['variant_id']) ? $_POST['variant_id'] : 0;

            $product = $this->model->getProductById($id);
            $name = $product['name'];
            $price = $product['price'];
            $image = $product['image'];
            $info = ""; 
            $stock = 100; // Mặc định nếu không check được

            if ($variantId > 0) {
                $variant = $this->model->getVariantDetail($variantId);
                if ($variant) {
                    $price = $variant['price'];
                    $info = "Phân loại: " . $variant['color'] . " / " . $variant['size'];
                    $stock = $variant['quantity']; // Lấy tồn kho thực tế
                }
            }

            $key = $id . "_" . $variantId;

            if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }

            // Kiểm tra tồn kho trước khi cộng dồn
            $currentQty = isset($_SESSION['cart'][$key]) ? $_SESSION['cart'][$key]['quantity'] : 0;
            
            if ($currentQty + $qty > $stock) {
                echo "<script>alert('Xin lỗi, kho chỉ còn $stock sản phẩm!'); history.back();</script>";
                exit;
            }

            if (isset($_SESSION['cart'][$key])) {
                $_SESSION['cart'][$key]['quantity'] += $qty;
            } else {
                $_SESSION['cart'][$key] = [
                    'id' => $id,
                    'variant_id' => $variantId,
                    'name' => $name,
                    'image' => $image,
                    'price' => $price,
                    'quantity' => $qty,
                    'stock' => $stock, // Lưu tồn kho vào session để check ở giỏ hàng
                    'info' => $info
                ];
            }

            if ($isBuyNow) {
                echo "<script>window.location='?ctrl=cart&act=view';</script>";
                exit;
            }
        }
    }

    // Xóa
    function delete() {
        if (isset($_GET['key'])) {
            $key = $_GET['key'];
            unset($_SESSION['cart'][$key]);
        }
        echo "<script>window.location='?ctrl=cart&act=view';</script>";
    }

    // Cập nhật bằng AJAX (Không load lại trang)
    function updateAjax() {
        if (isset($_POST['key']) && isset($_POST['qty'])) {
            $key = $_POST['key'];
            $qty = (int)$_POST['qty'];

            if (isset($_SESSION['cart'][$key])) {
                $stock = $_SESSION['cart'][$key]['stock'];
                
                // Kiểm tra tồn kho
                if ($qty > $stock) $qty = $stock;
                if ($qty < 1) $qty = 1;

                $_SESSION['cart'][$key]['quantity'] = $qty;
                
                // Tính lại tổng tiền giỏ hàng để trả về cho JS
                $totalOrder = 0;
                foreach ($_SESSION['cart'] as $item) {
                    $totalOrder += $item['price'] * $item['quantity'];
                }

                // Trả về JSON
                echo json_encode([
                    'status' => 'success', 
                    'new_qty' => $qty,
                    'row_total' => number_format($qty * $_SESSION['cart'][$key]['price']) . ' đ',
                    'cart_total' => number_format($totalOrder) . ' đ'
                ]);
            }
        }
    }
}
?>