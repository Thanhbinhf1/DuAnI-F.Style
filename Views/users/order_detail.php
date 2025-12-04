<div class="container" style="max-width: 1100px; margin: 30px auto; padding: 0 15px;">
    <div style="background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 24px; box-shadow: 0 8px 24px rgba(0,0,0,0.05);">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            <div>
                <p style="margin: 0; color: #888;">Mã đơn hàng #<?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?></p>
                <h2 style="margin: 6px 0 10px; font-size: 24px;">Chi tiết đơn hàng</h2>
                <?php $currentStatus = $statusMap[$order['status']] ?? ['label' => 'Không xác định', 'color' => '#555']; ?>
                <span style="display: inline-block; padding: 6px 12px; border-radius: 999px; background: <?= htmlspecialchars($currentStatus['color'], ENT_QUOTES, 'UTF-8') ?>; color: #fff; font-weight: 600;">
                    <?= htmlspecialchars($currentStatus['label'], ENT_QUOTES, 'UTF-8') ?>
                </span>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 14px; color: #777;">Tổng tiền</div>
                <div style="font-size: 22px; font-weight: 700; color: #ff5722;"><?= number_format($order['total_money']) ?> đ</div>
                <div style="margin-top: 6px; color: <?= (int)$order['payment_status'] === 1 ? '#27ae60' : '#c0392b' ?>; font-weight: 600;">
                    <?= htmlspecialchars($paymentLabel, ENT_QUOTES, 'UTF-8') ?> · <?= htmlspecialchars($order['payment_method'], ENT_QUOTES, 'UTF-8') ?>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 12px; align-items: center; margin-top: 18px; flex-wrap: wrap;">
            <a href="?ctrl=order&act=reorder&id=<?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?>" style="padding: 12px 18px; background: #222; color: #fff; border-radius: 8px; text-decoration: none; font-weight: 600;">Mua lại</a>
            <?php $canPay = (int)$order['payment_status'] === 0; ?>
            <?php $canCancel = (int)$order['status'] === 0; ?>
            <?php if ($canCancel): ?>
                <a href="?ctrl=order&act=cancel&id=<?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?>" onclick="return confirm('Bạn muốn hủy đơn hàng này?');" style="padding: 12px 18px; background: #fff; color: #c0392b; border: 1px solid #c0392b; border-radius: 8px; text-decoration: none; font-weight: 600;">Hủy đơn</a>
            <?php endif; ?>
            <a href="<?= $canPay ? '?ctrl=order&act=payment&id=' . htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') : '#' ?>" style="padding: 12px 18px; background: <?= $canPay ? '#ff5722' : '#ccc' ?>; color: #fff; border-radius: 8px; text-decoration: none; font-weight: 600; <?= $canPay ? '' : 'pointer-events: none;' ?>">Thanh toán ngay</a>
        </div>

        <div style="margin-top: 20px; padding: 14px 16px; background: #f7f9fb; border: 1px dashed #d5e3f0; border-radius: 10px; display: flex; gap: 12px; flex-wrap: wrap;">
            <a href="?ctrl=page&act=contact#support-chat" style="padding: 10px 14px; border-radius: 6px; background: #fff; border: 1px solid #d7dde5; text-decoration: none; color: #333; font-weight: 600;">💬 Hỗ trợ</a>
            <a href="?ctrl=order&act=printInvoice&id=<?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?>" style="padding: 10px 14px; border-radius: 6px; background: #fff; border: 1px solid #d7dde5; text-decoration: none; color: #333; font-weight: 600;">🧾 In hóa đơn</a>
            <a href="?ctrl=order&act=tracking&id=<?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?>" style="padding: 10px 14px; border-radius: 6px; background: #fff; border: 1px solid #d7dde5; text-decoration: none; color: #333; font-weight: 600;">🚚 Tra cứu vận đơn</a>
        </div>
    </div>

    <div style="margin-top: 24px; display: grid; grid-template-columns: 1.4fr 1fr; gap: 18px; align-items: start;">
        <div style="background: #fff; border-radius: 12px; border: 1px solid #eee; padding: 20px; box-shadow: 0 8px 24px rgba(0,0,0,0.05);">
            <h3 style="margin-top: 0; margin-bottom: 12px;">Dòng thời gian</h3>
            <div style="position: relative;">
                <?php foreach ($events as $index => $event): ?>
                    <div style="display: grid; grid-template-columns: 28px 1fr; gap: 10px; position: relative; padding-bottom: 18px;">
                        <div style="position: relative;">
                            <span style="width: 14px; height: 14px; display: inline-block; border-radius: 50%; background: <?= $event['done'] ? '#27ae60' : '#dfe6ed' ?>; position: relative; top: 4px;"></span>
                            <?php if ($index < count($events) - 1): ?>
                                <span style="position: absolute; left: 6px; top: 18px; width: 2px; height: calc(100% - 4px); background: #dfe6ed;"></span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: baseline; gap: 8px;">
                                <strong style="font-size: 16px;"><?= htmlspecialchars($event['title'], ENT_QUOTES, 'UTF-8') ?></strong>
                                <span style="color: #888; font-size: 13px;"><?= htmlspecialchars($event['time'], ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                            <p style="margin: 6px 0 8px; color: #555;"><?= htmlspecialchars($event['description'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php if (!empty($event['carrier']) || !empty($event['tracking'])): ?>
                                <div style="font-size: 13px; color: #444; background: #f5f9ff; padding: 8px 10px; border-radius: 8px; border: 1px solid #e3edfb;">
                                    <div>Đơn vị vận chuyển: <strong><?= htmlspecialchars($event['carrier'], ENT_QUOTES, 'UTF-8') ?></strong></div>
                                    <div>Mã vận đơn: <strong><?= htmlspecialchars($event['tracking'], ENT_QUOTES, 'UTF-8') ?></strong>
                                        <?php if (!empty($event['tracking_link'])): ?>
                                            <a href="<?= htmlspecialchars($event['tracking_link'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" style="margin-left: 8px; color: #1976d2; text-decoration: underline;">Theo dõi</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div style="background: #fff; border-radius: 12px; border: 1px solid #eee; padding: 20px; box-shadow: 0 8px 24px rgba(0,0,0,0.05);">
            <h3 style="margin-top: 0; margin-bottom: 12px;">Thông tin thanh toán & giao hàng</h3>
            <p style="margin: 8px 0;"><strong>Người nhận:</strong> <?= htmlspecialchars($order['fullname'], ENT_QUOTES, 'UTF-8') ?></p>
            <p style="margin: 8px 0;"><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['phone'], ENT_QUOTES, 'UTF-8') ?></p>
            <p style="margin: 8px 0;"><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address'], ENT_QUOTES, 'UTF-8') ?></p>
            <p style="margin: 8px 0;"><strong>Ghi chú:</strong> <?= !empty($order['note']) ? htmlspecialchars($order['note'], ENT_QUOTES, 'UTF-8') : 'Không có' ?></p>
            <hr style="margin: 14px 0;">
            <h4>Sản phẩm</h4>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <?php foreach ($orderDetails as $item): ?>
                    <div style="display: grid; grid-template-columns: 60px 1fr auto; gap: 10px; align-items: center; padding: 10px; border: 1px solid #f0f0f0; border-radius: 8px;">
                        <img src="<?= htmlspecialchars($item['product_image'], ENT_QUOTES, 'UTF-8') ?>" alt="" style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                        <div>
                            <div style="font-weight: 600;"><?= htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8') ?></div>
                            <div style="color: #777;">Số lượng: <?= (int)$item['quantity'] ?></div>
                        </div>
                        <div style="text-align: right; font-weight: 700; color: #ff5722;"><?= number_format($item['price']) ?> đ</div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
