<h1 style="color: #2c3e50; border-bottom: 2px solid #e74c3c; padding-bottom: 10px; margin-bottom: 30px;">CHI TIẾT ĐƠN HÀNG #<?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?></h1>

<?php
// Định nghĩa lại nhãn trạng thái (Nếu không có trong Controller)
$statusLabels = [
    0 => 'Chờ xác nhận',
    1 => 'Đang giao',
    2 => 'Đã giao',
    3 => 'Hủy'
];
?>

<div style="display: flex; gap: 30px; margin-bottom: 30px;">
    
    <div style="flex: 1; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <h3 style="color: #34495e; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">Thông tin Đơn hàng</h3>
        <p><strong>Ngày đặt:</strong> <?= htmlspecialchars(date('d/m/Y H:i', strtotime($order['created_at'])), ENT_QUOTES, 'UTF-8') ?></p>
        <p><strong>Tổng tiền:</strong> <span style="color: red; font-weight: bold;"><?= number_format($order['total_money']) ?> đ</span></p>
        <p><strong>PT Thanh toán:</strong> <?= htmlspecialchars($order['payment_method'], ENT_QUOTES, 'UTF-8') ?></p>
        <p><strong>Trạng thái TT:</strong> 
            <span style="color: <?= $order['payment_status'] == 1 ? '#27ae60' : '#e74c3c' ?>; font-weight: bold;">
                <?= $order['payment_status'] == 1 ? 'Đã thanh toán' : 'Chưa thanh toán' ?>
            </span>
        </p>
        
        <?php if ($order['payment_status'] == 0): ?>
            <div style="margin-top: 15px;">
                <a href="?ctrl=admin&act=confirmPayment&id=<?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?>" 
                   onclick="return confirm('Bạn có chắc chắn muốn xác nhận đơn hàng #<?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?> đã thanh toán? Hành động này không thể hoàn tác.');"
                   style="display: inline-block; padding: 8px 15px; background: #27ae60; color: white; text-decoration: none; border-radius: 4px; font-size: 14px;">
                    <i class="fas fa-check-circle"></i> Xác nhận ĐÃ THANH TOÁN
                </a>
            </div>
        <?php endif; ?>

        <p><strong>Ghi chú:</strong> <?= !empty($order['note']) ? htmlspecialchars($order['note'], ENT_QUOTES, 'UTF-8') : 'Không có' ?></p>

        <h3 style="color: #34495e; border-bottom: 1px solid #eee; padding-top: 20px; padding-bottom: 10px; margin-bottom: 15px;">Thông tin Người nhận</h3>
        <p><strong>Họ tên:</strong> <?= htmlspecialchars($order['fullname'], ENT_QUOTES, 'UTF-8') ?></p>
        <p><strong>SĐT:</strong> <?= htmlspecialchars($order['phone'], ENT_QUOTES, 'UTF-8') ?></p>
        <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address'], ENT_QUOTES, 'UTF-8') ?></p>

        <h3 style="color: #34495e; border-bottom: 1px solid #eee; padding-top: 20px; padding-bottom: 10px; margin-bottom: 15px;">Cập nhật Trạng thái</h3>
        <form action="?ctrl=admin&act=orderDetail&id=<?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?>" method="post">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <select name="new_status" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%; margin-bottom: 10px;">
                <?php foreach ($statusLabels as $value => $label): ?>
                    <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" <?= $order['status'] == $value ? 'selected' : '' ?>>
                        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" style="padding: 8px 15px; background: #2c3e50; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Cập nhật
            </button>
        </form>
    </div>

    <div style="flex: 2; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <h3 style="color: #34495e; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">Sản phẩm đã đặt</h3>
        
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa;">
                    <th style="padding: 10px; text-align: left;">Sản phẩm</th>
                    <th style="padding: 10px; text-align: right;">Giá</th>
                    <th style="padding: 10px; text-align: center;">SL</th>
                    <th style="padding: 10px; text-align: right;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($orderDetails) && is_array($orderDetails)): ?>
                    <?php foreach ($orderDetails as $item): ?>
                    <tr style="border-bottom: 1px solid #f1f1f1;">
                        <td style="padding: 10px; display: flex; align-items: center; gap: 10px;">
                            <img src="<?= htmlspecialchars($item['product_image'], ENT_QUOTES, 'UTF-8') ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 3px;">
                            <?= htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8') ?>
                        </td>
                        <td style="padding: 10px; text-align: right;"><?= number_format($item['price']) ?> đ</td>
                        <td style="padding: 10px; text-align: center;"><?= htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td style="padding: 10px; text-align: right; font-weight: bold; color: #ff5722;">
                            <?= number_format($item['price'] * $item['quantity']) ?> đ
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="padding: 15px; text-align: center; color: #7f8c8d;">Không tìm thấy chi tiết sản phẩm.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<a href="?ctrl=admin&act=orderList" style="color: #7f8c8d;">← Quay lại danh sách Đơn hàng</a>