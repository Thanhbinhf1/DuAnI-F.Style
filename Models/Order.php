<?php
// Models/Order.php
class Order {
    private $db;

    function __construct() {
        $this->db = new Database();
    }

    function getAllOrders() {
        // Lấy tất cả đơn hàng, join với users để lấy tên khách hàng
        $sql = "SELECT o.*, u.fullname as user_fullname 
                FROM orders o
                JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC";
        return $this->db->query($sql);
    }

    function getOrderDetail($orderId) {
        // Lấy thông tin đơn hàng chính và người dùng
        $sqlOrder = "SELECT o.*, u.fullname as user_fullname, u.email, u.phone as user_phone, u.address as user_address
                     FROM orders o
                     JOIN users u ON o.user_id = u.id
                     WHERE o.id = ?";
        $order = $this->db->queryOne($sqlOrder, [$orderId]);
        
        // Lấy chi tiết sản phẩm trong đơn
        $sqlDetails = "SELECT od.*, p.name as product_name, p.image as product_image
                       FROM order_details od
                       JOIN products p ON od.product_id = p.id
                       WHERE od.order_id = ?";
        $details = $this->db->query($sqlDetails, [$orderId]);
        
        return ['order' => $order, 'details' => $details];
    }
    
    function updateOrderStatus($orderId, $status) {
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        return $this->db->execute($sql, [$status, $orderId]);
    }
}
?>