<?php
class User {
    private $db;

    function __construct() {
        $this->db = new Database();
    }

    // 1. Kiểm tra user (Chỉ lấy user ra, việc so sánh mật khẩu để Controller làm)
    function checkUser($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        return $this->db->queryOne($sql, [$username]);
    }

    // 2. Đăng ký (Mật khẩu sẽ được mã hóa bên Controller trước khi gửi vào đây)
    function insertUser($username, $password, $fullname, $email) {
        $sql = "INSERT INTO users(username, password, fullname, email) VALUES (?, ?, ?, ?)";
        return $this->db->execute($sql, [$username, $password, $fullname, $email]);
    }
    
    // 3. Kiểm tra tồn tại
    function checkUserExist($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        return $this->db->queryOne($sql, [$username]);
    }
    
// thanhbinhf1/duani-f.style/DuAnI-F.Style-Quy/Models/User.php

class User {
    private $db;

    function __construct() {
        $this->db = new Database();
    }
    
    // ... (Các hàm cũ)

    // --- ADMIN USER FUNCTIONS ---

    function getAllUsers() {
        // Lấy tất cả trừ tài khoản Admin (role=1) nếu bạn không muốn hiện
        // Tùy chọn: WHERE role = 0 
        $sql = "SELECT * FROM users ORDER BY created_at DESC"; 
        return $this->db->query($sql);
    }
    
    function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }
}

}
?>