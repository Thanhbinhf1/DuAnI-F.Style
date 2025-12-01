<?php
// Views/admin/order_detail.php
$order = $data['order'];
$details = $data['details'];

$statusMap = [
    0 => ['label' => 'Chờ xác nhận', 'color' => '#f1c40f'],
    1 => ['label' => 'Đang giao', 'color' => '#3498db'],
    2 => ['label' => 'Đã giao', 'color' => '#2ecc71'],
    3 => ['label' => 'Hủy', 'color' => '#c0392b'],
];
?>
<h1 style="color: #2c3e50; border-bottom: 2px solid #e67e22; padding-bottom: 10px; margin-bottom: 30px;">
    CHI TIẾT ĐƠN HÀNG #<?=$order['id']?>
</h1>

<div style="display: flex; gap: 30px;">
    <div style="flex: 1; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <h2 style="font-size: 18px; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">Thông tin Đơn hàng</h2>
        <p><b>Ngày đặt:</b> <?=date('H:i d/m/Y', strtotime($order['created_at']))?></p>
        <p><b>Tổng tiền:</b> <span style="color: #ff5722; font-weight: bold; font-size: 18px;"><?=number_format($order['total_money'])?> đ</span></p>
        <p><b>Phương thức TT:</b> <?=$order['payment_method']?></p>
        <p><b>Trạng thái TT:</b> <span style="color: <?=$order['payment_status'] == 1 ? '#2ecc71' : '#f39c12'?>; font-weight: bold;"><?=$order['payment_status'] == 1 ? 'Đã thanh toán' : 'Chưa thanh toán'?></span></p>
        
        <h2 style="font-size: 18px; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-top: 20px; margin-bottom: 15px;">Thông tin Người nhận</h2>
        <p><b>Họ tên:</b> <?=$order['fullname']?></p>
        <p><b>SĐT:</b> <?=$order['phone']?></p>
        <p><b>Địa chỉ:</b> <?=$order['address']?></p>
        <p><b>Ghi chú:</b> <?=$order['note'] ?: 'Không có'?></p>
    </div>

    <div style="width: 300px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <h2 style="font-size: 18px; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">Trạng thái hiện tại</h2>
        <span style="display: block; padding: 10px; border-radius: 5px; background: <?=$statusMap[$order['status']]['color']?>; color: white; text-align: center; font-weight: bold; font-size: 16px;">
            <?=$statusMap[$order['status']]['label']?>
        </span>
        
        <h2 style="font-size: 18px; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-top: 20px; margin-bottom: 15px;">Cập nhật Trạng thái</h2>
        <form action="?ctrl=admin&act=updateOrderStatus" method="post">
            <input type="hidden" name="order_id" value="<?=$order['id']?>">
            <select name="status" style="width: 100%; padding: 8px; margin-bottom: 10px; border-radius: 4px; border: 1px solid #ddd;">
                <?php foreach ($statusMap as $key => $status): ?>
                    <option value="<?=$key?>" <?= $order['status'] == $key ? 'selected' : '' ?>>
                        <?=$status['label']?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" style="width: 100%; padding: 10px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Cập nhật</button>
        </form>
    </div>
</div>

<div style="margin-top: 30px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
    <h2 style="font-size: 18px; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">Danh sách Sản phẩm</h2>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8f8f8;">
                <th width="50%" style="padding: 10px; text-align: left;">Sản phẩm</th>
                <th width="15%" style="padding: 10px; text-align: right;">Đơn giá</th>
                <th width="15%" style="padding: 10px; text-align: center;">Số lượng</th>
                <th width="20%" style="padding: 10px; text-align: right;">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($details as $item): ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <img src="<?=$item['product_image']?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 3px;">
                        <span><?=$item['product_name']?></span>
                    </div>
                </td>
                <td style="padding: 10px; text-align: right;"><?=number_format($item['price'])?> đ</td>
                <td style="padding: 10px; text-align: center;"><?=$item['quantity']?></td>
                <td style="padding: 10px; text-align: right; font-weight: bold; color: #ff5722;"><?=number_format($item['price'] * $item['quantity'])?> đ</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div style="margin-top: 20px;">
    <a href="?ctrl=admin&act=listOrders" style="color: #333; font-weight: 500;">← Quay lại danh sách Đơn hàng</a>
</div>