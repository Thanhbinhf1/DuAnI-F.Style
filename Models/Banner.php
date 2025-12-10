<?php
class Banner {
    private $db;

    function __construct() {
        $this->db = new Database();
    }

    // Lấy tất cả cho Admin
    function getAllBanners() {
        $sql = "SELECT * FROM banners ORDER BY id DESC";
        return $this->db->query($sql);
    }

    // Lấy banner đang hiện cho Trang chủ
    function getActiveBanners() {
        $sql = "SELECT * FROM banners WHERE status = 1 ORDER BY id DESC";
        return $this->db->query($sql);
    }

    function getBannerById($id) {
        $sql = "SELECT * FROM banners WHERE id = ?";
        return $this->db->queryOne($sql, [$id]);
    }

    function insertBanner($title, $image, $link, $status) {
        $sql = "INSERT INTO banners (title, image, link, status) VALUES (?, ?, ?, ?)";
        return $this->db->execute($sql, [$title, $image, $link, $status]);
    }

    function updateBanner($id, $title, $image, $link, $status) {
        $sql = "UPDATE banners SET title = ?, image = ?, link = ?, status = ? WHERE id = ?";
        return $this->db->execute($sql, [$title, $image, $link, $status, $id]);
    }

    function deleteBanner($id) {
        $sql = "DELETE FROM banners WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }
    // Hàm cập nhật nhanh trạng thái Ẩn/Hiện
    function updateStatus($id, $status) {
        $sql = "UPDATE banners SET status = ? WHERE id = ?";
        return $this->db->execute($sql, [$status, $id]);
    }
}
?>