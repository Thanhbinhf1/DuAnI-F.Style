<?php
class Product {
    private $db;

    function __construct() {
        $this->db = new Database();
    }

    // 1. HÀNG MỚI VỀ
    // - Mặc định: lấy $limit sản phẩm mới nhất (theo created_at, id)
    // - Nếu $limit <= 0: lấy TẤT CẢ (dùng cho "Xem tất cả")
    function getNewProducts($limit = 4) {
        $limit = (int)$limit;
        $sql = "SELECT * FROM products ORDER BY created_at DESC, id DESC";
        if ($limit > 0) {
            $sql .= " LIMIT $limit";
        }
        return $this->db->query($sql);
    }

    // 2. SẢN PHẨM HOT – dựa theo lượt xem
    function getHotProducts($limit = 4) {
        $limit = (int)$limit;
        $sql = "SELECT * FROM products ORDER BY views DESC, id DESC";
        if ($limit > 0) {
            $sql .= " LIMIT $limit";
        }
        return $this->db->query($sql);
    }

    // 3. SẢN PHẨM GIÁ TỐT – ưu tiên price_sale nếu có
    function getSaleProducts($limit = 4) {
        $limit = (int)$limit;
        $sql = "SELECT *, 
                       CASE 
                           WHEN price_sale IS NOT NULL AND price_sale > 0 THEN price_sale
                           ELSE price
                       END AS effective_price
                FROM products
                ORDER BY effective_price ASC, id DESC";
        if ($limit > 0) {
            $sql .= " LIMIT $limit";
        }
        return $this->db->query($sql);
    }

    // Lấy chi tiết 1 sản phẩm
    function getProductById($id) {
        $sql = "SELECT * FROM products WHERE id = ?";
        return $this->db->queryOne($sql, [$id]);
    }

    // Tăng lượt xem (để xác định HOT)
    function increaseView($id) {
        $sql = "UPDATE products SET views = views + 1 WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }
    
    // Sản phẩm liên quan cùng danh mục (trừ chính nó)
    function getRelatedProducts($categoryId, $excludeId) {
        $sql = "SELECT * FROM products WHERE category_id = ? AND id != ? ORDER BY id DESC LIMIT 8";
        return $this->db->query($sql, [$categoryId, $excludeId]);
    }

    // Danh sách biến thể của 1 sản phẩm
    function getProductVariants($productId) {
        $sql = "SELECT * FROM product_variants 
                WHERE product_id = ? 
                ORDER BY color,
                FIELD(size, 'S', 'M', 'L', 'XL', 'XXL', 'FREESIZE', '28','29','30','31','32')";
        return $this->db->query($sql, [$productId]);
    }

    // Kiểm tra tồn kho cho 1 biến thể màu/size
    function checkStock($productId, $color, $size) {
        $sql = "SELECT quantity FROM product_variants 
                WHERE product_id = ? AND color = ? AND size = ?";
        $result = $this->db->queryOne($sql, [$productId, $color, $size]);
        return $result ? (int)$result['quantity'] : 0;
    }

    // Lấy sản phẩm theo danh mục
    function getProductsByCategory($catId) {
        $sql = "SELECT * FROM products WHERE category_id = ? ORDER BY id DESC";
        return $this->db->query($sql, [$catId]);
    }

    // Lấy tất cả sản phẩm (cho trang danh mục)
    function getAllProductsList() {
        $sql = "SELECT * FROM products ORDER BY id DESC";
        return $this->db->query($sql);
    }

    // Lấy tên danh mục
    function getCategoryName($id) {
        $sql = "SELECT name FROM categories WHERE id = ?";
        $result = $this->db->queryOne($sql, [$id]);
        return $result ? $result['name'] : "";
    }

    // Tìm kiếm sản phẩm theo tên / mô tả / brand / sku
    function searchProducts($keyword) {
        $keyword = trim($keyword);
        if ($keyword === '') {
            return [];
        }
        $like = '%' . $keyword . '%';
        $sql = "SELECT * FROM products 
                WHERE name LIKE ?
                   OR description LIKE ?
                   OR brand LIKE ?
                   OR sku_code LIKE ?
                ORDER BY id DESC";
        return $this->db->query($sql, [$like, $like, $like, $like]);
    }

    // Lấy chi tiết 1 biến thể (theo id variant)
    function getVariantDetail($variantId) {
        $sql = "SELECT * FROM product_variants WHERE id = ?";
        return $this->db->queryOne($sql, [$variantId]);
    }

    // ==========================
    // BÌNH LUẬN SẢN PHẨM
    // ==========================

    // Lấy danh sách comment theo sản phẩm + thông tin user
    function getCommentsByProduct($productId) {
        $sql = "SELECT c.*, u.fullname, u.username 
                FROM comments c
                JOIN users u ON c.user_id = u.id
                WHERE c.product_id = ?
                ORDER BY c.date DESC";
        return $this->db->query($sql, [$productId]);
    }

    // Thêm bình luận mới
    function insertComment($userId, $productId, $content, $rating) {
        $sql = "INSERT INTO comments (user_id, product_id, content, rating, date)
                VALUES (?, ?, ?, ?, NOW())";
        return $this->db->execute($sql, [$userId, $productId, $content, $rating]);
    }

    // Lấy rating trung bình & tổng số đánh giá
    function getAverageRating($productId) {
        $sql = "SELECT AVG(rating) AS avg_rating, COUNT(*) AS total 
                FROM comments 
                WHERE product_id = ?";
        $row = $this->db->queryOne($sql, [$productId]);
        return [
            'avg_rating' => $row && $row['avg_rating'] ? round($row['avg_rating'], 1) : 0,
            'total'      => $row ? (int)$row['total'] : 0
        ];
    }

    
    // ===================================
    //  BỔ SUNG CHỨC NĂNG ADMIN
    // ===================================
    
    // --- ADMIN CATEGORY FUNCTIONS ---

    function getAllCategories() {
        $sql = "SELECT * FROM categories ORDER BY id DESC";
        return $this->db->query($sql);
    }
    
    function getCategoryById($id) {
        $sql = "SELECT * FROM categories WHERE id = ?";
        return $this->db->queryOne($sql, [$id]);
    }
    
    function insertCategory($name, $status) {
        $sql = "INSERT INTO categories(name, status) VALUES (?, ?)";
        return $this->db->execute($sql, [$name, $status]);
    }
    
    function updateCategory($id, $name, $status) {
        $sql = "UPDATE categories SET name = ?, status = ? WHERE id = ?";
        return $this->db->execute($sql, [$name, $status, $id]);
    }
    
    function deleteCategory($id) {
        $sql = "DELETE FROM categories WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }

    // --- ADMIN PRODUCT FUNCTIONS ---

    function getAllProductsAdmin() {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.id
                ORDER BY p.id DESC";
        return $this->db->query($sql);
    }

function insertProduct($categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode) {
    // Thêm price_sale vào SQL và danh sách tham số
    $sql = "INSERT INTO products(category_id, name, price, price_sale, image, description, material, brand, sku_code) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
    return $this->db->execute($sql, [$categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode]);
}

// Giữ nguyên hàm updateProduct (10 tham số)
function updateProduct($id, $categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode) {
    $sql = "UPDATE products SET category_id = ?, name = ?, price = ?, price_sale = ?, image = ?, description = ?, material = ?, brand = ?, sku_code = ? WHERE id = ?";
    return $this->db->execute($sql, [$categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode, $id]);
}

    function deleteProduct($id) {
        $sql = "DELETE FROM products WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }

    // --- ADMIN ORDER FUNCTIONS ---
    
    // Lấy tất cả đơn hàng, nối với tên khách hàng
    function getAllOrders() {
        $sql = "SELECT o.*, u.fullname as user_fullname, u.phone as user_phone
                FROM orders o 
                JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC";
        return $this->db->query($sql);
    }

    // Lấy chi tiết đơn hàng (các món đồ trong đơn)
    function getOrderDetails($orderId) {
        $sql = "SELECT od.*, p.name as product_name, p.image as product_image 
                FROM order_details od
                JOIN products p ON od.product_id = p.id
                WHERE od.order_id = ?";
        return $this->db->query($sql, [$orderId]);
    }
    
    function getOrderById($orderId) {
        $sql = "SELECT o.*, u.fullname as user_fullname, u.email, u.phone as user_phone, u.address as user_address
                FROM orders o 
                JOIN users u ON o.user_id = u.id
                WHERE o.id = ?";
        return $this->db->queryOne($sql, [$orderId]);
    }
    
    // Cập nhật trạng thái đơn hàng
    function updateOrderStatus($orderId, $status) {
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        return $this->db->execute($sql, [$status, $orderId]);
    }
}
?>
