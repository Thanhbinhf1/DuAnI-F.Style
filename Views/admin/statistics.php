<?php
// Views/admin/statistics.php

// Dữ liệu cần thiết cho JS
$dailyRevenueJson = json_encode($stats['daily_revenue'] ?? [], JSON_NUMERIC_CHECK);
$statusRatioJson = json_encode($stats['status_ratio'] ?? [], JSON_NUMERIC_CHECK);
$revenueByCategoryJson = json_encode($stats['revenue_by_category'] ?? [], JSON_NUMERIC_CHECK);

// Mapping trạng thái
$statusMapping = [
    0 => ['label' => 'Chờ xác nhận', 'color' => '#f1c40f'], // Vàng
    1 => ['label' => 'Đang giao', 'color' => '#3498db'],   // Xanh dương
    2 => ['label' => 'Hoàn thành', 'color' => '#2ecc71'],  // Xanh lá
    3 => ['label' => 'Đã hủy', 'color' => '#e74c3c'],      // Đỏ
];
$totalOrders = array_sum(array_column($stats['status_ratio'] ?? [], 'total'));

// Tính toán sơ bộ để hiển thị gợi ý hành động (Logic PHP)
$pendingOrders = 0;
$shippingOrders = 0;
$cancelledOrders = 0;
$successOrders = 0;

foreach ($stats['status_ratio'] ?? [] as $s) {
    if ($s['status'] == 0) $pendingOrders = $s['total'];
    if ($s['status'] == 1) $shippingOrders = $s['total'];
    if ($s['status'] == 2) $successOrders = $s['total'];
    if ($s['status'] == 3) $cancelledOrders = $s['total'];
}
?>

<h1 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; margin-bottom: 30px;">
    📈 THỐNG KÊ & BÁO CÁO CHI TIẾT
</h1>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 40px;">
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <h3>Doanh thu 7 ngày gần nhất</h3>
        <p style="font-size: 13px; color: #7f8c8d; margin-bottom: 10px;">Theo dõi biến động dòng tiền hàng ngày</p>
        <canvas id="dailyRevenueChart" style="max-height: 300px;"></canvas>
    </div>

    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <h3>Tỷ lệ & Kiểm soát Đơn hàng</h3>
        <div style="display: flex; gap: 20px; align-items: center;">
            <div style="flex: 1;">
                <canvas id="statusRatioChart" style="max-height: 250px;"></canvas>
            </div>
            <div style="flex: 1; font-size: 14px;">
                <div style="margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px solid #eee;">
                    <strong style="color: #f1c40f;">● Chờ xác nhận: <?= $pendingOrders ?> đơn</strong><br>
                    <small style="color: #555;">👉 Cần đóng gói & trừ kho ngay.</small>
                </div>
                <div style="margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px solid #eee;">
                    <strong style="color: #3498db;">● Đang giao: <?= $shippingOrders ?> đơn</strong><br>
                    <small style="color: #555;">📦 Hàng đang trên đường đi.</small>
                </div>
                <div style="margin-bottom: 10px;">
                    <strong style="color: #e74c3c;">● Đã hủy: <?= $cancelledOrders ?> đơn</strong><br>
                    <small style="color: #555;">⚠️ Kiểm tra lý do (hoàn kho).</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div
    style="margin-bottom: 40px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
    <h3>Top 5 Doanh thu theo Danh mục</h3>
    <canvas id="revenueByCategoryChart" style="max-height: 400px;"></canvas>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 40px;">

    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <h3>Top 10 Sản phẩm Bán chạy</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f1f1f1;">
                    <th>#</th>
                    <th>Tên sản phẩm</th>
                    <th style="text-align:center">Đã bán</th>
                </tr>
            </thead>
            <tbody>
                <?php 
            foreach ($stats['top_selling'] ?? [] as $index => $sp) {
                echo "<tr style='border-bottom: 1px solid #eee;'><td>" . ($index + 1) . "</td><td>{$sp['name']}</td><td style='text-align:center'>{$sp['sold_quantity']}</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <h3>Phân tích Khu vực & Khách hàng</h3>

        <h4 style="margin-top: 15px; font-size: 16px;">Top 5 Tỉnh/Thành</h4>
        <table style="width: 100%; border-collapse: collapse;">
            <?php 
            foreach ($stats['orders_by_province'] ?? [] as $index => $row) {
                echo "<tr><td>" . ($index + 1) . "</td><td>{$row['province']}</td><td style='text-align:center'>{$row['count']} đơn</td></tr>";
            }
            ?>
        </table>

        <h4 style="margin-top: 20px; font-size: 16px;">Tỉ lệ Khách hàng (30 ngày)</h4>
        <table style="width: 100%; border-collapse: collapse;">
            <?php
            $totalCustomerOrders = array_sum(array_column($stats['customer_type_stats'] ?? [], 'total_orders'));
            foreach ($stats['customer_type_stats'] ?? [] as $row) {
                $type = ($row['customer_type'] === 'New') ? 'Khách mới' : 'Khách cũ';
                $percent = $totalCustomerOrders > 0 ? round(($row['total_orders'] / $totalCustomerOrders) * 100, 1) : 0;
                echo "<tr><td>{$type}</td><td>{$row['total_orders']} đơn</td><td>{$percent}%</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dailyRevenueData = <?= $dailyRevenueJson ?>;
    const statusRatioData = <?= $statusRatioJson ?>;
    const revenueByCategoryData = <?= $revenueByCategoryJson ?>;
    const statusMapping = <?= json_encode($statusMapping) ?>;

    const formatCurrency = (value) => new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(value);

    // 1. BIỂU ĐỒ DOANH THU HÀNG NGÀY (ĐÃ SỬA: Bar Chart)
    const revenueLabels = dailyRevenueData.map(item => new Date(item.date).toLocaleDateString('vi-VN'));
    const revenueValues = dailyRevenueData.map(item => item.revenue);
    // Đảo ngược mảng nếu dữ liệu server trả về từ mới nhất -> cũ nhất (để hiển thị theo thời gian trái -> phải)
    revenueLabels.reverse();
    revenueValues.reverse();

    new Chart(document.getElementById('dailyRevenueChart').getContext('2d'), {
        type: 'bar', // <--- ĐỔI TỪ LINE SANG BAR
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Doanh thu (VND)',
                data: revenueValues,
                backgroundColor: '#3498db', // Màu cột
                borderRadius: 4, // Bo tròn góc cột
                barThickness: 20, // Độ rộng cột
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: formatCurrency
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: (context) => context.dataset.label + ': ' + formatCurrency(context.parsed
                            .y)
                    }
                },
                legend: {
                    display: false // Ẩn chú thích vì chỉ có 1 loại dữ liệu
                }
            }
        }
    });

    // 2. BIỂU ĐỒ TỶ LỆ TRẠNG THÁI ĐƠN HÀNG (Giữ nguyên Chart, HTML đã thêm phần giải thích)
    const ratioLabels = statusRatioData.map(item => statusMapping[item.status]?.label || 'Khác');
    const ratioValues = statusRatioData.map(item => item.total);
    const ratioColors = statusRatioData.map(item => statusMapping[item.status]?.color || '#777');

    new Chart(document.getElementById('statusRatioChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ratioLabels,
            datasets: [{
                label: 'Số đơn hàng',
                data: ratioValues,
                backgroundColor: ratioColors,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // Ẩn legend trong chart vì đã có phần giải thích bên cạnh
                }
            }
        }
    });

    // 3. BIỂU ĐỒ DOANH THU THEO DANH MỤC
    const categoryLabels = revenueByCategoryData.map(item => item.category_name);
    const categoryValues = revenueByCategoryData.map(item => item.revenue);

    new Chart(document.getElementById('revenueByCategoryChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: categoryLabels,
            datasets: [{
                label: 'Doanh thu (VND)',
                data: categoryValues,
                backgroundColor: '#e67e22',
                borderColor: '#d35400',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y', // Biểu đồ cột ngang
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: formatCurrency
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: (context) => context.dataset.label + ': ' + formatCurrency(context.parsed
                            .x)
                    }
                },
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>