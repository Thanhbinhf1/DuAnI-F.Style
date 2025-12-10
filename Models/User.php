<?php
class User {
    private $db;

    function __construct() {
        $this->db = new Database();
    }

    // 1. Kiểm tra user khi login
    function checkUser($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        return $this->db->queryOne($sql, [$username]);
    }
    function login($username) {
        $sql = "SELECT id, username, password, fullname, email, role 
            FROM users WHERE username = ?";
    return $this->db->queryOne($sql, [$username]);
}

    // 2. Đăng ký tài khoản mới
    function insertUser($username, $password, $fullname, $email) {
        $sql = "INSERT INTO users (username, password, fullname, email, role)
                VALUES (?, ?, ?, ?, 0)"; // role = 0: khách
        return $this->db->execute($sql, [$username, $password, $fullname, $email]);
    }

    // 3. Kiểm tra username đã tồn tại chưa
    function checkUserExist($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        return $this->db->queryOne($sql, [$username]);
    }

    // 4. Cập nhật thông tin hồ sơ
    function updateUser($id, $fullname, $email, $phone, $address) {
        $sql = "UPDATE users 
                SET fullname = ?, email = ?, phone = ?, address = ?
                WHERE id = ?";
        return $this->db->execute($sql, [$fullname, $email, $phone, $address, $id]);
    }

    // BỔ SUNG: Tìm kiếm user bằng email
    function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        return $this->db->queryOne($sql, [$email]);
    }

    // BỔ SUNG: Lưu MÃ XÁC NHẬN (Code) và thời gian hết hạn vào DB
    function setResetCode($id, $code, $expiry) {
        // Giả định bạn đã thêm 2 cột reset_token và token_expiry vào bảng users
        $sql = "UPDATE users SET reset_token = ?, token_expiry = ? WHERE id = ?";
        return $this->db->execute($sql, [$code, $expiry, $id]);
    }

    // BỔ SUNG: Tìm user bằng MÃ VÀ EMAIL và kiểm tra thời gian hết hạn
    function getUserByCodeAndEmail($code, $email) {
        $sql = "SELECT * FROM users WHERE reset_token = ? AND email = ? AND token_expiry > NOW()";
        return $this->db->queryOne($sql, [$code, $email]);
    }
    
    // BỔ SUNG: Cập nhật mật khẩu và xóa code/token
    function updatePasswordByCode($code, $newHashedPassword) {
        $sql = "UPDATE users 
                SET password = ?, reset_token = NULL, token_expiry = NULL 
                WHERE reset_token = ?";
        return $this->db->execute($sql, [$newHashedPassword, $code]);
    }

    // Lấy tất cả user
    function getAllUsers() {
        $sql = "SELECT * FROM users";
        return $this->db->query($sql);
    }

    // Xoá user
    function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }

    // Cập nhật vai trò (admin / user)
    function updateUserRole($id, $role) {
        $sql = "UPDATE users SET role = ? WHERE id = ?";
        return $this->db->execute($sql, [(int)$role, $id]);
    }

    // ===================================
    //  BỔ SUNG CHO DASHBOARD
    // ===================================
    function countTotalUsers() {
        $sql = "SELECT COUNT(*) as total FROM users";
        $result = $this->db->queryOne($sql);
        return $result ? (int)$result['total'] : 0;
    }
}
?>