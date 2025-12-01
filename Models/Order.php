<?php
class Order {
    private $db;

    function __construct() {
        $this->db = new Database();
    }

    // 1. Tạo đơn hàng mới (Lưu vào bảng orders)
   function createOrder($userId, $fullname, $phone, $address, $total, $payment, $note) {
        // payment_status mặc định là 0 (Chưa thanh toán)
        $sql = "INSERT INTO orders (user_id, fullname, phone, address, total_money, payment_method, payment_status, note, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, 0, ?, NOW())";
        
        $this->db->execute($sql, [$userId, $fullname, $phone, $address, $total, $payment, $note]);
        return $this->db->getLastId();
    }

    // THÊM HÀM NÀY: Để lấy thông tin đơn hàng hiển thị ra trang QR Code
    function getOrderById($id) {
        $sql = "SELECT * FROM orders WHERE id = ?";
        return $this->db->queryOne($sql, [$id]);
    }

    // 2. Lưu chi tiết đơn hàng (Lưu vào bảng order_details)
    function createOrderDetail($orderId, $productId, $qty, $price) {
        $sql = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $this->db->execute($sql, [$orderId, $productId, $qty, $price]);
    }

    // 3. Lấy lịch sử đơn hàng theo User ID
    function getOrdersByUser($userId) {
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC";
        return $this->db->query($sql, [$userId]);
    }

    // 4. Lấy chi tiết của 1 đơn hàng (Để xem cụ thể mua gì)
    function getOrderDetail($orderId) {
        $sql = "SELECT od.*, p.name, p.image 
                FROM order_details od 
                JOIN products p ON od.product_id = p.id 
                WHERE od.order_id = ?";
        return $this->db->query($sql, [$orderId]);
    }
}
?>