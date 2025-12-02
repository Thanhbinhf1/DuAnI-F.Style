<?php
// Views/admin/dashboard.php
?>
<h1 style="color: #2c3e50; border-bottom: 2px solid #e74c3c; padding-bottom: 10px; margin-bottom: 30px;">TRANG QUẢN TRỊ (DASHBOARD)</h1>

<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
    <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center;">
        <h2 style="font-size: 16px; color: #7f8c8d; margin-bottom: 10px; text-transform: uppercase;">Tổng Sản phẩm</h2>
        <p style="font-size: 36px; font-weight: bold; color: #27ae60;"><?= isset($stats['products']) ? htmlspecialchars($stats['products'], ENT_QUOTES, 'UTF-8') : 0 ?></p>
    </div>
    <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center;">
        <h2 style="font-size: 16px; color: #7f8c8d; margin-bottom: 10px; text-transform: uppercase;">Đơn hàng mới</h2>
        <p style="font-size: 36px; font-weight: bold; color: #e67e22;"><?= isset($stats['new_orders']) ? htmlspecialchars($stats['new_orders'], ENT_QUOTES, 'UTF-8') : 0 ?></p>
    </div>
    <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center;">
        <h2 style="font-size: 16px; color: #7f8c8d; margin-bottom: 10px; text-transform: uppercase;">Người dùng</h2>
        <p style="font-size: 36px; font-weight: bold; color: #3498db;"><?= isset($stats['users']) ? htmlspecialchars($stats['users'], ENT_QUOTES, 'UTF-8') : 0 ?></p>
    </div>
    <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center;">
        <h2 style="font-size: 16px; color: #7f8c8d; margin-bottom: 10px; text-transform: uppercase;">Tổng Thu nhập</h2>
        <p style="font-size: 36px; font-weight: bold; color: #e74c3c;"><?= isset($stats['income']) ? number_format($stats['income'], 0, ',', '.') : 0 ?> đ</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <h3 style="font-size: 20px; color: #2c3e50; margin-bottom: 15px;">Doanh thu theo tháng</h3>
        <canvas id="incomeChart" style="max-height: 250px;"></canvas>
        <p style="text-align:center; color: #95a5a6; margin-top: 15px;">(Biểu đồ ví dụ, cần tích hợp JS để vẽ)</p>
    </div>

    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <h3 style="font-size: 20px; color: #2c3e50; margin-bottom: 15px;">Hoạt động gần đây</h3>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <?php if (isset($recent_activities) && !empty($recent_activities)): ?>
                <?php foreach ($recent_activities as $activity): ?>
                    <li style="border-bottom: 1px dashed #eee; padding: 12px 0; font-size: 14px;">
                        <span style="color: #2980b9; font-weight: 600;">
                            <a href="?ctrl=admin&act=orderDetail&id=<?= htmlspecialchars($activity['id'], ENT_QUOTES, 'UTF-8') ?>">Đơn hàng #<?= htmlspecialchars($activity['id'], ENT_QUOTES, 'UTF-8') ?></a>
                        </span>
                        mới từ khách "<?= isset($activity['fullname']) ? htmlspecialchars($activity['fullname'], ENT_QUOTES, 'UTF-8') : '' ?>" 
                        trị giá <span style="color:#e74c3c;"><?= isset($activity['total_money']) ? number_format($activity['total_money']) : '' ?> đ</span>
                        <span style="display: block; font-size: 12px; color: #7f8c8d; margin-top: 4px;">
                            <?= isset($activity['created_at']) ? htmlspecialchars(date('H:i d/m/Y', strtotime($activity['created_at'])), ENT_QUOTES, 'UTF-8') : '' ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li style="padding: 10px 0; color: #7f8c8d;">Không có hoạt động nào gần đây.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<!-- Dữ liệu PHP được truyền sang JS tại đây -->
<script>
    const dashboardStats = <?= json_encode($stats ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
</script>