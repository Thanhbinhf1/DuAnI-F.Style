<?php
// File: Models/Order.php

class Order {
    private $db;

    function __construct() {
        $this->db = new Database(); // Yêu cầu Models/Database.php đã được include
    }

    // Lấy tất cả đơn hàng (cho trang Admin)
    function getAllOrders() {
        // Truy vấn cơ bản, bạn có thể bổ sung join với user name
        $sql = "SELECT * FROM orders ORDER BY order_date DESC";
        return $this->db->query($sql);
    }

    // Lấy chi tiết đơn hàng theo ID
    function getOrderDetail($order_id) {
        $sql = "SELECT 
                    od.*, p.name as product_name 
                FROM order_details od
                JOIN products p ON od.product_id = p.id
                WHERE od.order_id = ?";
        return $this->db->query($sql, [$order_id]);
    }
    // ... trong class User { ...
    function getAllUsers() {
        $sql = "SELECT id, username, email, role FROM users ORDER BY id ASC";
        return $this->db->query($sql);
    }
// }
}
?>