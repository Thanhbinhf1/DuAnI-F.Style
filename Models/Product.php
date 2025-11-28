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
    
    // Lấy chi tiết (giữ nguyên)
    function getProductById($id) {
        $sql = "SELECT * FROM products WHERE id = $id";
        return $this->db->queryOne($sql);
    }
    
    // Sản phẩm liên quan (giữ nguyên)
    function getRelatedProducts($categoryId, $excludeId) {
        $sql = "SELECT * FROM products WHERE category_id = $categoryId AND id != $excludeId LIMIT 4";
        return $this->db->query($sql);
    }

    function getProductVariants($productId) {
        $sql = "SELECT * FROM product_variants WHERE product_id = ? ORDER BY color, size";
        return $this->db->query($sql, [$productId]);
    }
    
    function checkStock($productId, $color, $size) {
        $sql = "SELECT quantity FROM product_variants 
                WHERE product_id = ? AND color = ? AND size = ?";
        $result = $this->db->queryOne($sql, [$productId, $color, $size]);
        
        // Trả về số lượng (nếu không tìm thấy thì trả về 0)
        return $result ? $result['quantity'] : 0;
    }
}
?>