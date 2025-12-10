<?php
class User {
    function updateAddress($id, $province_id, $district_id, $ward_id, $street, $full_address) {
        // Lưu cả ID (để auto fill) và Full Address (để hiển thị nhanh)
        $sql = "UPDATE users SET 
                province_id = ?, 
                district_id = ?, 
                ward_id = ?, 
                street_address = ?, 
                address = ? 
                WHERE id = ?";
        return $this->db->execute($sql, [$province_id, $district_id, $ward_id, $street, $full_address, $id]);
    }
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