<h1 style="color: #2c3e50; border-bottom: 2px solid #e74c3c; padding-bottom: 10px; margin-bottom: 30px;">CHI TIẾT ĐƠN HÀNG #<?= $order['id'] ?></h1>

<?php
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
        <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
        <p><strong>Tổng tiền:</strong> <span style="color: red; font-weight: bold;"><?= number_format($order['total_money']) ?> đ</span></p>
        <p><strong>PT Thanh toán:</strong> <?= $order['payment_method'] ?></p>
        <p><strong>Trạng thái TT:</strong> <?= $order['payment_status'] == 1 ? 'Đã thanh toán' : 'Chưa thanh toán' ?></p>
        <p><strong>Ghi chú:</strong> <?= !empty($order['note']) ? $order['note'] : 'Không có' ?></p>

        <h3 style="color: #34495e; border-bottom: 1px solid #eee; padding-top: 20px; padding-bottom: 10px; margin-bottom: 15px;">Thông tin Người nhận</h3>
        <p><strong>Họ tên:</strong> <?= $order['fullname'] ?></p>
        <p><strong>SĐT:</strong> <?= $order['phone'] ?></p>
        <p><strong>Địa chỉ:</strong> <?= $order['address'] ?></p>

        <h3 style="color: #34495e; border-bottom: 1px solid #eee; padding-top: 20px; padding-bottom: 10px; margin-bottom: 15px;">Cập nhật Trạng thái</h3>
        <form action="?ctrl=admin&act=orderDetail&id=<?= $order['id'] ?>" method="post">
            <select name="new_status" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%; margin-bottom: 10px;">
                <?php foreach ($statusLabels as $value => $label): ?>
                    <option value="<?= $value ?>" <?= $order['status'] == $value ? 'selected' : '' ?>>
                        <?= $label ?>
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
                <?php foreach ($orderDetails as $item): ?>
                <tr style="border-bottom: 1px solid #f1f1f1;">
                    <td style="padding: 10px; display: flex; align-items: center; gap: 10px;">
                        <img src="<?= $item['product_image'] ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 3px;">
                        <?= $item['product_name'] ?>
                    </td>
                    <td style="padding: 10px; text-align: right;"><?= number_format($item['price']) ?> đ</td>
                    <td style="padding: 10px; text-align: center;"><?= $item['quantity'] ?></td>
                    <td style="padding: 10px; text-align: right; font-weight: bold; color: #ff5722;">
                        <?= number_format($item['price'] * $item['quantity']) ?> đ
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<a href="?ctrl=admin&act=orderList" style="color: #7f8c8d;">← Quay lại danh sách Đơn hàng</a>