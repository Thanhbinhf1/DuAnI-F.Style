<?php
class Order {
    private $db;

    function __construct() {
        $this->db = new Database();
    }

    // 1. Tạo đơn hàng mới (lưu vào bảng orders)
    function createOrder($userId, $fullname, $phone, $address, $total, $payment, $note) {
        // payment_status, status, created_at dùng giá trị mặc định trong DB
        $sql = "INSERT INTO orders (user_id, total_money, payment_method, fullname, address, note, phone)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $this->db->execute($sql, [$userId, $total, $payment, $fullname, $address, $note, $phone]);
        return $this->db->getLastId();
    }

    // 2. Lấy thông tin đơn hàng theo id (dùng cho trang QR, lịch sử...)
    function getOrderById($id) {
        $sql = "SELECT * FROM orders WHERE id = ?";
        return $this->db->queryOne($sql, [$id]);
    }


    function createOrderDetail($orderId, $productId, $qty, $price) {
        $sql = "INSERT INTO order_details (order_id, product_id, quantity, price)
                VALUES (?, ?, ?, ?)";
        $this->db->execute($sql, [$orderId, $productId, $qty, $price]);
    }

    function getOrdersByUser($userId) {
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC";
        return $this->db->query($sql, [$userId]);
    }

    function getOrderDetail($orderId) {
        $sql = "SELECT od.*, p.name, p.image 
                FROM order_details od 
                JOIN products p ON od.product_id = p.id 
                WHERE od.order_id = ?";
        return $this->db->query($sql, [$orderId]);
    }
}
?>
<?php
