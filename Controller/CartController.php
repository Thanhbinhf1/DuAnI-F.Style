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
public function addToCart() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Kiểm tra dữ liệu gửi lên
        $color = $_POST['color'] ?? null;
        $size = $_POST['size'] ?? null;
        $productId = $_POST['product_id'] ?? null;

        if (empty($color) || empty($size)) {
            // Nếu thiếu, quay lại trang chi tiết và báo lỗi
            $_SESSION['error'] = "Vui lòng chọn đầy đủ màu sắc và kích thước!";
            header("Location: index.php?act=product-detail&id=" . $productId);
            exit();
        }

        // ... Các xử lý thêm vào giỏ hàng bình thường ...
    }
}
    // Hàm xử lý chung cho Add và BuyNow
    private function processAdd($isBuyNow) {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $qty = (int)$_POST['quantity'];
            $variantId = isset($_POST['variant_id']) ? $_POST['variant_id'] : 0;

            $product = $this->model->getProductById($id);
            $name = $product['name'];
            if (isset($product['price_sale']) && $product['price_sale'] > 0) {
            $price = $product['price_sale'];
            } else {
            $price = $product['price'];
            }
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
    // 1. Hàm kiểm tra mã giảm giá (Dùng cho nút "Áp dụng")
    function checkVoucher() {
        while (ob_get_level()) { ob_end_clean(); } // Xóa rác header
        header('Content-Type: application/json');

        if (isset($_POST['code'])) {
            $code = $_POST['code'];
            $db = new Database(); // Gọi DB trực tiếp
            $sql = "SELECT * FROM vouchers WHERE code = ? AND status = 1 AND quantity > 0 AND end_date >= CURDATE()";
            $voucher = $db->queryOne($sql, [$code]);

            if ($voucher) {
                echo json_encode(['status' => 'success', 'percent' => $voucher['discount_percent']]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Mã không hợp lệ hoặc đã hết hạn!']);
            }
        }
        exit;
    }

    // 2. Hàm lấy danh sách Voucher (Dùng cho Modal "Chọn Voucher")
    function listVouchers() {
        while (ob_get_level()) { ob_end_clean(); }
        header('Content-Type: application/json');

        $db = new Database();
        $sql = "SELECT * FROM vouchers WHERE status = 1 AND quantity > 0 AND end_date >= CURDATE()";
        $list = $db->query($sql);
        
        echo json_encode(['status' => 'success', 'data' => $list]);
        exit;
    }
    // Xóa
    function delete() {
        if (isset($_GET['key'])) {
            $key = $_GET['key'];
            unset($_SESSION['cart'][$key]);
        }
        echo "<script>window.location='?ctrl=cart&act=view';</script>";
    }
    function updateAjax() {
        // --- ĐOẠN CODE QUAN TRỌNG NHẤT: Xóa sạch HTML Header thừa ---
        while (ob_get_level()) {
            ob_end_clean();
        }
        // -------------------------------------------------------------

        if (isset($_POST['key']) && isset($_POST['qty'])) {
            $key = $_POST['key'];
            $qty = (int)$_POST['qty'];

            if (isset($_SESSION['cart'][$key])) {
                $stock = $_SESSION['cart'][$key]['stock'];
                
                // Kiểm tra và gán số lượng hợp lệ
                if ($qty > $stock) $qty = $stock;
                if ($qty < 1) $qty = 1;

                $_SESSION['cart'][$key]['quantity'] = $qty;
                
                // Tính toán giá trị mới
                $price = $_SESSION['cart'][$key]['price'];
                $rowTotal = $price * $qty; 
                
                $totalOrder = 0; 
                foreach ($_SESSION['cart'] as $item) {
                    $totalOrder += $item['price'] * $item['quantity'];
                }

                // Trả về kết quả JSON sạch
                echo json_encode([
                    'status' => 'success', 
                    'new_qty' => $qty,
                    'row_total' => number_format($rowTotal) . ' đ', 
                    'cart_total' => number_format($totalOrder) . ' đ'
                ]);
                exit; // Dừng ngay lập tức để không in thêm Footer
            }
        }
        
        // Fallback nếu không thành công
        echo json_encode(['status' => 'error', 'message' => 'Lỗi cập nhật.']);
        exit; 
    
    
}
}

?>