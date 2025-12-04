<?php
// Tách đơn hàng thành 2 nhóm để dễ quản lý
$successOrders = []; // Đơn tốt (Chờ, Đang giao, Đã giao)
$boomOrders = [];    // Đơn hủy (Bom hàng) - Status = 3

foreach ($orders as $od) {
    if ($od['status'] == 3) {
        $boomOrders[] = $od;
    } else {
        $successOrders[] = $od;
    }
}

$statusMap = [
    0 => ['label' => 'Chờ xác nhận', 'class' => 'bg-warning'],
    1 => ['label' => 'Đang giao', 'class' => 'bg-primary'],
    2 => ['label' => 'Đã giao', 'class' => 'bg-success'],
    3 => ['label' => 'ĐÃ HỦY', 'class' => 'bg-danger']
];
?>

<style>
.profile-card {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    gap: 20px;
    border-left: 5px solid #3498db;
}

.avatar-placeholder {
    width: 80px;
    height: 80px;
    background: #ecf0f1;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    color: #bdc3c7;
}

.section-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px dashed #ddd;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.boom-alert {
    color: #e74c3c;
    background: #fdedec;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 14px;
}

.history-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

.history-table th {
    background: #f8f9fa;
    padding: 12px;
    text-align: left;
    color: #555;
    font-size: 13px;
}

.history-table td {
    padding: 15px 12px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
}

.badge {
    padding: 4px 8px;
    border-radius: 4px;
    color: white;
    font-size: 11px;
    font-weight: bold;
}

.bg-warning {
    background: #f1c40f;
}

.bg-primary {
    background: #3498db;
}

.bg-success {
    background: #2ecc71;
}

.bg-danger {
    background: #e74c3c;
}
</style>

<div style="margin-bottom: 20px;">
    <a href="?ctrl=admin&act=userList" style="color: #7f8c8d; text-decoration: none;">← Quay lại danh sách người
        dùng</a>
</div>

<div class="profile-card">
    <div class="avatar-placeholder"><i class="fas fa-user"></i></div>
    <div>
        <h2 style="margin: 0; color: #2c3e50;"><?= htmlspecialchars($user['fullname']) ?></h2>
        <p style="margin: 5px 0; color: #7f8c8d;">Username: <strong><?= htmlspecialchars($user['username']) ?></strong>
        </p>
        <p style="margin: 0; color: #7f8c8d;">Email: <?= htmlspecialchars($user['email']) ?></p>
        <p style="margin-top: 5px; color: #7f8c8d;">Vai trò:
            <?= $user['role'] == 1 ? '<span style="color:red; font-weight:bold">Quản trị viên</span>' : 'Khách hàng' ?>
        </p>
    </div>
    <div style="margin-left: auto; text-align: right;">
        <div style="font-size: 30px; font-weight: bold; color: #e74c3c;"><?= count($boomOrders) ?></div>
        <div style="font-size: 12px; color: #999;">Đơn hàng đã hủy</div>
    </div>
    <div style="text-align: right;">
        <div style="font-size: 30px; font-weight: bold; color: #27ae60;"><?= count($successOrders) ?></div>
        <div style="font-size: 12px; color: #999;">Đơn hàng thành công</div>
    </div>
</div>

<?php if (count($boomOrders) > 0): ?>
<div style="margin-bottom: 40px;">
    <div class="section-title" style="color: #c0392b; border-color: #e74c3c;">
        <span><i class="fas fa-exclamation-triangle"></i> LỊCH SỬ HỦY ĐƠN (BOM HÀNG)</span>
        <span class="boom-alert">Cảnh báo: <?= count($boomOrders) ?> lần</span>
    </div>
    <table class="history-table">
        <thead style="background: #fdedec;">
            <tr>
                <th width="10%">Mã đơn</th>
                <th width="40%">Sản phẩm (Tóm tắt)</th>
                <th width="15%">Tổng tiền</th>
                <th width="20%">Ngày đặt</th>
                <th width="15%">Chi tiết</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($boomOrders as $ord): ?>
            <tr>
                <td><strong style="color: #c0392b;">#<?= $ord['id'] ?></strong></td>
                <td>
                    <?= htmlspecialchars(mb_strimwidth($ord['product_summary'], 0, 50, "...")) ?>
                    <div style="font-size: 11px; color: #999;">(<?= $ord['item_count'] ?> món)</div>
                </td>
                <td style="color: #c0392b; font-weight: bold;"><?= number_format($ord['total_money']) ?> đ</td>
                <td><?= date('H:i d/m/Y', strtotime($ord['created_at'])) ?></td>
                <td><a href="?ctrl=admin&act=orderDetail&id=<?= $ord['id'] ?>" style="color: #3498db;">Xem đơn này</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<div>
    <div class="section-title" style="color: #27ae60;">
        <span><i class="fas fa-shopping-cart"></i> LỊCH SỬ MUA HÀNG</span>
    </div>

    <?php if (count($successOrders) > 0): ?>
    <table class="history-table">
        <thead>
            <tr>
                <th width="10%">Mã đơn</th>
                <th width="40%">Sản phẩm</th>
                <th width="15%">Tổng tiền</th>
                <th width="15%">Trạng thái</th>
                <th width="20%">Ngày đặt</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($successOrders as $ord): ?>
            <tr>
                <td><strong>#<?= $ord['id'] ?></strong></td>
                <td>
                    <?= htmlspecialchars(mb_strimwidth($ord['product_summary'], 0, 50, "...")) ?>
                    <div style="font-size: 11px; color: #999;">(<?= $ord['item_count'] ?> món)</div>
                </td>
                <td style="font-weight: bold;"><?= number_format($ord['total_money']) ?> đ</td>
                <td>
                    <span class="badge <?= $statusMap[$ord['status']]['class'] ?>">
                        <?= $statusMap[$ord['status']]['label'] ?>
                    </span>
                </td>
                <td><?= date('d/m/Y', strtotime($ord['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p style="text-align: center; color: #999; padding: 20px;">Người dùng này chưa có đơn hàng nào thành công.</p>
    <?php endif; ?>
</div>