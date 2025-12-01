<?php
class Product {
    private $db;

    function __construct() {
        $this->db = new Database();
    }

    // 1. Hàng mới về (Lấy 4 cái mới nhất)
    function getNewProducts() {
        $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 4"; 
        return $this->db->query($sql);
    }

    // 2. Sản phẩm Hot (Lấy 4 cái có nhiều lượt xem nhất)
    function getHotProducts() {
        $sql = "SELECT * FROM products ORDER BY views DESC LIMIT 4";
        return $this->db->query($sql);
    }

    // 3. Sản phẩm Giá tốt (Lấy 4 cái giá rẻ nhất hoặc đang giảm giá)
    function getSaleProducts() {
        // Ưu tiên lấy những sản phẩm có giá < 200.000 hoặc sắp xếp giá tăng dần
        $sql = "SELECT * FROM products ORDER BY price ASC LIMIT 4";
        return $this->db->query($sql);
    }
    
    // Lấy chi tiết sản phẩm + tên danh mục
function getProductById($id) {
    $sql = "SELECT p.*, c.name AS category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.id = ?";
    return $this->db->queryOne($sql, [$id]);
}

    
    
    // Sản phẩm liên quan: cùng danh mục, khác id
function getRelatedProducts($categoryId, $excludeId) {
    $sql = "SELECT * FROM products 
            WHERE category_id = ? AND id != ?
            ORDER BY id DESC
            LIMIT 4";
    return $this->db->query($sql, [$categoryId, $excludeId]);
}


   function getProductVariants($productId) {
        $sql = "SELECT * FROM product_variants 
                WHERE product_id = ? 
                ORDER BY color, 
                FIELD(size, 'S', 'M', 'L', 'XL', 'XXL')"; 
        
        return $this->db->query($sql, [$productId]);
    }
    
    function checkStock($productId, $color, $size) {
        $sql = "SELECT quantity FROM product_variants 
                WHERE product_id = ? AND color = ? AND size = ?";
        $result = $this->db->queryOne($sql, [$productId, $color, $size]);
        
        // Trả về số lượng (nếu không tìm thấy thì trả về 0)
        return $result ? $result['quantity'] : 0;
    }
    // HÀM MỚI: Lấy sản phẩm theo Danh Mục (Category ID)
    function getProductsByCategory($catId) {
        $sql = "SELECT * FROM products WHERE category_id = ? ORDER BY id DESC";
        return $this->db->query($sql, [$catId]);
    }

    // HÀM MỚI: Lấy tất cả sản phẩm (Có phân trang nếu muốn, tạm thời lấy hết)
    function getAllProductsList() {
        $sql = "SELECT * FROM products ORDER BY id DESC";
        return $this->db->query($sql);
    }
    function getCategoryName($id) {
        $sql = "SELECT name FROM categories WHERE id = ?";
        $result = $this->db->queryOne($sql, [$id]);
        return $result ? $result['name'] : "";
    }

    function searchProducts($keyword, $categoryId = null) {
        $keyword = trim($keyword);

        // Nếu từ khóa rỗng -> trả về mảng rỗng cho chắc
        if ($keyword === '') {
            return [];
        }

        $like = '%' . $keyword . '%';

        if ($categoryId !== null) {
            $sql = "SELECT * FROM products 
                    WHERE category_id = ?
                      AND (name LIKE ? 
                           OR description LIKE ? 
                           OR brand LIKE ?
                           OR sku_code LIKE ?)
                    ORDER BY id DESC";

            return $this->db->query($sql, [
                $categoryId,
                $like, $like, $like, $like
            ]);
        } else {
            $sql = "SELECT * FROM products 
                    WHERE name LIKE ? 
                       OR description LIKE ? 
                       OR brand LIKE ?
                       OR sku_code LIKE ?
                    ORDER BY id DESC";

            return $this->db->query($sql, [
                $like, $like, $like, $like
            ]);
        }
    }

    function getVariantDetail($variantId) {
        $sql = "SELECT * FROM product_variants WHERE id = ?";
        return $this->db->queryOne($sql, [$variantId]);
    }
    function getProductImages($productId) {
    $sql = "SELECT * FROM product_images WHERE product_id = ? ORDER BY id ASC";
    return $this->db->query($sql, [$productId]);
}

// Lấy danh sách comment + thông tin user
function getCommentsByProduct($productId) {
    $sql = "SELECT c.*, u.fullname, u.username 
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.product_id = ?
            ORDER BY c.date DESC";
    return $this->db->query($sql, [$productId]);
}

// Thêm comment mới
function insertComment($productId, $userId, $content, $rating) {
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


}
?>