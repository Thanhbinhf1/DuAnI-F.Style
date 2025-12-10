<?php
class User {
    private $db;

    function __construct() {
        $this->db = new Database();
    }

    // ... (Các hàm cũ giữ nguyên) ...

    // BỔ SUNG: Tìm kiếm user bằng email
    function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        return $this->db->queryOne($sql, [$email]);
    }

    // BỔ SUNG: Lưu MÃ XÁC NHẬN (Code) và thời gian hết hạn vào DB
    function setResetCode($id, $code, $expiry) {
        // Giả định bạn đã thêm 2 cột reset_token và token_expiry
        $sql = "UPDATE users SET reset_token = ?, token_expiry = ? WHERE id = ?";
        return $this->db->execute($sql, [$code, $expiry, $id]);
    }

    // BỔ SUNG: Fix Lỗi Lệch Múi Giờ bằng cách truyền thời gian hiện tại từ PHP
    function getUserByCodeAndEmail($code, $email, $currentTime) {
        // Kiểm tra reset_token trùng KHỚP, email trùng KHỚP (hoặc email trống nếu chỉ kiểm tra mã) và thời gian CHƯA HẾT HẠN
        $sql = "SELECT * FROM users WHERE reset_token = ? AND (email = ? OR ?) AND token_expiry > ?";
        
        // Nếu email rỗng, ta sử dụng cờ 1 để bỏ qua điều kiện email (cho hàm resetPassword)
        $isEmailEmpty = empty($email) ? 1 : 0;
        
        return $this->db->queryOne($sql, [$code, $email, $isEmailEmpty, $currentTime]);
    }
    
    // BỔ SUNG: Cập nhật mật khẩu và xóa code/token
    function updatePasswordByCode($code, $newHashedPassword) {
        $sql = "UPDATE users 
                SET password = ?, reset_token = NULL, token_expiry = NULL 
                WHERE reset_token = ?";
        return $this->db->execute($sql, [$newHashedPassword, $code]);
    }

    // ... (Các hàm cũ giữ nguyên) ...
}
?>