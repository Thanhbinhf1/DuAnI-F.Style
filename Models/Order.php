<?php
// File: Models/Order.php
include_once 'Database.php';

class Order {
    private $db;

    function __construct() {
        $this->db = new Database();
    }

    // Lấy danh sách đơn hàng cho Admin
    function getAllOrdersAdmin() {
        $sql = "SELECT o.*, u.fullname as customer_name, u.email as customer_email
                FROM orders o
                JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC";
        return $this->db->query($sql);
    }

    // Lấy chi tiết đơn hàng
    function getOrderDetailAdmin($order_id) {
        $sql = "SELECT od.*, p.name as product_name, p.image as product_image
                FROM order_details od
                JOIN orders o ON od.order_id = o.id
                JOIN products p ON od.product_id = p.id
                WHERE od.order_id = ?";
        return $this->db->query($sql, [$order_id]);
    }
    
    // Lấy thông tin chung của đơn hàng
    function getOrderInfo($order_id) {
         $sql = "SELECT * FROM orders WHERE id = ?";
         return $this->db->queryOne($sql, [$order_id]);
    }
    
    // Cập nhật trạng thái đơn hàng
    function updateOrderStatus($order_id, $new_status) {
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        return $this->db->execute($sql, [$new_status, $order_id]);
    }
}
?>