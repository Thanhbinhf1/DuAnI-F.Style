<?php
class Order {
    private $db;

    function __construct() {
        $this->db = new Database();
    }

    function countNewOrders() {
        $sql = "SELECT COUNT(*) as total FROM orders WHERE status = 0";
        $result = $this->db->queryOne($sql);
        return $result ? (int)$result['total'] : 0;
    }

    function calculateTotalIncome() {
        $sql = "SELECT SUM(total_money) as total FROM orders WHERE payment_status = 1";
        $result = $this->db->queryOne($sql);
        return $result ? (float)$result['total'] : 0;
    }

    function getMonthlyIncome() {
        $sql = "SELECT MONTH(created_at) as month, SUM(total_money) as income
                FROM orders 
                WHERE payment_status = 1 AND YEAR(created_at) = YEAR(CURDATE())
                GROUP BY MONTH(created_at)";
        return $this->db->query($sql);
    }

    function getRecentActivityOrders() {
        $sql = "SELECT o.id, o.total_money, o.created_at, u.fullname
                FROM orders o
                JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC
                LIMIT 5";
        return $this->db->query($sql);
    }

    function getAllOrders() {
       $sql = "SELECT o.*, u.fullname AS user_fullname, COALESCE(od.item_count, 0) AS item_count
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                LEFT JOIN (
                    SELECT order_id, SUM(quantity) AS item_count
                    FROM order_details
                    GROUP BY order_id
                ) od ON o.id = od.order_id
                ORDER BY o.created_at DESC";
        return $this->db->query($sql);
    }
    function getOrdersByUserId($userId) {
        $sql = "SELECT *
                FROM orders
                WHERE user_id = ?
                ORDER BY created_at DESC";
        return $this->db->query($sql, [$userId]);
    }


    function createOrder($userId, $fullname, $phone, $address, $totalMoney, $paymentMethod, $note = '') {
        $sql = "INSERT INTO orders (user_id, fullname, phone, address, total_money, payment_method, note, status, payment_status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, 0, 0, NOW())";

        $result = $this->db->execute($sql, [
            $userId,
            $fullname,
            $phone,
            $address,
            $totalMoney,
            $paymentMethod,
            $note
        ]);

        if ($result) {
            return (int)$this->db->getLastId();
        }

        return 0;
    }

    function createOrderDetail($orderId, $productId, $quantity, $price) {
        $sql = "INSERT INTO order_details (order_id, product_id, quantity, price)
                VALUES (?, ?, ?, ?)";
        return $this->db->execute($sql, [$orderId, $productId, $quantity, $price]);
    }
    

    function getOrderById($id) {
        $sql = "SELECT * FROM orders WHERE id = ?";
        return $this->db->queryOne($sql, [$id]);
    }
    

    function getOrderDetails($orderId) {
        $sql = "SELECT od.*, p.name as product_name, p.image as product_image
                FROM order_details od
                JOIN products p ON od.product_id = p.id
                WHERE od.order_id = ?";
        return $this->db->query($sql, [$orderId]);
    }

    function updateOrderStatus($orderId, $newStatus) {
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        return $this->db->execute($sql, [$newStatus, $orderId]);
    }

    function updatePaymentStatus($orderId, $status) {
        $sql = "UPDATE orders SET payment_status = ? WHERE id = ?";
        return $this->db->execute($sql, [$status, $orderId]);
    }
    // Trong class Order { ...
// ... các hàm hiện có ...

/**
 * Thống kê chi tiết các chỉ số Doanh thu và Đơn hàng
 * @return array Chứa các mảng dữ liệu thống kê
 */
function getSaleStatistics() {
    // 1. Doanh thu theo Ngày (7 ngày gần nhất)
    // Dữ liệu này có thể dùng để vẽ biểu đồ Line Chart
    $sqlDailyRevenue = "SELECT DATE(created_at) as date, SUM(total_money) as revenue
                        FROM orders
                        WHERE payment_status = 1 
                        GROUP BY DATE(created_at)
                        ORDER BY date DESC
                        LIMIT 7";
    $dailyRevenue = $this->db->query($sqlDailyRevenue);
    
    // 2. Tỷ lệ Trạng thái Đơn hàng (Đã hoàn thành / Đang giao / Hủy)
    // Dữ liệu này có thể dùng để vẽ biểu đồ Pie Chart
    $sqlStatusRatio = "SELECT status, COUNT(*) as total
                       FROM orders
                       GROUP BY status";
    $statusRatio = $this->db->query($sqlStatusRatio);
    
    // 3. Doanh thu theo Danh mục
    // Yêu cầu JOIN 4 bảng: orders -> order_details -> products -> categories
    $sqlRevenueByCategory = "SELECT c.name as category_name, SUM(od.price * od.quantity) as revenue
                             FROM order_details od
                             JOIN products p ON od.product_id = p.id
                             JOIN categories c ON p.category_id = c.id
                             JOIN orders o ON od.order_id = o.id
                             WHERE o.payment_status = 1  /* Chỉ tính đơn đã thanh toán */
                             GROUP BY c.name
                             ORDER BY revenue DESC";
    $revenueByCategory = $this->db->query($sqlRevenueByCategory);
    
    // 4. Đơn hàng theo Tỉnh/Thành phố
    // Giả định địa chỉ được nhập: [Chi tiết], [Phường/Xã], [Quận/Huyện], [Tỉnh/Thành phố]
    $sqlOrdersByProvince = "SELECT TRIM(SUBSTRING_INDEX(address, ',', -1)) as province, COUNT(*) as count
                            FROM orders
                            GROUP BY province
                            ORDER BY count DESC
                            LIMIT 5";
    $ordersByProvince = $this->db->query($sqlOrdersByProvince);
    
    // 5. Thống kê Khách hàng mới vs Khách hàng cũ
    // Đây là thống kê phức tạp, cần dùng Subquery hoặc Common Table Expression.
    // Cách đơn giản hơn: Thống kê số lượng đơn hàng đầu tiên của mỗi user trong tháng.
    $sqlCustomerType = "SELECT 
                            CASE 
                                WHEN (SELECT COUNT(id) FROM orders WHERE user_id = o.user_id AND id <= o.id) = 1 THEN 'New'
                                ELSE 'Returning'
                            END as customer_type,
                            COUNT(o.id) as total_orders
                        FROM orders o
                        WHERE o.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) /* Trong 30 ngày gần nhất */
                        GROUP BY customer_type";
    $customerTypeStats = $this->db->query($sqlCustomerType);

    return [
        'daily_revenue'         => $dailyRevenue,
        'status_ratio'          => $statusRatio,
        'revenue_by_category'   => $revenueByCategory,
        'orders_by_province'    => $ordersByProvince,
        'customer_type_stats'   => $customerTypeStats,
    ];
}

/**
 * Bổ sung: Hàm lấy tổng số đơn hàng theo Ngày/Tuần/Tháng để vẽ biểu đồ số đơn
 */
function countOrdersByInterval($interval = 'MONTH') {
    $format = '';
    if ($interval === 'DAY') {
        $format = '%Y-%m-%d';
    } elseif ($interval === 'WEEK') {
        $format = '%X-%V'; 
    } elseif ($interval === 'MONTH') {
        $format = '%Y-%m';
    } elseif ($interval === 'YEAR') {
        $format = '%Y';
    } else {
        return [];
    }
    
    $sql = "SELECT DATE_FORMAT(created_at, '{$format}') as period, COUNT(id) as total_orders
            FROM orders
            GROUP BY period
            ORDER BY period DESC
            LIMIT 12";
    return $this->db->query($sql);
}

// ... các hàm hiện có khác (getMonthlyIncome, getOrdersByUserId, etc.)
    
}
?>