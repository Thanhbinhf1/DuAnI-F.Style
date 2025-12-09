<?php
// Views/admin/order_list.php

$currentFilter = isset($_GET['status']) ? (string)$_GET['status'] : 'all';

$statusMap = [
    0 => ['label' => 'Chờ xác nhận', 'color' => '#f39c12', 'icon' => 'fa-hourglass-half'],
    1 => ['label' => 'Đang giao', 'color' => '#3498db', 'icon' => 'fa-truck'],
    2 => ['label' => 'Đã giao', 'color' => '#2ecc71', 'icon' => 'fa-check-circle'],
    3 => ['label' => 'Đã hủy', 'color' => '#e74c3c', 'icon' => 'fa-times-circle'],
];

// Label hiển thị trên Header
$headerLabel = "Trạng thái (Tất cả)";
if ($currentFilter !== 'all' && isset($statusMap[$currentFilter])) {
    $headerLabel = "Lọc: " . $statusMap[$currentFilter]['label'];
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* CSS GIAO DIỆN */
.order-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    border-radius: 10px;
    /* QUAN TRỌNG: Phải xóa overflow: hidden hoặc để visible */
    overflow: visible !important;
}

/* 2. Đảm bảo menu luôn nổi lên trên cùng */
.th-filter-container {
    position: relative;
    z-index: 100;
    /* Cao hơn nội dung bảng */
}

/* 3. Menu lọc xổ xuống */
.filter-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    /* Bóng đổ đậm để dễ nhìn */
    border-radius: 0 0 8px 8px;
    z-index: 99999;
    /* Lớp cao nhất tuyệt đối */
    padding: 5px 0;
    border: 1px solid #ddd;
    border-top: none;
    min-width: 150px;
    /* Đảm bảo đủ rộng */
}

.filter-dropdown.show {
    display: block;
}

/* Các CSS khác giữ nguyên */
.order-page-header {
    border-bottom: 2px solid #e67e22;
    padding-bottom: 15px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.order-title {
    color: #2c3e50;
    font-size: 24px;
    margin: 0;
    font-weight: 700;
}

.order-table thead {
    background: linear-gradient(45deg, #d35400, #e67e22);
    color: white;
}

.order-table th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
}

.order-table th:first-child {
    border-top-left-radius: 10px;
}

.order-table th:last-child {
    border-top-right-radius: 10px;
}

.order-table td {
    padding: 15px;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
    color: #555;
    font-size: 14px;
}

.order-table tbody tr:hover {
    background-color: #fcfcfc;
}

.th-filter-wrapper {
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    height: 100%;
}

.th-filter-wrapper:hover {
    color: #ffd;
}

.filter-item {
    display: block;
    padding: 12px 15px;
    color: #333;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    border-bottom: 1px solid #f5f5f5;
    transition: 0.2s;
    text-align: left;
}

.filter-item:last-child {
    border-bottom: none;
}

.filter-item:hover {
    background-color: #fff8e1;
    color: #d35400;
}

.filter-item.active {
    background-color: #e67e22;
    color: white;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    color: white;
    gap: 5px;
}

/* --- BỘ LỌC TRONG HEADER --- */
.th-filter-wrapper {
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    height: 100%;
}

.th-filter-wrapper:hover {
    color: #ffd;
}

.filter-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    border-radius: 0 0 8px 8px;
    z-index: 9999;
    /* Đảm bảo nổi lên trên cùng */
    padding: 0;
    margin-top: 0;
    border: 1px solid #eee;
    border-top: none;
}

.filter-dropdown.show {
    display: block;
}

.filter-item {
    display: block;
    padding: 12px 15px;
    color: #333;
    text-decoration: none;
    font-size: 13px;
    text-transform: none;
    border-bottom: 1px solid #f5f5f5;
    font-weight: 600;
    text-align: left;
    transition: 0.2s;
}

.filter-item:last-child {
    border-bottom: none;
    border-radius: 0 0 8px 8px;
}

.filter-item:hover {
    background-color: #fff8e1;
    color: #d35400;
    padding-left: 20px;
}

.filter-item.active {
    background-color: #e67e22;
    color: white;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    color: white;
    gap: 5px;
}

.filter-dropdown {
    /* ... các thuộc tính khác ... */
    z-index: 1000;
    /* Đảm bảo menu nằm trên các phần tử khác */


}
</style>

<div class="order-page-header">
    <div style="display: flex; align-items: center; gap: 15px;">
        <h1 class="order-title"><i class="fas fa-file-invoice-dollar"></i> QUẢN LÝ ĐƠN HÀNG</h1>
        <span
            style="background: #e67e22; color: white; padding: 5px 15px; border-radius: 20px; font-weight: bold; font-size: 14px;">
            Hiển thị: <?= count($orders) ?> đơn
        </span>
    </div>

    <a href="?ctrl=admin&act=orderCancelledList"
        style="background: #e74c3c; color: white; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; box-shadow: 0 4px 6px rgba(231, 76, 60, 0.3);">
        <i class="fas fa-trash-alt"></i> Kho Đơn Hủy
    </a>
</div>

<table class="order-table">
    <thead>
        <tr>
            <th width="8%" style="text-align: center;">Mã Đơn</th>
            <th width="22%">Khách hàng</th>
            <th width="15%" style="text-align: right;">Tổng tiền</th>
            <th width="15%">Ngày đặt</th>

            <th width="18%" style="text-align: center; padding: 0; position: relative;">
                <div class="th-filter-wrapper" onclick="toggleFilter(event)" title="Bấm để lọc">
                    <?= $headerLabel ?> <i class="fas fa-filter"></i>
                </div>

                <div id="filterDropdown" class="filter-dropdown">
                    <a href="?ctrl=admin&act=orderList"
                        class="filter-item <?= $currentFilter === 'all' ? 'active' : '' ?>">
                        <i class="fas fa-list"></i> Tất cả
                    </a>
                    <a href="?ctrl=admin&act=orderList&status=0"
                        class="filter-item <?= $currentFilter === '0' ? 'active' : '' ?>">
                        <i class="fas fa-hourglass-half"></i> Chờ xác nhận
                    </a>
                    <a href="?ctrl=admin&act=orderList&status=1"
                        class="filter-item <?= $currentFilter === '1' ? 'active' : '' ?>">
                        <i class="fas fa-truck"></i> Đang giao
                    </a>
                    <a href="?ctrl=admin&act=orderList&status=2"
                        class="filter-item <?= $currentFilter === '2' ? 'active' : '' ?>">
                        <i class="fas fa-check-circle"></i> Đã giao
                    </a>
                    <a href="?ctrl=admin&act=orderList&status=3"
                        class="filter-item <?= $currentFilter === '3' ? 'active' : '' ?>">
                        <i class="fas fa-times-circle"></i> Đã hủy
                    </a>
                </div>
            </th>

            <th width="12%" style="text-align: center;">Thanh toán</th>
            <th width="10%" style="text-align: center;">Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $order): ?>
        <tr>
            <td style="text-align: center;"><strong style="color: #2c3e50;">#<?= $order['id'] ?></strong></td>
            <td>
                <div style="font-weight: 600; color: #34495e;">
                    <?= htmlspecialchars($order['user_fullname'] ?? 'Khách lẻ') ?></div>
            </td>
            <td style="text-align: right; font-weight: bold; color: #e67e22;">
                <?= number_format($order['total_money'], 0, ',', '.') ?> ₫
            </td>
            <td><?= date('H:i d/m/Y', strtotime($order['created_at'])) ?></td>

            <td style="text-align: center;">
                <?php $stt = $statusMap[$order['status']] ?? ['label' => 'Lỗi', 'color' => '#7f8c8d', 'icon' => 'fa-question']; ?>
                <span class="status-badge" style="background-color: <?= $stt['color'] ?>;">
                    <i class="fas <?= $stt['icon'] ?>"></i> <?= $stt['label'] ?>
                </span>
            </td>

            <td style="text-align: center;">
                <?= $order['payment_status'] == 1 ? '<span style="color:#2ecc71;font-weight:700">Đã TT</span>' : '<span style="color:#f39c12">Chưa TT</span>' ?>
            </td>
            <td style="text-align: center;">
                <a href="?ctrl=admin&act=orderDetail&id=<?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?>"
                    style="color: #3498db; font-weight: 600; border: 1px solid #3498db; padding: 5px 10px; border-radius: 4px; text-decoration: none;">Xem</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="7" style="text-align: center; padding: 30px; color: #95a5a6;">
                <i class="fas fa-search" style="font-size: 30px; margin-bottom: 10px;"></i><br>
                Không tìm thấy đơn hàng nào theo bộ lọc này.
            </td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
// Toggle menu lọc
function toggleFilter(e) {
    e.stopPropagation();
    document.getElementById('filterDropdown').classList.toggle('show');
}

// Đóng menu khi click ra ngoài
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('filterDropdown');
    const wrapper = document.querySelector('.th-filter-wrapper');
    if (!wrapper.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove('show');
    }
});
</script>