<?php
class Product {
    private $db;

    function __construct() {
        $this->db = new Database();
    }

    function getNewProducts() {
        // Giả sử bảng của bạn tên là 'products'
        $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 8"; 
        return $this->db->query($sql);
    }
}
?>