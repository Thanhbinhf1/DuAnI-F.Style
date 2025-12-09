<?php
// Views/admin/statistics.php

// Dữ liệu JSON cho biểu đồ
$dailyRevenueJson = json_encode($stats['daily_revenue'] ?? [], JSON_NUMERIC_CHECK);
$statusRatioJson = json_encode($stats['status_ratio'] ?? [], JSON_NUMERIC_CHECK);

// Mapping trạng thái
$statusMapping = [
    0 => ['label' => 'Chờ xác nhận', 'color' => '#f1c40f'],
    1 => ['label' => 'Đang giao', 'color' => '#3498db'],
    2 => ['label' => 'Hoàn thành', 'color' => '#2ecc71'],
    3 => ['label' => 'Đã hủy', 'color' => '#e74c3c'],
];

// Tính toán tổng quan cho các thẻ gợi ý
$pendingOrders = 0; $shippingOrders = 0; $cancelledOrders = 0;
foreach ($stats['status_ratio'] ?? [] as $s) {
    if ($s['status'] == 0) $pendingOrders = $s['total'];
    if ($s['status'] == 1) $shippingOrders = $s['total'];
    if ($s['status'] == 3) $cancelledOrders = $s['total'];
}

// Mảng cấu hình bộ lọc thời gian
$timeOptions = [
    15 => '15 ngày qua',
    30 => '30 ngày qua',
    90 => '3 tháng qua',
    180 => '6 tháng qua',
    365 => '1 năm qua'
];
$currentTime = isset($selectedDays) ? $selectedDays : 30; // Biến từ Controller
?>

<div
    style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #3498db; padding-bottom: 10px; margin-bottom: 30px;">
    <h1 style="color: #2c3e50; margin: 0;">📈 THỐNG KÊ DOANH THU</h1>

    <form method="GET" action="" style="display: flex; align-items: center; gap: 10px;">
        <input type="hidden" name="ctrl" value="admin">
        <input type="hidden" name="act" value="statistics">

        <label style="font-weight: 600; color: #555;">Xem theo:</label>
        <select name="time" onchange="this.form.submit()"
            style="padding: 8px 15px; border-radius: 5px; border: 1px solid #ccc; cursor: pointer; font-weight: bold; color: #2c3e50;">
            <?php foreach ($timeOptions as $val => $label): ?>
            <option value="<?= $val ?>" <?= $currentTime == $val ? 'selected' : '' ?>>
                <?= $label ?>
            </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; margin-bottom: 40px;">
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <h3>Doanh thu thực tế (<?= $timeOptions[$currentTime] ?>)</h3>
        <p style="font-size: 13px; color: #7f8c8d; margin-bottom: 10px;">Biểu đồ thể hiện tổng tiền thu được từ các đơn
            hàng "Hoàn thành".</p>
        <canvas id="dailyRevenueChart" style="max-height: 300px; width: 100%;"></canvas>
    </div>

    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <h3>Trạng thái đơn hàng</h3>
        <canvas id="statusRatioChart" style="max-height: 200px; margin-bottom: 20px;"></canvas>

        <div style="font-size: 13px; border-top: 1px dashed #eee; padding-top: 15px;">
            <div style="margin-bottom: 8px; display: flex; justify-content: space-between;">
                <span style="color: #f1c40f; font-weight: bold;">● Chờ xử lý:</span>
                <span><?= $pendingOrders ?> đơn</span>
            </div>
            <div style="margin-bottom: 8px; display: flex; justify-content: space-between;">
                <span style="color: #3498db; font-weight: bold;">● Đang giao:</span>
                <span><?= $shippingOrders ?> đơn</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #e74c3c; font-weight: bold;">● Đã hủy:</span>
                <span><?= $cancelledOrders ?> đơn</span>
            </div>
        </div>
    </div>
</div>

<div
    style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); margin-bottom: 40px;">
    <h3 style="color: #2c3e50; border-left: 5px solid #27ae60; padding-left: 10px; margin-bottom: 20px;">
        🏆 TOP 10 SẢN PHẨM BÁN CHẠY NHẤT (<?= $timeOptions[$currentTime] ?>)
    </h3>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8f9fa; color: #555; text-transform: uppercase; font-size: 13px;">
                <th style="padding: 12px; text-align: center; width: 5%;">Top</th>
                <th style="padding: 12px; text-align: left;">Tên sản phẩm</th>
                <th style="padding: 12px; text-align: center;">Số lượng bán</th>
                <th style="padding: 12px; text-align: right;">Ngày bán gần nhất</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($stats['top_selling'])): ?>
            <?php foreach ($stats['top_selling'] as $index => $sp): ?>
            <tr style="border-bottom: 1px solid #eee; transition: 0.2s;" onmouseover="this.style.background='#fcfcfc'"
                onmouseout="this.style.background='transparent'">
                <td style="padding: 15px; text-align: center; font-weight: bold; color: #7f8c8d;">
                    <?php if($index == 0) echo '🥇'; elseif($index == 1) echo '🥈'; elseif($index == 2) echo '🥉'; else echo ($index + 1); ?>
                </td>
                <td style="padding: 15px; font-weight: 600; color: #2c3e50;">
                    <?= htmlspecialchars($sp['name']) ?>
                </td>
                <td style="padding: 15px; text-align: center;">
                    <span
                        style="background: #eafaf1; color: #2ecc71; padding: 5px 10px; border-radius: 15px; font-weight: bold;">
                        <?= $sp['sold_quantity'] ?> cái
                    </span>
                </td>
                <td style="padding: 15px; text-align: right; color: #555;">
                    <i class="far fa-clock"></i>
                    <?= date('d/m/Y', strtotime($sp['last_sale_date'])) ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="4" style="text-align: center; padding: 30px; color: #999;">
                    Chưa có dữ liệu bán hàng trong khoảng thời gian này.
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dailyRevenueData = <?= $dailyRevenueJson ?>;
    const statusRatioData = <?= $statusRatioJson ?>;
    const statusMapping = <?= json_encode($statusMapping) ?>;

    const formatCurrency = (value) => new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(value);

    // 1. CHART DOANH THU
    const revenueLabels = dailyRevenueData.map(item => {
        const d = new Date(item.date);
        return d.getDate() + '/' + (d.getMonth() + 1); // Chỉ hiện Ngày/Tháng cho gọn
    });
    const revenueValues = dailyRevenueData.map(item => item.revenue);

    // Đảo ngược để hiện từ cũ đến mới (trái qua phải)
    revenueLabels.reverse();
    revenueValues.reverse();

    new Chart(document.getElementById('dailyRevenueChart').getContext('2d'), {
        type: 'line', // Dùng line chart để thấy xu hướng theo thời gian tốt hơn
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Doanh thu',
                data: revenueValues,
                borderColor: '#3498db',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#3498db',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (val) => formatCurrency(val)
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: (ctx) => formatCurrency(ctx.parsed.y)
                    }
                }
            }
        }
    });

    // 2. CHART TRẠNG THÁI (Doughnut)
    const ratioLabels = statusRatioData.map(item => statusMapping[item.status]?.label || 'Khác');
    const ratioValues = statusRatioData.map(item => item.total);
    const ratioColors = statusRatioData.map(item => statusMapping[item.status]?.color || '#ccc');

    new Chart(document.getElementById('statusRatioChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ratioLabels,
            datasets: [{
                data: ratioValues,
                backgroundColor: ratioColors,
                borderWidth: 0,
                hoverOffset: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 12,
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
});
</script>