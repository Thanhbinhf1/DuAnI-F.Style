<?php
class User {
    private $db;

    function __construct() {
        $this->db = new Database();
    }

    // 1. Lấy user theo username
    function checkUser($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        return $this->db->queryOne($sql, [$username]);
    }

    // 2. Kiểm tra tồn tại username hoặc email
    function checkUserExist($username, $email) {
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        return $this->db->queryOne($sql, [$username, $email]);
    }

    // 3. Đăng ký (mật khẩu HASH)
    function insertUser($username, $password, $fullname, $email) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, fullname, email, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        return $this->db->execute($sql, [$username, $hashed, $fullname, $email]);
    }

    // 4. Cập nhật thông tin
    function updateUser($id, $fullname, $email, $phone, $address) {
        $sql = "UPDATE users SET fullname=?, email=?, phone=?, address=? WHERE id=?";
        return $this->db->execute($sql, [$fullname, $email, $phone, $address, $id]);
    }
}
?>
