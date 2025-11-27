<?php
class Product {
    private $db;

    function __construct() {
        $this->db = new Database();
    }

    // Lấy danh sách sản phẩm mới (Đã có)
    function getNewProducts() {
        $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 8"; 
        return $this->db->query($sql);
    }

    // --- THÊM HÀM NÀY: Lấy chi tiết 1 sản phẩm theo ID ---
    function getProductById($id) {
        // Dùng tham số $id để lọc đúng sản phẩm cần tìm
        $sql = "SELECT * FROM products WHERE id = $id";
        return $this->db->queryOne($sql);
    }
    
    // --- THÊM HÀM NÀY (Optional): Lấy sản phẩm liên quan (cùng danh mục) ---
    function getRelatedProducts($categoryId, $excludeId) {
        $sql = "SELECT * FROM products WHERE category_id = $categoryId AND id != $excludeId LIMIT 4";
        return $this->db->query($sql);
    }
}
?>