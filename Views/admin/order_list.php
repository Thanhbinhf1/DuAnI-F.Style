<?php
// Views/admin/order_list.php

// --- LOGIC SẮP XẾP MỚI (ƯU TIÊN THEO TRẠNG THÁI) ---
if (isset($orders) && is_array($orders)) {
    usort($orders, function($a, $b) {
        // Định nghĩa độ ưu tiên (Số càng nhỏ càng nằm trên)
        $priority = [
            0 => 1, // Chờ xác nhận (Ưu tiên số 1 - CAO NHẤT)
            1 => 2, // Đang giao    (Ưu tiên số 2)
            2 => 3, // Đã giao      (Ưu tiên số 3 - THẤP)
            3 => 4  // Đã hủy       (Ưu tiên số 4 - THẤP NHẤT)
        ];

        // Lấy độ ưu tiên của đơn hàng A và B
        // (Nếu trạng thái lỗi không có trong list thì cho xuống đáy = 99)
        $pA = $priority[$a['status']] ?? 99;
        $pB = $priority[$b['status']] ?? 99;

        // So sánh độ ưu tiên trước
        if ($pA != $pB) {
            return $pA - $pB; // Tăng dần (1 lên đầu, 99 xuống cuối)
        }

        // Nếu cùng độ ưu tiên (ví dụ cùng là Chờ xác nhận), thì đơn MỚI NHẤT lên trên
        return $b['id'] <=> $a['id'];
    });
}

// Mapping hiển thị
$statusMap = [
    0 => ['label' => 'Chờ xác nhận', 'color' => '#f39c12', 'icon' => 'fa-hourglass-half'],
    1 => ['label' => 'Đang giao', 'color' => '#3498db', 'icon' => 'fa-truck'],
    2 => ['label' => 'Đã giao', 'color' => '#2ecc71', 'icon' => 'fa-check-circle'],
    3 => ['label' => 'Đã hủy', 'color' => '#e74c3c', 'icon' => 'fa-times-circle'],
];
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* CSS BẢNG */
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
}

/* Bo góc tiêu đề */
.order-table thead tr:first-child th:first-child {
    border-top-left-radius: 10px;
}

.order-table thead tr:first-child th:last-child {
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

.price-tag {
    color: #e67e22;
    font-weight: 700;
    font-family: 'Consolas', monospace;
}

/* CSS MENU DROPDOWN (ĐÃ FIX LỖI MẤT KHUNG) */
.status-dropdown-wrapper {
    position: relative;
    display: inline-block;
    z-index: 1;
}

.status-dropdown-wrapper.active {
    z-index: 1000 !important;
}

.status-btn {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    color: white;
    cursor: pointer;
    border: none;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: 0.2s;
    white-space: nowrap;
}

.status-btn:active {
    transform: scale(0.95);
}

.status-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: white;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    padding: 5px;
    min-width: 160px;
    border: 1px solid #eee;
    margin-top: 8px;
}

.status-menu::before {
    content: "";
    position: absolute;
    bottom: 100%;
    left: 50%;
    margin-left: -6px;
    border-width: 6px;
    border-style: solid;
    border-color: transparent transparent white transparent;
}

.status-menu.show {
    display: block;
    animation: fadeIn 0.2s;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateX(-50%) translateY(-10px);
    }

    to {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
}

.status-item {
    display: block;
    width: 100%;
    padding: 10px;
    text-align: left;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 13px;
    color: #333;
    border-radius: 4px;
    white-space: nowrap;
}

.status-item:hover {
    background-color: #f0f2f5;
}

.status-item i {
    margin-right: 8px;
    width: 20px;
    text-align: center;
}

/* Màu chữ menu */
.text-warning {
    color: #f39c12;
}

.text-primary {
    color: #3498db;
}

.text-success {
    color: #2ecc71;
}

.text-danger {
    color: #e74c3c;
}
</style>

<div class="order-page-header">
    <div style="display: flex; align-items: center; gap: 15px;">
        <h1 class="order-title"><i class="fas fa-file-invoice-dollar"></i> QUẢN LÝ ĐƠN HÀNG</h1>
        <span
            style="background: #e67e22; color: white; padding: 5px 15px; border-radius: 20px; font-weight: bold; font-size: 14px;">Tổng:
            <?= count($orders) ?> đơn</span>
    </div>
    <a href="?ctrl=admin&act=orderCancelledList"
        style="background: #e74c3c; color: white; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: bold;">
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
            <th width="20%" style="text-align: center;">Trạng thái</th>
            <th width="12%" style="text-align: center;">Thanh toán</th>
            <th width="10%" style="text-align: center;">Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $order): ?>
        <tr>
            <td style="text-align: center;">
                <strong style="color: #2c3e50;">#<?= $order['id'] ?></strong>
            </td>
            <td>
                <div style="font-weight: 600; color: #34495e;">
                    <?= htmlspecialchars($order['user_fullname'] ?? 'Khách lẻ') ?></div>
            </td>
            <td style="text-align: right; font-weight: bold; color: #e67e22;">
                <?= number_format($order['total_money'], 0, ',', '.') ?> ₫
            </td>
            <td><?= date('H:i d/m/Y', strtotime($order['created_at'])) ?></td>

            <td style="text-align: center; overflow: visible;">
                <?php 
                    $currentStt = $order['status']; 
                    $sttConfig = $statusMap[$currentStt] ?? ['label' => 'Lỗi', 'color' => '#7f8c8d', 'icon' => 'fa-question'];
                ?>

                <div class="status-dropdown-wrapper" id="dropdown-<?= $order['id'] ?>">
                    <button class="status-btn" style="background-color: <?= $sttConfig['color'] ?>;"
                        onclick="toggleMenu('<?= $order['id'] ?>', event)">
                        <i class="fas <?= $sttConfig['icon'] ?>"></i> <?= $sttConfig['label'] ?>
                        <?php if ($currentStt != 3): ?><i class="fas fa-caret-down"
                            style="font-size: 10px; margin-left: 3px;"></i><?php endif; ?>
                    </button>

                    <?php if ($currentStt != 3): // Nếu chưa hủy thì mới hiện menu ?>
                    <div class="status-menu">
                        <form action="?ctrl=admin&act=orderUpdateStatus" method="POST" style="margin:0;">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            <input type="hidden" name="id" value="<?= $order['id'] ?>">

                            <?php if ($currentStt == 0): ?>
                            <div class="status-item text-warning"
                                style="cursor: default; background: #fffbe6; opacity: 0.8;">
                                <i class="fas fa-check"></i> Đang chờ...
                            </div>
                            <?php endif; ?>

                            <?php if ($currentStt == 0): ?>
                            <button type="submit" name="new_status" value="1" class="status-item text-primary">
                                <i class="fas fa-truck"></i> Chuyển: Đang giao
                            </button>
                            <?php elseif ($currentStt == 1): ?>
                            <div class="status-item text-primary"
                                style="cursor: default; background: #e3f2fd; opacity: 0.8;">
                                <i class="fas fa-truck"></i> Đang giao hàng...
                            </div>
                            <?php endif; ?>

                            <?php if ($currentStt == 1): ?>
                            <button type="submit" name="new_status" value="2" class="status-item text-success">
                                <i class="fas fa-check-circle"></i> Xác nhận: Đã giao
                            </button>
                            <?php endif; ?>

                            <?php if ($currentStt == 2): ?>
                            <div class="status-item text-success" style="cursor: default; background: #e8f5e9;">
                                <i class="fas fa-check-double"></i> Đơn hoàn tất
                            </div>
                            <?php endif; ?>

                            <?php if ($currentStt != 2): ?>
                            <hr style="margin: 3px 0; border: 0; border-top: 1px solid #eee;">
                            <button type="submit" name="new_status" value="3" class="status-item text-danger"
                                onclick="return confirm('Hủy đơn này?');">
                                <i class="fas fa-times-circle"></i> Hủy đơn
                            </button>
                            <?php endif; ?>

                        </form>
                    </div>
                    <?php endif; ?>
                </div>
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
            <td colspan="7" style="text-align: center; padding: 30px; color: #95a5a6;">Chưa có đơn hàng nào.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
function toggleMenu(orderId, event) {
    event.stopPropagation();
    document.querySelectorAll('.status-menu').forEach(menu => menu.classList.remove('show'));
    document.querySelectorAll('.status-dropdown-wrapper').forEach(wrapper => wrapper.classList.remove('active'));

    const wrapper = document.getElementById('dropdown-' + orderId);
    const menu = wrapper.querySelector('.status-menu');

    if (menu) {
        if (!menu.classList.contains('show')) {
            menu.classList.add('show');
            wrapper.classList.add('active'); // Đẩy z-index lên cao
        }
    }
}

document.addEventListener('click', function() {
    document.querySelectorAll('.status-menu').forEach(menu => menu.classList.remove('show'));
    document.querySelectorAll('.status-dropdown-wrapper').forEach(wrapper => wrapper.classList.remove(
    'active'));
});
</script>