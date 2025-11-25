<?php
class User {
    private $db;

    function __construct() {
        $this->db = new Database();
    }

    // 1. Kiểm tra đăng nhập
    function checkUser($username, $password) {
        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        return $this->db->queryOne($sql);
    }

    // 2. Đăng ký tài khoản mới
    function insertUser($username, $password, $fullname, $email) {
        $sql = "INSERT INTO users(username, password, fullname, email) 
                VALUES ('$username', '$password', '$fullname', '$email')";
        return $this->db->execute($sql);
    }
    
    // 3. Kiểm tra user đã tồn tại chưa (tránh trùng tên)
    function checkUserExist($username) {
        $sql = "SELECT * FROM users WHERE username='$username'";
        return $this->db->queryOne($sql);
    }
}
?>