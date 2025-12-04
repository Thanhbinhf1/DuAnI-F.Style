<?php
class Wishlist {
    private $db;

    function __construct() {
        $this->db = new Database();
    }

    // Thêm vào yêu thích
    function add($userId, $productId) {
        // Kiểm tra xem đã tồn tại chưa để tránh lỗi trùng lặp
        $sqlCheck = "SELECT * FROM wishlists WHERE user_id = ? AND product_id = ?";
        $exists = $this->db->queryOne($sqlCheck, [$userId, $productId]);
        
        if (!$exists) {
            $sql = "INSERT INTO wishlists (user_id, product_id) VALUES (?, ?)";
            return $this->db->execute($sql, [$userId, $productId]);
        }
        return true; 
    }

    // Xóa khỏi yêu thích
    function remove($userId, $productId) {
        $sql = "DELETE FROM wishlists WHERE user_id = ? AND product_id = ?";
        return $this->db->execute($sql, [$userId, $productId]);
    }

    // Lấy danh sách yêu thích của user (kèm thông tin sản phẩm)
    function getWishlistByUser($userId) {
        $sql = "SELECT p.id, p.name, p.image, p.price, p.sku_code as sku
                FROM wishlists w
                JOIN products p ON w.product_id = p.id
                WHERE w.user_id = ?
                ORDER BY w.created_at DESC";
        return $this->db->query($sql, [$userId]);
    }
}
?>