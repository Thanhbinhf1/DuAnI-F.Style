<?php
class AdminModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // =========================================================================
    // 1. QUẢN LÝ THỐNG KÊ (DASHBOARD - TỔNG QUAN)
    // =========================================================================
    
    public function getDashboardStats() {
        return [
            'products'   => $this->db->queryOne("SELECT COUNT(*) as total FROM products")['total'] ?? 0,
            'users'      => $this->db->queryOne("SELECT COUNT(*) as total FROM users")['total'] ?? 0,
            'new_orders' => $this->db->queryOne("SELECT COUNT(*) as total FROM orders WHERE status = 0")['total'] ?? 0,
            'income'     => $this->db->queryOne("SELECT SUM(total_money) as total FROM orders WHERE status = 2")['total'] ?? 0
        ];
    }

    public function getMonthlyIncome() {
        $sql = "SELECT DATE_FORMAT(created_at, '%m/%Y') as month, SUM(total_money) as income 
                FROM orders 
                WHERE status = 2 
                GROUP BY month 
                ORDER BY created_at DESC LIMIT 6";
        
        $data = $this->db->query($sql);
        
        // Normalize data for Chart.js
        $labels = [];
        $values = [];
        if ($data) {
            foreach (array_reverse($data) as $row) {
                $labels[] = "Tháng " . $row['month'];
                $values[] = $row['income'];
            }
        }
        return ['labels' => $labels, 'values' => $values];
    }

    public function getRecentActivityOrders() {
        $sql = "SELECT o.id, o.total_money, o.created_at, u.fullname 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                ORDER BY o.created_at DESC LIMIT 6";
        return $this->db->query($sql);
    }

    // =========================================================================
    // 2. QUẢN LÝ NGƯỜI DÙNG
    // =========================================================================

    public function getAllUsers() {
        $sql = "SELECT u.*, 
                       COUNT(o.id) as total_orders,
                       SUM(CASE WHEN o.status = 3 THEN 1 ELSE 0 END) as cancelled_orders
                FROM users u
                LEFT JOIN orders o ON u.id = o.user_id
                GROUP BY u.id
                ORDER BY u.id DESC";
        return $this->db->query($sql);
    }

    public function getUserInfo($id) {
        return $this->db->queryOne("SELECT * FROM users WHERE id = ?", [$id]);
    }

    public function getUserHistory($userId) {
        $sql = "SELECT o.*, 
                       GROUP_CONCAT(p.name SEPARATOR ', ') as product_summary,
                       COUNT(od.id) as item_count
                FROM orders o
                LEFT JOIN order_details od ON o.id = od.order_id
                LEFT JOIN products p ON od.product_id = p.id
                WHERE o.user_id = ?
                GROUP BY o.id
                ORDER BY o.created_at DESC";
        return $this->db->query($sql, [$userId]);
    }

    public function updateUserRole($id, $role) {
        $sql = "UPDATE users SET role = ? WHERE id = ?";
        return $this->db->execute($sql, [$role, $id]);
    }

    public function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }

    // =========================================================================
    // 3. QUẢN LÝ DANH MỤC
    // =========================================================================

    public function getAllCategories() {
        return $this->db->query("SELECT * FROM categories");
    }

    public function getCategoryById($id) {
        return $this->db->queryOne("SELECT * FROM categories WHERE id = ?", [$id]);
    }

    public function insertCategory($name, $status) {
        $sql = "INSERT INTO categories (name, status) VALUES (?, ?)";
        return $this->db->execute($sql, [$name, $status]);
    }

    public function updateCategory($id, $name, $status) {
        $sql = "UPDATE categories SET name = ?, status = ? WHERE id = ?";
        return $this->db->execute($sql, [$name, $status, $id]);
    }

    public function toggleCategoryStatus($id, $newStatus) {
        $sql = "UPDATE categories SET status = ? WHERE id = ?";
        return $this->db->execute($sql, [$newStatus, $id]);
    }

    // Kiểm tra trùng tên danh mục
    public function checkCategoryNameExist($name, $excludeId = 0) {
        $sql = "SELECT COUNT(*) as total FROM categories WHERE name = ? AND id != ?";
        $result = $this->db->queryOne($sql, [$name, $excludeId]);
        return ($result && $result['total'] > 0);
    }

    // =========================================================================
    // 4. QUẢN LÝ SẢN PHẨM
    // =========================================================================

    public function getAllProductsAdmin() {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.id DESC";
        return $this->db->query($sql);
    }

    public function getProductById($id) {
        return $this->db->queryOne("SELECT * FROM products WHERE id = ?", [$id]);
    }

    public function insertProduct($categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode) {
        $sql = "INSERT INTO products (category_id, name, price, price_sale, image, description, material, brand, sku_code) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        if ($this->db->execute($sql, [$categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode])) {
            return $this->db->getLastId();
        }
        return false;
    }

    public function updateProduct($id, $categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode) {
        if (!empty($image)) {
            $sql = "UPDATE products SET category_id=?, name=?, price=?, price_sale=?, image=?, description=?, material=?, brand=?, sku_code=? WHERE id=?";
            return $this->db->execute($sql, [$categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode, $id]);
        } else {
            $sql = "UPDATE products SET category_id=?, name=?, price=?, price_sale=?, description=?, material=?, brand=?, sku_code=? WHERE id=?";
            return $this->db->execute($sql, [$categoryId, $name, $price, $priceSale, $description, $material, $brand, $skuCode, $id]);
        }
    }

    public function toggleProductStatus($id, $newStatus) {
        $sql = "UPDATE products SET status = ? WHERE id = ?";
        return $this->db->execute($sql, [$newStatus, $id]);
    }

    public function countProductsByCategoryId($catId) {
        $result = $this->db->queryOne("SELECT COUNT(*) as total FROM products WHERE category_id = ?", [$catId]);
        return $result['total'] ?? 0;
    }

    public function checkProductNameExist($name, $excludeId = 0) {
        $sql = "SELECT COUNT(*) as total FROM products WHERE name = ? AND id != ?";
        $result = $this->db->queryOne($sql, [$name, $excludeId]);
        return ($result && $result['total'] > 0);
    }

    // --- Gallery Logic ---
    public function insertGalleryImages($productId, $imageUrls) {
        if (empty($imageUrls)) return true;
        foreach ($imageUrls as $url) {
            $this->db->execute("INSERT INTO product_images (product_id, image_url) VALUES (?, ?)", [$productId, $url]);
        }
        return true;
    }

    public function getGalleryImages($productId) {
        return $this->db->query("SELECT * FROM product_images WHERE product_id = ?", [$productId]);
    }

    public function deleteGalleryImages($productId) {
        return $this->db->execute("DELETE FROM product_images WHERE product_id = ?", [$productId]);
    }

    // =========================================================================
    // 5. QUẢN LÝ ĐƠN HÀNG
    // =========================================================================

    public function getAllOrders($status = null) {
        $sql = "SELECT o.*, u.fullname as user_fullname, u.email as user_email 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id";
        
        // Giữ nguyên logic lọc nếu bạn bấm vào các tab trạng thái
        if ($status !== null && $status !== 'all') {
            $sql .= " WHERE o.status = " . (int)$status;
        }
        
        // --- PHẦN SỬA ĐỔI ---
        // Logic sắp xếp:
        // 1. CASE WHEN o.status = 0 THEN 0 ELSE 1 END ASC: 
        //    -> Nếu đơn là "Chờ xác nhận" (0) thì gán ưu tiên 0 (lên đầu).
        //    -> Các đơn khác gán là 1 (xuống dưới).
        // 2. o.id ASC:
        //    -> Sắp xếp theo ID tăng dần (người mua trước có ID nhỏ hơn sẽ hiện trước).
        
        $sql .= " ORDER BY 
                  CASE WHEN o.status = 0 THEN 0 ELSE 1 END ASC, 
                  o.id ASC";
        
        return $this->db->query($sql);
    }

    public function getCancelledOrders() {
        $sql = "SELECT o.*, u.fullname as user_fullname, u.email as user_email 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE o.status = 3 
                ORDER BY o.id DESC";
        return $this->db->query($sql);
    }

    public function getOrderById($id) {
        return $this->db->queryOne("SELECT * FROM orders WHERE id = ?", [$id]);
    }

    public function getOrderDetails($orderId) {
        $sql = "SELECT od.*, p.name as product_name, p.image as product_image, p.sku_code 
                FROM order_details od
                LEFT JOIN products p ON od.product_id = p.id
                WHERE od.order_id = ?";
        return $this->db->query($sql, [$orderId]);
    }

    public function updateOrderStatus($id, $status) {
        return $this->db->execute("UPDATE orders SET status = ? WHERE id = ?", [$status, $id]);
    }

    public function updatePaymentStatus($id, $status) {
        return $this->db->execute("UPDATE orders SET payment_status = ? WHERE id = ?", [$status, $id]);
    }

    // =========================================================================
    // 6. THỐNG KÊ CHI TIẾT (Statistics Page)
    // =========================================================================

    // [CẬP NHẬT] Lấy Top sản phẩm bán chạy (có lọc theo ngày)
    public function getTopSellingProducts($limit = 10, $days = 30) {
        $sql = "SELECT p.name, 
                       SUM(od.quantity) as sold_quantity, 
                       MAX(o.created_at) as last_sale_date 
                FROM order_details od
                JOIN products p ON od.product_id = p.id
                JOIN orders o ON od.order_id = o.id
                WHERE o.status = 2 
                AND o.created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
                GROUP BY p.id
                ORDER BY sold_quantity DESC LIMIT $limit";
        // Nếu database class của bạn hỗ trợ tham số cho LIMIT thì dùng ?, nếu không thì nối chuỗi
        return $this->db->query($sql, [$days]);
    }

    // --- QUẢN LÝ BÌNH LUẬN ---
    function getAllComments() {
        $sql = "SELECT c.*, u.fullname, p.name as product_name, p.image as product_image
                FROM comments c
                JOIN users u ON c.user_id = u.id
                JOIN products p ON c.product_id = p.id
                ORDER BY c.date DESC";
        return $this->db->query($sql);
    }

    function deleteComment($id) {
        $sql = "DELETE FROM comments WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }
    // [CẬP NHẬT] Thống kê doanh thu & Trạng thái (Đã xóa các phần dư thừa)
    public function getSaleStatistics($days = 30) {
        // 1. Doanh thu (Lọc theo số ngày được chọn)
        $sqlDaily = "SELECT DATE(created_at) as date, SUM(total_money) as revenue 
                     FROM orders 
                     WHERE status = 2 AND created_at >= DATE_SUB(NOW(), INTERVAL $days DAY) 
                     GROUP BY DATE(created_at) 
                     ORDER BY date DESC"; 
        $daily = $this->db->query($sqlDaily);
        
        // 2. Tỷ lệ trạng thái (Lọc theo số ngày được chọn)
        $sqlStatus = "SELECT status, COUNT(*) as total 
                      FROM orders 
                      WHERE created_at >= DATE_SUB(NOW(), INTERVAL $days DAY)
                      GROUP BY status";
        $statusRatio = $this->db->query($sqlStatus);
        
        return [
            'daily_revenue' => $daily,
            'status_ratio' => $statusRatio,
        ];
    }
    // --- KHU VỰC XỬ LÝ BIẾN THỂ (SIZE/MÀU) ---

    // 1. Thêm biến thể mới
    function insertVariant($productId, $color, $size, $quantity, $price) {
        // Thêm cột price vào câu lệnh SQL
        $sql = "INSERT INTO product_variants (product_id, color, size, quantity, price) 
                VALUES (?, ?, ?, ?, ?)";
        
        return $this->db->execute($sql, [$productId, $color, $size, $quantity, $price]);
    }

    // 2. Xóa tất cả biến thể của sản phẩm (Dùng khi cập nhật sản phẩm)
    function deleteVariants($productId) {
        $sql = "DELETE FROM product_variants WHERE product_id = ?";
        return $this->db->execute($sql, [$productId]);
    }
    
    // 3. Lấy danh sách biến thể (Dùng để hiển thị lại khi Sửa sản phẩm)
     function getVariants($productId) {
        $sql = "SELECT * FROM product_variants WHERE product_id = ?";
        return $this->db->query($sql, [$productId]);
    }
}
?>