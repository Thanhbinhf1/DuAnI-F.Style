    <div class="container" style="max-width: 900px; margin: 30px auto; padding: 0 15px;">
    <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; box-shadow: 0 8px 24px rgba(0,0,0,0.05);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="margin: 0 0 8px;">Hóa đơn #<?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?></h2>
                <p style="margin: 0; color: #555;">Ngày đặt: <?= htmlspecialchars(date('d/m/Y H:i', strtotime($order['created_at'])), ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <button onclick="window.print()" style="padding: 10px 16px; background: #222; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">In hóa đơn</button>
        </div>

        <div style="margin-top: 16px; display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div>
                <h4 style="margin: 0 0 8px;">Thông tin người nhận</h4>
                <p style="margin: 4px 0;">Họ tên: <strong><?= htmlspecialchars($order['fullname'], ENT_QUOTES, 'UTF-8') ?></strong></p>
                <p style="margin: 4px 0;">SĐT: <strong><?= htmlspecialchars($order['phone'], ENT_QUOTES, 'UTF-8') ?></strong></p>
                <p style="margin: 4px 0;">Địa chỉ: <?= htmlspecialchars($order['address'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div>
                <h4 style="margin: 0 0 8px;">Thanh toán</h4>
                <p style="margin: 4px 0;">Phương thức: <?= htmlspecialchars($order['payment_method'], ENT_QUOTES, 'UTF-8') ?></p>
                <p style="margin: 4px 0;">Trạng thái: <strong><?= (int)$order['payment_status'] === 1 ? 'Đã thanh toán' : 'Chưa thanh toán' ?></strong></p>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-top: 18px;">
            <thead>
                <tr style="background: #f6f7fb;">
                    <th style="padding: 10px; text-align: left;">Sản phẩm</th>
                    <th style="padding: 10px; text-align: center;">Số lượng</th>
                    <th style="padding: 10px; text-align: right;">Đơn giá</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderDetails as $item): ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 10px;"><?= htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td style="padding: 10px; text-align: center;"><?= (int)$item['quantity'] ?></td>
                        <td style="padding: 10px; text-align: right;"><?= number_format($item['price']) ?> đ</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="text-align: right; margin-top: 12px; font-size: 18px; font-weight: 700; color: #ff5722;">
            Tổng cộng: <?= number_format($order['total_money']) ?> đ
        </div>
    </div>
</div>