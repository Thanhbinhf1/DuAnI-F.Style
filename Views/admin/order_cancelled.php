<?php
// Views/admin/order_cancelled.php
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
.page-header {
    border-bottom: 2px solid #e74c3c;
    padding-bottom: 15px;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-title {
    color: #c0392b;
    font-size: 24px;
    font-weight: 700;
    margin: 0;
}

.table-cancel {
    width: 100%;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    border-radius: 8px;
    overflow: hidden;
}

.table-cancel th {
    background: #e74c3c;
    color: white;
    padding: 15px;
    text-align: left;
}

.table-cancel td {
    padding: 15px;
    border-bottom: 1px solid #eee;
    color: #555;
}

.btn-back {
    text-decoration: none;
    color: #7f8c8d;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.note-box {
    background: #fff5f5;
    color: #c0392b;
    padding: 8px;
    border-radius: 4px;
    border: 1px dashed #e74c3c;
    font-size: 13px;
    font-style: italic;
}
</style>

<div class="page-header">
    <h1 class="page-title"><i class="fas fa-ban"></i> DANH SÁCH ĐƠN HỦY (<?= count($orders) ?> đơn)</h1>
    <a href="?ctrl=admin&act=orderList" class="btn-back"><i class="fas fa-arrow-left"></i> Quay lại DS Đơn hàng</a>
</div>

<table class="table-cancel">
    <thead>
        <tr>
            <th width="10%">Mã Đơn</th>
            <th width="20%">Khách hàng</th>
            <th width="15%">Tổng tiền</th>
            <th width="15%">Ngày đặt</th>
            <th width="30%">Lý do hủy / Ghi chú</th>
            <th width="10%" style="text-align: center;">Chi tiết</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $order): ?>
        <tr>
            <td><strong style="color: #c0392b;">#<?= $order['id'] ?></strong></td>
            <td>
                <div style="font-weight: bold;"><?= htmlspecialchars($order['user_fullname'] ?? 'Khách lẻ') ?></div>
                <small><?= htmlspecialchars($order['user_email']) ?></small>
            </td>
            <td style="font-weight: bold;"><?= number_format($order['total_money']) ?> đ</td>
            <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>

            <td>
                <?php if (!empty($order['note'])): ?>
                <div class="note-box">
                    <i class="fas fa-comment-alt"></i> <?= htmlspecialchars($order['note']) ?>
                </div>
                <?php else: ?>
                <span style="color: #999;">(Không có lý do)</span>
                <?php endif; ?>
            </td>

            <td style="text-align: center;">
                <a href="?ctrl=admin&act=orderDetail&id=<?= $order['id'] ?>"
                    style="color: #3498db; font-weight: bold;">Xem</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="6" style="text-align: center; padding: 30px;">Chưa có đơn hàng nào bị hủy.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>