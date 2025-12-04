<?php
class AdminModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // =========================================================================
    // 1. QUẢN LÝ THỐNG KÊ (DASHBOARD)
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
        
        // Chuẩn hóa dữ liệu cho biểu đồ
        $labels = [];
        $values = [];
        foreach (array_reverse($data) as $row) {
            $labels[] = "Tháng " . $row['month'];
            $values[] = $row['income'];
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
        // Lấy danh sách user kèm thống kê số đơn hàng và số đơn hủy
        $sql = "SELECT u.*, 
                       COUNT(o.id) as total_orders,
                       SUM(CASE WHEN o.status = 3 THEN 1 ELSE 0 END) as cancelled_orders
                FROM users u
                LEFT JOIN orders o ON u.id = o.user_id
                GROUP BY u.id
                ORDER BY u.id DESC";
        return $this->db->query($sql);
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

    public function deleteCategory($id) {
        $sql = "DELETE FROM categories WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }

    // =========================================================================
    // 4. QUẢN LÝ SẢN PHẨM
    // =========================================================================

    public function getAllProductsAdmin() {
        // Lấy sản phẩm kèm tên danh mục
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
            return $this->db->getLastId(); // Trả về ID vừa thêm để xử lý gallery
        }
        return false;
    }

    public function updateProduct($id, $categoryId, $name, $price, $priceSale, $image, $description, $material, $brand, $skuCode) {
        // Nếu có ảnh mới thì cập nhật, không thì giữ nguyên
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

    // --- Xử lý Gallery ảnh ---
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

    public function getTopSellingProducts($limit = 10) {
        $sql = "SELECT p.name, SUM(od.quantity) as sold_quantity 
                FROM order_details od
                JOIN products p ON od.product_id = p.id
                JOIN orders o ON od.order_id = o.id
                WHERE o.status = 2 
                GROUP BY p.id
                ORDER BY sold_quantity DESC LIMIT $limit";
        return $this->db->query($sql);
    }

    // =========================================================================
    // 5. QUẢN LÝ ĐƠN HÀNG
    // =========================================================================

    public function getAllOrders() {
        $sql = "SELECT o.*, u.fullname as user_fullname, u.email as user_email 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
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

    // Thống kê chi tiết cho trang Statistics
    public function getSaleStatistics() {
        // 1. Doanh thu 7 ngày
        $daily = $this->db->query("SELECT DATE(created_at) as date, SUM(total_money) as revenue FROM orders WHERE status = 2 GROUP BY DATE(created_at) ORDER BY date DESC LIMIT 7");
        
        // 2. Tỷ lệ trạng thái
        $statusRatio = $this->db->query("SELECT status, COUNT(*) as total FROM orders GROUP BY status");
        
        // 3. Top danh mục
        $catRev = $this->db->query("SELECT c.name as category_name, SUM(od.price * od.quantity) as revenue 
                                    FROM order_details od 
                                    JOIN products p ON od.product_id = p.id 
                                    JOIN categories c ON p.category_id = c.id 
                                    JOIN orders o ON od.order_id = o.id 
                                    WHERE o.status = 2 
                                    GROUP BY c.name ORDER BY revenue DESC LIMIT 5");
                                    
        // 4. Khách hàng
        $provinces = $this->db->query("SELECT TRIM(SUBSTRING_INDEX(address, ',', -1)) as province, COUNT(*) as count FROM orders GROUP BY province ORDER BY count DESC LIMIT 5");
        
        return [
            'daily_revenue' => $daily,
            'status_ratio' => $statusRatio,
            'revenue_by_category' => $catRev,
            'orders_by_province' => $provinces,
            'customer_type_stats' => [] // Có thể bổ sung sau nếu cần query phức tạp
        ];
        
    }
    public function getUserInfo($id) {
        return $this->db->queryOne("SELECT * FROM users WHERE id = ?", [$id]);
    }

    // [MỚI] Lấy lịch sử đơn hàng của 1 user (Kèm tên sản phẩm tóm tắt)
    public function getUserHistory($userId) {
        // Query này dùng GROUP_CONCAT để gộp tên các sản phẩm trong 1 đơn hàng vào 1 dòng
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
}

?>