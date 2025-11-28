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
   
}
?>