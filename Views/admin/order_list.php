<?php
// Views/admin/order_list.php
?>
<h1 style="color: #2c3e50; border-bottom: 2px solid #e67e22; padding-bottom: 10px; margin-bottom: 30px;">QUẢN LÝ ĐƠN HÀNG (<?=count($orders)?> đơn)</h1>

<table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
    <thead style="background: #e67e22; color: white;">
        <tr>
            <th width="5%" style="padding: 15px; text-align: left;">ID</th>
            <th width="20%" style="padding: 15px; text-align: left;">Khách hàng</th>
            <th width="15%" style="padding: 15px; text-align: right;">Tổng tiền</th>
            <th width="15%" style="padding: 15px; text-align: left;">Ngày đặt</th>
            <th width="20%" style="padding: 15px; text-align: center;">Trạng thái</th>
            <th width="10%" style="padding: 15px; text-align: center;">Thanh toán</th>
            <th width="15%" style="padding: 15px; text-align: center;">Chi tiết</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $statusMap = [
            0 => ['label' => 'Chờ xác nhận', 'color' => '#f1c40f'],
            1 => ['label' => 'Đang giao', 'color' => '#3498db'],
            2 => ['label' => 'Đã giao', 'color' => '#2ecc71'],
            3 => ['label' => 'Hủy', 'color' => '#c0392b'],
        ];

        foreach ($orders as $order): 
        ?>
        <tr style="border-bottom: 1px solid #eee;">
            <td style="padding: 15px; font-weight: bold;">#<?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?></td>
            <td style="padding: 15px;"><?= htmlspecialchars($order['user_fullname'], ENT_QUOTES, 'UTF-8') ?></td>
            <td style="padding: 15px; text-align: right; color: #ff5722; font-weight: bold;"><?= number_format($order['total_money']) ?> đ</td>
            <td style="padding: 15px;"><?= htmlspecialchars(date('H:i d/m/Y', strtotime($order['created_at'])), ENT_QUOTES, 'UTF-8') ?></td>
            <td style="padding: 15px; text-align: center;">
                <span style="display: inline-block; padding: 5px 10px; border-radius: 5px; background: <?= isset($statusMap[$order['status']]['color']) ? htmlspecialchars($statusMap[$order['status']]['color'], ENT_QUOTES, 'UTF-8') : '' ?>; color: white; font-size: 12px; font-weight: 600;">
                    <?= isset($statusMap[$order['status']]['label']) ? htmlspecialchars($statusMap[$order['status']]['label'], ENT_QUOTES, 'UTF-8') : '' ?>
                </span>
            </td>
            <td style="padding: 15px; text-align: center; color: <?= $order['payment_status'] == 1 ? '#2ecc71' : '#f39c12' ?>;">
                <?= $order['payment_status'] == 1 ? 'Đã TT' : 'Chưa TT' ?>
            </td>
            <td style="padding: 15px; text-align: center;">
                <a href="?ctrl=admin&act=orderDetail&id=<?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?>" style="color: #2980b9;">Xem chi tiết</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>