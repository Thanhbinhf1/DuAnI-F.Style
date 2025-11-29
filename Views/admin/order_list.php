<h1 style="color: #2c3e50; border-bottom: 2px solid #e74c3c; padding-bottom: 10px; margin-bottom: 30px;">QUẢN LÝ ĐƠN HÀNG</h1>

<table style="width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border-radius: 8px;">
    <thead>
        <tr style="background-color: #27ae60; color: white;">
            <th style="padding: 15px; text-align: center;">Mã ĐH</th>
            <th style="padding: 15px; text-align: left;">Khách hàng</th>
            <th style="padding: 15px; text-align: right;">Tổng tiền</th>
            <th style="padding: 15px; text-align: center;">PTTT</th>
            <th style="padding: 15px; text-align: center;">Trạng thái</th>
            <th style="padding: 15px; text-align: center;">Ngày đặt</th>
            <th style="padding: 15px; text-align: center;">Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $statusLabels = [
            0 => 'Chờ xác nhận',
            1 => 'Đang giao',
            2 => 'Đã giao',
            3 => 'Hủy'
        ];
        
        foreach ($orders as $order): ?>
        <tr style="border-bottom: 1px solid #eee;">
            <td style="padding: 15px; text-align: center; font-weight: bold;"><?= $order['id'] ?></td>
            <td style="padding: 15px; font-weight: 600;"><?= $order['fullname'] ?> (<?= $order['user_phone'] ?>)</td>
            <td style="padding: 15px; text-align: right; color: #e74c3c; font-weight: bold;"><?= number_format($order['total_money']) ?> đ</td>
            <td style="padding: 15px; text-align: center;"><?= $order['payment_method'] ?></td>
            <td style="padding: 15px; text-align: center;">
                <span style="color: <?= $order['status'] == 2 ? 'green' : ($order['status'] == 3 ? 'red' : 'orange') ?>;">
                    <?= $statusLabels[$order['status']] ?>
                </span>
            </td>
            <td style="padding: 15px; text-align: center;"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
            <td style="padding: 15px; text-align: center;">
                <a href="?ctrl=admin&act=orderDetail&id=<?= $order['id'] ?>" style="color: #3498db; text-decoration: none;">Xem chi tiết</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>