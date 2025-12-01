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

    function searchProducts($keyword) {
        $sql = "SELECT * FROM products WHERE name LIKE ? ORDER BY id DESC";
        // Thêm dấu % để tìm tương đối (Ví dụ: %Jean% sẽ tìm thấy "Quần Jean Đẹp")
        return $this->db->query($sql, ['%' . $keyword . '%']);
    }

    function getVariantDetail($variantId) {
        $sql = "SELECT * FROM product_variants WHERE id = ?";
        return $this->db->queryOne($sql, [$variantId]);
    }
   
}
?>