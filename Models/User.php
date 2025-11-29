<?php
class User {
    private $db;

    function __construct() {
        $this->db = new Database();
    }

    // 1. Kiểm tra user (Login)
    function checkUser($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        return $this->db->queryOne($sql, [$username]);
    }

    // 2. Đăng ký (Register)
    function insertUser($username, $password, $fullname, $email) {
        $sql = "INSERT INTO users(username, password, fullname, email) VALUES (?, ?, ?, ?)";
        return $this->db->execute($sql, [$username, $password, $fullname, $email]);
    }
    
    // 3. Kiểm tra tồn tại
    function checkUserExist($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        return $this->db->queryOne($sql, [$username]);
    }


    // 4. Cập nhật thông tin (Edit Profile)
    function updateUser($id, $fullname, $email, $phone, $address) {
        $sql = "UPDATE users SET fullname=?, email=?, phone=?, address=? WHERE id=?";
        return $this->db->execute($sql, [$fullname, $email, $phone, $address, $id]);
    }
    // --------------------------------------
}
?>