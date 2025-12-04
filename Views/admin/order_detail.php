<?php
// Views/admin/order_detail.php

// Định nghĩa lại nhãn trạng thái và màu sắc
$statusConfig = [
    0 => ['label' => 'Chờ xác nhận', 'color' => '#f39c12', 'bg' => '#fef5e7', 'icon' => 'fa-clock'],
    1 => ['label' => 'Đang giao', 'color' => '#3498db', 'bg' => '#ebf5fb', 'icon' => 'fa-truck'],
    2 => ['label' => 'Đã giao', 'color' => '#2ecc71', 'bg' => '#eafaf1', 'icon' => 'fa-check-circle'],
    3 => ['label' => 'Đã hủy', 'color' => '#e74c3c', 'bg' => '#fdedec', 'icon' => 'fa-times-circle']
];

$currentStatus = $statusConfig[$order['status']] ?? ['label' => 'Không rõ', 'color' => '#7f8c8d', 'bg' => '#f4f6f7', 'icon' => 'fa-question'];
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.detail-container {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #444;
}

.detail-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #e67e22;
    padding-bottom: 15px;
    margin-bottom: 30px;
}

.detail-title {
    font-size: 24px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.btn-print {
    background: #95a5a6;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    font-size: 14px;
    transition: 0.3s;
}

.btn-print:hover {
    background: #7f8c8d;
}

/* Layout 2 cột */
.detail-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    /* Cột trái nhỏ, cột phải lớn */
    gap: 30px;
}

@media (max-width: 992px) {
    .detail-grid {
        grid-template-columns: 1fr;
    }
}

.card {
    background: #fff;
    border-radius: 8px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    height: 100%;
}

.card-title {
    font-size: 16px;
    font-weight: 700;
    color: #2c3e50;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
    margin-bottom: 20px;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Thông tin chi tiết */
.info-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: 14px;
}

.info-label {
    color: #7f8c8d;
}

.info-value {
    font-weight: 600;
    color: #2c3e50;
    text-align: right;
}

/* Badge trạng thái */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 15px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 14px;
}

/* Bảng sản phẩm */
.product-table {
    width: 100%;
    border-collapse: collapse;
}

.product-table th {
    background: #f8f9fa;
    padding: 12px;
    text-align: left;
    font-size: 13px;
    color: #7f8c8d;
}

.product-table td {
    padding: 15px 12px;
    border-bottom: 1px dashed #eee;
    vertical-align: middle;
}

.product-item {
    display: flex;
    align-items: center;
    gap: 15px;
}

.product-img {
    width: 50px;
    height: 50px;
    border-radius: 6px;
    object-fit: cover;
    border: 1px solid #eee;
}

.product-name {
    font-weight: 600;
    color: #2c3e50;
    display: block;
    margin-bottom: 4px;
}

.product-meta {
    font-size: 12px;
    color: #95a5a6;
}

.btn-action {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
    margin-top: 10px;
}

.btn-confirm {
    background: #27ae60;
    color: white;
}

.btn-confirm:hover {
    background: #219150;
}

.btn-update {
    background: #34495e;
    color: white;
}

.btn-update:hover {
    background: #2c3e50;
}
</style>

<div class="detail-container">
    <div class="detail-header">
        <h1 class="detail-title">
            <i class="fas fa-file-invoice"></i> CHI TIẾT ĐƠN HÀNG
            #<?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?>
        </h1>
        <div>
            <a href="javascript:window.print()" class="btn-print"><i class="fas fa-print"></i> In Hóa Đơn</a>
        </div>
    </div>

    <div class="detail-grid">
        <div style="display: flex; flex-direction: column; gap: 20px;">

            <div class="card">
                <h3 class="card-title"><i class="fas fa-info-circle"></i> Trạng thái đơn hàng</h3>

                <div style="text-align: center; margin-bottom: 20px;">
                    <span class="status-badge"
                        style="background: <?= $currentStatus['bg'] ?>; color: <?= $currentStatus['color'] ?>;">
                        <i class="fas <?= $currentStatus['icon'] ?>"></i> <?= $currentStatus['label'] ?>
                    </span>
                </div>

                <form action="?ctrl=admin&act=orderUpdateStatus" method="post">
                    <input type="hidden" name="id" value="<?= $order['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                    <label
                        style="font-size: 13px; font-weight: 600; color: #7f8c8d; display: block; margin-bottom: 8px;">Cập
                        nhật trạng thái:</label>
                    <select name="new_status"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; margin-bottom: 10px;">
                        <?php foreach ($statusConfig as $val => $cfg): ?>
                        <option value="<?= $val ?>" <?= $order['status'] == $val ? 'selected' : '' ?>>
                            <?= $cfg['label'] ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn-action btn-update">Cập nhật</button>
                </form>
            </div>

            <div class="card">
                <h3 class="card-title"><i class="fas fa-user"></i> Thông tin khách hàng</h3>
                <div class="info-row">
                    <span class="info-label">Họ tên:</span>
                    <span class="info-value"><?= htmlspecialchars($order['fullname'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Điện thoại:</span>
                    <span class="info-value"><?= htmlspecialchars($order['phone'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ngày đặt:</span>
                    <span class="info-value"><?= date('H:i d/m/Y', strtotime($order['created_at'])) ?></span>
                </div>
                <hr style="border: 0; border-top: 1px dashed #eee; margin: 15px 0;">
                <div style="font-size: 14px; color: #555;">
                    <i class="fas fa-map-marker-alt" style="color: #e74c3c; margin-right: 5px;"></i>
                    <?= htmlspecialchars($order['address'], ENT_QUOTES, 'UTF-8') ?>
                </div>
                <?php if (!empty($order['note'])): ?>
                <div
                    style="background: #fff3cd; padding: 10px; border-radius: 6px; margin-top: 15px; font-size: 13px; color: #856404;">
                    <i class="fas fa-sticky-note"></i> <strong>Ghi chú:</strong> <?= htmlspecialchars($order['note']) ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="card">
                <h3 class="card-title"><i class="fas fa-wallet"></i> Thanh toán</h3>
                <div class="info-row">
                    <span class="info-label">Phương thức:</span>
                    <span class="info-value"
                        style="text-transform: uppercase;"><?= htmlspecialchars($order['payment_method']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Trạng thái:</span>
                    <?php if ($order['payment_status'] == 1): ?>
                    <span class="info-value" style="color: #27ae60;"><i class="fas fa-check"></i> Đã thanh toán</span>
                    <?php else: ?>
                    <span class="info-value" style="color: #f39c12;"><i class="fas fa-clock"></i> Chưa thanh toán</span>
                    <?php endif; ?>
                </div>

                <?php if ($order['payment_status'] == 0): ?>
                <div style="margin-top: 15px;">
                    <a href="?ctrl=admin&act=confirmPayment&id=<?= $order['id'] ?>"
                        onclick="return confirm('Xác nhận đã nhận được tiền?');" class="btn-action btn-confirm"
                        style="display: block; text-align: center; text-decoration: none;">
                        <i class="fas fa-check"></i> Xác nhận Đã TT
                    </a>
                </div>
                <?php endif; ?>
            </div>

        </div>

        <div class="card">
            <h3 class="card-title"><i class="fas fa-box-open"></i> Chi tiết sản phẩm</h3>

            <table class="product-table">
                <thead>
                    <tr>
                        <th width="50%">Sản phẩm</th>
                        <th width="15%" style="text-align: center;">Đơn giá</th>
                        <th width="10%" style="text-align: center;">SL</th>
                        <th width="25%" style="text-align: right;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($orderDetails) && is_array($orderDetails)): ?>
                    <?php foreach ($orderDetails as $item): ?>
                    <tr>
                        <td>
                            <div class="product-item">
                                <img src="<?= htmlspecialchars($item['product_image'], ENT_QUOTES, 'UTF-8') ?>"
                                    class="product-img" onerror="this.src='assets/images/no-image.png'">
                                <div>
                                    <span
                                        class="product-name"><?= htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8') ?></span>
                                    <div class="product-meta">
                                        <?php if (!empty($item['size'])): ?>
                                        <span
                                            style="background: #eee; padding: 2px 6px; border-radius: 3px; margin-right: 5px;">Size:
                                            <?= htmlspecialchars($item['size']) ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($item['color'])): ?>
                                        <span style="background: #eee; padding: 2px 6px; border-radius: 3px;">Màu:
                                            <?= htmlspecialchars($item['color']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="text-align: center; color: #555;"><?= number_format($item['price']) ?></td>
                        <td style="text-align: center; font-weight: bold;"><?= $item['quantity'] ?></td>
                        <td style="text-align: right; font-weight: bold; color: #2c3e50;">
                            <?= number_format($item['price'] * $item['quantity']) ?> ₫
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <tr>
                        <td colspan="3" style="text-align: right; padding-top: 20px; font-weight: 600; color: #7f8c8d;">
                            Tạm tính:</td>
                        <td style="text-align: right; padding-top: 20px; font-weight: bold; color: #2c3e50;">
                            <?= number_format($order['total_money']) ?> ₫</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right; border: none; font-weight: 600; color: #7f8c8d;">Phí
                            vận chuyển:</td>
                        <td style="text-align: right; border: none; font-weight: bold; color: #2c3e50;">0 ₫</td>
                    </tr>
                    <tr style="background: #fdfefe;">
                        <td colspan="3"
                            style="text-align: right; border: none; font-size: 18px; font-weight: 700; color: #e74c3c;">
                            TỔNG CỘNG:</td>
                        <td style="text-align: right; border: none; font-size: 20px; font-weight: 800; color: #e74c3c;">
                            <?= number_format($order['total_money']) ?> ₫
                        </td>
                    </tr>

                    <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 30px;">Không có dữ liệu sản phẩm.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <a href="?ctrl=admin&act=orderList" style="text-decoration: none; color: #7f8c8d; font-weight: 600;">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>
</div>