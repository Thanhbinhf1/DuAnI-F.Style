<?php
// Views/admin/statistics.php

// 1. Chuẩn bị dữ liệu cho Javascript
$dailyRevenueJson = json_encode($stats['daily_revenue'] ?? [], JSON_NUMERIC_CHECK);
$statusRatioJson = json_encode($stats['status_ratio'] ?? [], JSON_NUMERIC_CHECK);

// Mapping màu sắc trạng thái
$statusMapping = [
    0 => ['label' => 'Chờ xác nhận', 'color' => '#f1c40f'], // Vàng
    1 => ['label' => 'Đang giao', 'color' => '#3498db'],    // Xanh
    2 => ['label' => 'Hoàn thành', 'color' => '#2ecc71'],   // Xanh lá
    3 => ['label' => 'Đã hủy', 'color' => '#e74c3c'],       // Đỏ
];

// Tính toán số lượng đơn để hiển thị text gợi ý
$pendingOrders = 0; $shippingOrders = 0; $cancelledOrders = 0;
foreach ($stats['status_ratio'] ?? [] as $s) {
    if ($s['status'] == 0) $pendingOrders = $s['total'];
    if ($s['status'] == 1) $shippingOrders = $s['total'];
    if ($s['status'] == 3) $cancelledOrders = $s['total'];
}

// 2. Cấu hình bộ lọc thời gian
$timeOptions = [
    15 => '15 ngày qua',
    30 => '30 ngày qua',
    90 => '3 tháng qua',
    180 => '6 tháng qua',
    365 => '1 năm qua' // 12 tháng
];
// Lấy giá trị đang chọn (từ Controller truyền sang), mặc định 30
$currentTime = isset($selectedDays) ? $selectedDays : 30;
?>

<div
    style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #3498db; padding-bottom: 10px; margin-bottom: 30px;">
    <h1 style="color: #2c3e50; margin: 0; font-size: 24px;">
        <i class="fas fa-chart-line"></i> THỐNG KÊ KINH DOANH
    </h1>

    <form method="GET" action=""
        style="display: flex; align-items: center; gap: 10px; background: #f8f9fa; padding: 5px 15px; border-radius: 20px; border: 1px solid #ddd;">
        <input type="hidden" name="ctrl" value="admin">
        <input type="hidden" name="act" value="statistics">

        <label for="timeFilter" style="font-weight: 600; color: #555; margin: 0;"><i class="far fa-calendar-alt"></i>
            Thời gian:</label>
        <select name="time" id="timeFilter" onchange="this.form.submit()"
            style="border: none; background: transparent; font-weight: bold; color: #3498db; cursor: pointer; padding: 5px; outline: none;">
            <?php foreach ($timeOptions as $val => $label): ?>
            <option value="<?= $val ?>" <?= $currentTime == $val ? 'selected' : '' ?>>
                <?= $label ?>
            </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; margin-bottom: 40px;">

    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <h3 style="margin-top: 0; color: #2c3e50;">Doanh thu (<?= $timeOptions[$currentTime] ?>)</h3>
        <p style="font-size: 13px; color: #95a5a6; margin-bottom: 20px;">Tổng tiền từ các đơn hàng đã giao thành công.
        </p>
        <div style="height: 300px;">
            <canvas id="dailyRevenueChart"></canvas>
        </div>
    </div>

    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <h3 style="margin-top: 0; color: #2c3e50;">Tỷ lệ đơn hàng</h3>
        <div style="height: 200px; margin-bottom: 15px;">
            <canvas id="statusRatioChart"></canvas>
        </div>

        <div style="border-top: 1px dashed #eee; padding-top: 15px; font-size: 13px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="color: #f1c40f; font-weight: bold;">● Chờ xác nhận:</span>
                <strong><?= $pendingOrders ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="color: #3498db; font-weight: bold;">● Đang giao:</span>
                <strong><?= $shippingOrders ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #e74c3c; font-weight: bold;">● Đã hủy:</span>
                <strong><?= $cancelledOrders ?></strong>
            </div>
        </div>
    </div>
</div>

<div
    style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 40px;">
    <div
        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f1f1; padding-bottom: 10px;">
        <h3 style="color: #2c3e50; margin: 0;">
            <i class="fas fa-crown" style="color: #f1c40f;"></i> TOP 10 SẢN PHẨM BÁN CHẠY
        </h3>
        <span style="font-size: 13px; color: #7f8c8d; background: #eee; padding: 4px 10px; border-radius: 10px;">
            <?= $timeOptions[$currentTime] ?>
        </span>
    </div>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr
                style="background: #f8f9fa; color: #555; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">
                <th style="padding: 12px; text-align: center; width: 60px;">Hạng</th>
                <th style="padding: 12px; text-align: left;">Tên sản phẩm</th>
                <th style="padding: 12px; text-align: center;">Số lượng bán</th>
                <th style="padding: 12px; text-align: right;">Gần nhất</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($stats['top_selling'])): ?>
            <?php foreach ($stats['top_selling'] as $index => $sp): ?>
            <tr style="border-bottom: 1px solid #f1f1f1; transition: 0.2s;"
                onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='transparent'">
                <td style="padding: 15px; text-align: center;">
                    <?php 
                            if($index == 0) echo '<span style="font-size:20px">🥇</span>';
                            elseif($index == 1) echo '<span style="font-size:20px">🥈</span>';
                            elseif($index == 2) echo '<span style="font-size:20px">🥉</span>';
                            else echo '<span style="font-weight:bold; color:#999; display:inline-block; width:24px; height:24px; line-height:24px; background:#eee; border-radius:50%;">' . ($index + 1) . '</span>';
                        ?>
                </td>
                <td style="padding: 15px; font-weight: 600; color: #2c3e50;">
                    <?= htmlspecialchars($sp['name']) ?>
                </td>
                <td style="padding: 15px; text-align: center;">
                    <span
                        style="background: #eafaf1; color: #2ecc71; padding: 5px 12px; border-radius: 15px; font-weight: bold; font-size: 14px;">
                        <?= $sp['sold_quantity'] ?>
                    </span>
                </td>
                <td style="padding: 15px; text-align: right; color: #7f8c8d; font-size: 13px;">
                    <?= date('d/m/Y', strtotime($sp['last_sale_date'])) ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="4" style="text-align: center; padding: 40px; color: #999;">
                    <i class="fas fa-box-open" style="font-size: 40px; margin-bottom: 10px; color: #ddd;"></i><br>
                    Chưa có sản phẩm nào được bán trong <?= $timeOptions[$currentTime] ?>.
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dailyRevenueData = <?= $dailyRevenueJson ?>;
    const statusRatioData = <?= $statusRatioJson ?>;
    const statusMapping = <?= json_encode($statusMapping) ?>;

    const formatCurrency = (value) => new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(value);

    // --- 1. BIỂU ĐỒ DOANH THU (DẠNG CỘT - BAR CHART) ---
    // Xử lý dữ liệu ngày tháng
    const revenueLabels = dailyRevenueData.map(item => {
        const d = new Date(item.date);
        return d.getDate() + '/' + (d.getMonth() + 1);
    });
    const revenueValues = dailyRevenueData.map(item => item.revenue);

    // Đảo ngược để hiển thị theo thời gian từ trái sang phải
    revenueLabels.reverse();
    revenueValues.reverse();

    new Chart(document.getElementById('dailyRevenueChart').getContext('2d'), {
        type: 'bar', // <--- Đổi thành BAR (CỘT)
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Doanh thu',
                data: revenueValues,
                backgroundColor: 'rgba(52, 152, 219, 0.7)', // Màu cột xanh
                borderColor: '#2980b9',
                borderWidth: 1,
                borderRadius: 4, // Bo góc cột
                barPercentage: 0.6, // Độ rộng cột
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
                    },
                    grid: {
                        borderDash: [2, 2]
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

    // --- 2. BIỂU ĐỒ TRẠNG THÁI (DOUGHNUT - GIỮ NGUYÊN) ---
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
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%', // Làm vòng tròn mỏng hơn cho đẹp
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 10,
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