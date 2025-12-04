<?php
// Views/admin/order_list.php

// --- XỬ LÝ SẮP XẾP (SORTING) ---
// Nếu Controller chưa sắp xếp, ta sắp xếp ngay tại đây: Mới nhất (ID lớn nhất) lên đầu
if (isset($orders) && is_array($orders)) {
    usort($orders, function($a, $b) {
        // So sánh ID: Cái nào lớn hơn thì đứng trước (DESC)
        return $b['id'] <=> $a['id'];
    });
}

// Mapping trạng thái đơn hàng (Màu sắc & Nhãn)
$statusMap = [
    0 => ['label' => 'Chờ xác nhận', 'color' => '#f39c12', 'icon' => 'fa-hourglass-half'],
    1 => ['label' => 'Đang giao', 'color' => '#3498db', 'icon' => 'fa-truck'],
    2 => ['label' => 'Đã giao', 'color' => '#2ecc71', 'icon' => 'fa-check-circle'],
    3 => ['label' => 'Đã hủy', 'color' => '#e74c3c', 'icon' => 'fa-times-circle'],
];
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.order-page-header {
    border-bottom: 2px solid #e67e22;
    padding-bottom: 15px;
    margin-bottom: 30px;
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

.order-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    border-radius: 10px;
    overflow: hidden;
    /* Để bo góc hoạt động */
}

.order-table thead {
    background: linear-gradient(45deg, #d35400, #e67e22);
    color: white;
}

.order-table th {
    padding: 18px 15px;
    text-align: left;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.5px;
}

.order-table td {
    padding: 15px;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
    color: #555;
    font-size: 14px;
}

.order-table tbody tr:last-child td {
    border-bottom: none;
}

.order-table tbody tr:hover {
    background-color: #fcfcfc;
}

/* Badge trạng thái */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    color: white;
    gap: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.btn-detail {
    color: #3498db;
    font-weight: 600;
    text-decoration: none;
    padding: 6px 12px;
    border: 1px solid #3498db;
    border-radius: 6px;
    transition: all 0.2s;
    font-size: 13px;
}

.btn-detail:hover {
    background: #3498db;
    color: white;
}

.payment-status-paid {
    color: #2ecc71;
    font-weight: 700;
}

.payment-status-unpaid {
    color: #f39c12;
    font-weight: 600;
}

.price-tag {
    color: #e67e22;
    font-weight: 700;
    font-family: 'Consolas', monospace;
}
</style>

<div class="order-page-header">
    <h1 class="order-title">
        <i class="fas fa-file-invoice-dollar"></i> QUẢN LÝ ĐƠN HÀNG
    </h1>
    <span style="background: #e67e22; color: white; padding: 5px 15px; border-radius: 20px; font-weight: bold;">
        Tổng: <?= count($orders) ?> đơn
    </span>
</div>

<table class="order-table">
    <thead>
        <tr>
            <th width="8%" style="text-align: center;">Mã Đơn</th>
            <th width="22%">Khách hàng</th>
            <th width="15%" style="text-align: right;">Tổng tiền</th>
            <th width="15%">Ngày đặt</th>
            <th width="18%" style="text-align: center;">Trạng thái</th>
            <th width="12%" style="text-align: center;">Thanh toán</th>
            <th width="10%" style="text-align: center;">Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $order): ?>
        <tr>
            <td style="text-align: center;">
                <strong style="color: #2c3e50;">#<?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?></strong>
            </td>

            <td>
                <div style="font-weight: 600; color: #34495e;">
                    <?= htmlspecialchars($order['user_fullname'] ?? 'Khách lẻ', ENT_QUOTES, 'UTF-8') ?>
                </div>
                <?php if(isset($order['user_email'])): ?>
                <div style="font-size: 12px; color: #95a5a6;"><?= htmlspecialchars($order['user_email']) ?></div>
                <?php endif; ?>
            </td>

            <td style="text-align: right;">
                <span class="price-tag"><?= number_format($order['total_money'], 0, ',', '.') ?> ₫</span>
            </td>

            <td>
                <i class="far fa-clock" style="color: #95a5a6; margin-right: 5px;"></i>
                <?= htmlspecialchars(date('H:i d/m/Y', strtotime($order['created_at'])), ENT_QUOTES, 'UTF-8') ?>
            </td>

            <td style="text-align: center;">
                <?php 
                        $stt = $statusMap[$order['status']] ?? ['label' => 'Không rõ', 'color' => '#7f8c8d', 'icon' => 'fa-question'];
                    ?>
                <span class="status-badge" style="background-color: <?= $stt['color'] ?>;">
                    <i class="fas <?= $stt['icon'] ?>"></i> <?= $stt['label'] ?>
                </span>
            </td>

            <td style="text-align: center;">
                <?php if ($order['payment_status'] == 1): ?>
                <span class="payment-status-paid"><i class="fas fa-check"></i> Đã TT</span>
                <?php else: ?>
                <span class="payment-status-unpaid"><i class="fas fa-spinner"></i> Chưa TT</span>
                <?php endif; ?>
            </td>

            <td style="text-align: center;">
                <a href="?ctrl=admin&act=orderDetail&id=<?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?>"
                    class="btn-detail">
                    <i class="fas fa-eye"></i> Xem
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="7" style="text-align: center; padding: 30px; color: #95a5a6;">
                <i class="fas fa-box-open" style="font-size: 40px; margin-bottom: 10px; display: block;"></i>
                Chưa có đơn hàng nào.
            </td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>