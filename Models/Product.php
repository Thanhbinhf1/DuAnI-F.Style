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
    
    // --- ADMIN PRODUCT FUNCTIONS ---

 function getAllProductsAdmin() {
        // Giả sử cột status đã được thêm vào bảng products
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.id
                ORDER BY p.id DESC";
        return $this->db->query($sql);
    }

function insertProduct($categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode) {
        $sql = "INSERT INTO products(category_id, name, price, price_sale, image, description, material, brand, sku_code) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
        return $this->db->execute($sql, [$categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode]);
    }

// Giữ nguyên hàm updateProduct (10 tham số)
function updateProduct($id, $categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode) {
        $sql = "UPDATE products 
                SET category_id = ?, name = ?, price = ?, price_sale = ?, image = ?, description = ?, material = ?, brand = ?, sku_code = ? 
                WHERE id = ?";
        return $this->db->execute($sql, [$categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode, $id]);
    }

// Trong Models/Product.php

// 1. Hàm cập nhật trạng thái (thay thế logic DELETE)
function toggleProductStatus($id, $newStatus) {
    // Giả định bạn đã thêm cột 'status' (1: Hiển thị, 0: Ẩn) vào bảng products
    $sql = "UPDATE products SET status = ? WHERE id = ?";
    return $this->db->execute($sql, [$newStatus, $id]);
}

// 2. Hàm kiểm tra trùng tên sản phẩm (đã thêm vào productPost)
function checkProductNameExist($name, $excludeId = 0) {
    $sql = "SELECT id FROM products WHERE name = ? AND id != ?";
    return $this->db->queryOne($sql, [$name, $excludeId]);
}

    function countTotalProducts() {
        $sql = "SELECT COUNT(*) as total FROM products";
        $result = $this->db->queryOne($sql);
        return $result ? (int)$result['total'] : 0;
    }
    // Trong class Product { ...
// ... các hàm hiện có ...

/**
 * 1. Lấy Top sản phẩm bán chạy nhất (Best Seller)
 * Dựa trên tổng số lượng sản phẩm đã bán trong order_details
 */
// Trong class Product { ...

/**
 * Lấy Top sản phẩm bán chạy nhất (Best Seller)
 */
function getTopSellingProducts($limit = 10) {
    $sql = "SELECT p.id, p.name, p.image, p.price, p.price_sale, SUM(od.quantity) as sold_quantity
            FROM order_details od
            JOIN products p ON od.product_id = p.id
            GROUP BY p.id, p.name, p.image, p.price, p.price_sale
            ORDER BY sold_quantity DESC
            LIMIT ?";
    return $this->db->query($sql, [$limit]);
}

/**
 * Lấy Top sản phẩm bán chậm (Slow Moving)
 */
function getSlowSellingProducts($limit = 10) {
    $sql = "SELECT p.id, p.name, p.image, p.price, p.price_sale, COALESCE(SUM(od.quantity), 0) as sold_quantity
            FROM products p
            LEFT JOIN order_details od ON p.id = od.product_id
            GROUP BY p.id, p.name, p.image, p.price, p.price_sale
            HAVING sold_quantity > 0 
            ORDER BY sold_quantity ASC
            LIMIT ?";
    return $this->db->query($sql, [$limit]);
}
}


?>
