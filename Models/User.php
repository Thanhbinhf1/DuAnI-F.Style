<?php
class User {
    private $db;

    function __construct() {
        $this->db = new Database();
    }

    // 1. Khôi phục: Kiểm tra user khi login
    function checkUser($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        return $this->db->queryOne($sql, [$username]);
    }

    // 2. Khôi phục: Hàm login (FIX: Đảm bảo hàm này tồn tại cho loginPost)
    function login($username) {
        $sql = "SELECT id, username, password, fullname, email, role 
            FROM users WHERE username = ?";
        return $this->db->queryOne($sql, [$username]);
    }

    // 3. Khôi phục: Đăng ký tài khoản mới
    function insertUser($username, $password, $fullname, $email) {
        $sql = "INSERT INTO users (username, password, fullname, email, role)
                VALUES (?, ?, ?, ?, 0)"; // role = 0: khách
        return $this->db->execute($sql, [$username, $password, $fullname, $email]);
    }

    // 4. Khôi phục: Kiểm tra username đã tồn tại chưa
    function checkUserExist($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        return $this->db->queryOne($sql, [$username]);
    }

    // 5. Khôi phục: Cập nhật thông tin hồ sơ
    function updateUser($id, $fullname, $email, $phone, $address) {
        $sql = "UPDATE users 
                SET fullname = ?, email = ?, phone = ?, address = ?
                WHERE id = ?";
        return $this->db->execute($sql, [$fullname, $email, $phone, $address, $id]);
    }

    // ===================================
    // CHỨC NĂNG QUÊN MẬT KHẨU (BỔ SUNG)
    // ===================================

    // 6. Bổ sung: Tìm kiếm user bằng email
    function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        return $this->db->queryOne($sql, [$email]);
    }

    // 7. Bổ sung: Lưu MÃ XÁC NHẬN (Code) và thời gian hết hạn vào DB
    function setResetCode($id, $code, $expiry) {
        $sql = "UPDATE users SET reset_token = ?, token_expiry = ? WHERE id = ?";
        return $this->db->execute($sql, [$code, $expiry, $id]);
    }

    // 8. Bổ sung: FIX LỖI TIMEZONE: Truyền thời gian hiện tại từ PHP
    function getUserByCodeAndEmail($code, $email, $currentTime) {
        // Kiểm tra reset_token khớp, email khớp (hoặc cờ ?) VÀ thời gian CHƯA HẾT HẠN
        // Dùng tham số thứ 3 (isEmailEmpty) để cho phép kiểm tra mã trong hàm resetPassword mà không cần kiểm tra email (tránh lỗi email = '')
        $sql = "SELECT * FROM users WHERE reset_token = ? AND (email = ? OR ?) AND token_expiry > ?";
        
        // Nếu email rỗng hoặc là 'temp@email.com', đặt cờ là 1 (True) để bỏ qua điều kiện email trong SQL
        $isEmailEmpty = (empty($email) || $email === 'temp@email.com') ? 1 : 0;
        
        return $this->db->queryOne($sql, [$code, $email, $isEmailEmpty, $currentTime]);
    }
    
    // 9. Bổ sung: Cập nhật mật khẩu và xóa code/token
    function updatePasswordByCode($code, $newHashedPassword) {
        $sql = "UPDATE users 
                SET password = ?, reset_token = NULL, token_expiry = NULL 
                WHERE reset_token = ?";
        return $this->db->execute($sql, [$newHashedPassword, $code]);
    }

    // 10. Khôi phục: Lấy tất cả user (Admin)
    function getAllUsers() {
        $sql = "SELECT * FROM users";
        return $this->db->query($sql);
    }

    // 11. Khôi phục: Xoá user (Admin)
    function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }

    // 12. Khôi phục: Cập nhật vai trò (Admin)
    function updateUserRole($id, $role) {
        $sql = "UPDATE users SET role = ? WHERE id = ?";
        return $this->db->execute($sql, [(int)$role, $id]);
    }

    // 13. Khôi phục: BỔ SUNG CHO DASHBOARD
    function countTotalUsers() {
        $sql = "SELECT COUNT(*) as total FROM users";
        $result = $this->db->queryOne($sql);
        return $result ? (int)$result['total'] : 0;
    }
}
?>