<?php
// thanhbinhf1/duani-f.style/DuAnI-F.Style-Quy/Models/Product.php

class Product {
    private $db;

    function __construct() {
        $this->db = new Database();
    }

    // --- FRONT-END FUNCTIONS (Đã có) ---
    function getNewProducts() {
        $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 4"; 
        return $this->db->query($sql);
    }
    //... (Các hàm khác)
    function getCategoryName($id) {
        $sql = "SELECT name FROM categories WHERE id = ?";
        $result = $this->db->queryOne($sql, [$id]);
        return $result ? $result['name'] : "";
    }
    function getVariantDetail($variantId) {
        $sql = "SELECT * FROM product_variants WHERE id = ?";
        return $this->db->queryOne($sql, [$variantId]);
    }

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

    function insertProduct($categoryId, $name, $price, $image, $description, $material, $brand, $skuCode) {
        $sql = "INSERT INTO products(category_id, name, price, image, description, material, brand, sku_code) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        return $this->db->execute($sql, [$categoryId, $name, $price, $image, $description, $material, $brand, $skuCode]);
    }

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