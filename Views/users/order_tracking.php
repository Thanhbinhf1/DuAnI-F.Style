<div class="container" style="max-width: 800px; margin: 30px auto; padding: 0 15px;">
    <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; box-shadow: 0 8px 24px rgba(0,0,0,0.05);">
        <h2 style="margin-top: 0;">Theo dõi vận chuyển</h2>
        <p style="margin: 6px 0; color: #666;">Đơn hàng #<?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?></p>
        <p style="margin: 6px 0;">Đơn vị vận chuyển: <strong><?= htmlspecialchars($carrierName, ENT_QUOTES, 'UTF-8') ?></strong></p>
        <p style="margin: 6px 0;">Mã vận đơn: <strong><?= htmlspecialchars($trackingId, ENT_QUOTES, 'UTF-8') ?></strong></p>
        <a href="<?= htmlspecialchars($trackingUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" style="display: inline-block; margin-top: 10px; padding: 10px 14px; background: #1976d2; color: #fff; border-radius: 8px; text-decoration: none;">Xem trạng thái chi tiết</a>
    </div>
</div>