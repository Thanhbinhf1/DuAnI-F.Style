<?php
class Order {
    private $db;

    function __construct() {
        $this->db = new Database();
    }

    function countNewOrders() {
        $sql = "SELECT COUNT(*) as total FROM orders WHERE status = 0";
        $result = $this->db->queryOne($sql);
        return $result ? (int)$result['total'] : 0;
    }

    function calculateTotalIncome() {
        $sql = "SELECT SUM(total_money) as total FROM orders WHERE payment_status = 1";
        $result = $this->db->queryOne($sql);
        return $result ? (float)$result['total'] : 0;
    }

    function getMonthlyIncome() {
        $sql = "SELECT MONTH(created_at) as month, SUM(total_money) as income
                FROM orders 
                WHERE payment_status = 1 AND YEAR(created_at) = YEAR(CURDATE())
                GROUP BY MONTH(created_at)";
        return $this->db->query($sql);
    }

    function getRecentActivityOrders() {
        $sql = "SELECT o.id, o.total_money, o.created_at, u.fullname
                FROM orders o
                JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC
                LIMIT 5";
        return $this->db->query($sql);
    }

    function getAllOrders() {
        $sql = "SELECT o.*, u.fullname as user_fullname 
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC";
        return $this->db->query($sql);
    }
    

    function getOrderById($id) {
        $sql = "SELECT * FROM orders WHERE id = ?";
        return $this->db->queryOne($sql, [$id]);
    }

    function getOrderDetails($orderId) {
        $sql = "SELECT od.*, p.name as product_name, p.image as product_image
                FROM order_details od
                JOIN products p ON od.product_id = p.id
                WHERE od.order_id = ?";
        return $this->db->query($sql, [$orderId]);
    }

    function updateOrderStatus($orderId, $newStatus) {
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        return $this->db->execute($sql, [$newStatus, $orderId]);
    }

    function updatePaymentStatus($orderId, $status) {
        $sql = "UPDATE orders SET payment_status = ? WHERE id = ?";
        return $this->db->execute($sql, [$status, $orderId]);
    }
    
}
?>