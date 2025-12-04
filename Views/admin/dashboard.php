<?php
// Views/admin/dashboard.php

// --- DỮ LIỆU GIẢ LẬP CHO BIỂU ĐỒ (Nếu Controller chưa gửi) ---
// Logic: Nếu controller chưa gửi dữ liệu biểu đồ, ta tạo mảng mặc định 6 tháng gần nhất
if (!isset($chartData)) {
    $months = [];
    $revenues = [];
    for ($i = 5; $i >= 0; $i--) {
        $months[] = "Tháng " . date('m', strtotime("-$i months"));
        $revenues[] = rand(1000000, 5000000); // Số ngẫu nhiên demo
    }
    $chartLabels = json_encode($months);
    $chartValues = json_encode($revenues);
} else {
    // Nếu Controller đã gửi, hãy format nó ở đây
    $chartLabels = json_encode($chartData['labels']);
    $chartValues = json_encode($chartData['values']);
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
:root {
    --primary: #3498db;
    --success: #2ecc71;
    --warning: #f1c40f;
    --danger: #e74c3c;
    --dark: #2c3e50;
    --light: #f8f9fa;
    --gray: #95a5a6;
}

.dashboard-container {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #444;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    border-bottom: 2px solid var(--primary);
    padding-bottom: 15px;
}

.page-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
}

/* STATS CARDS */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.stat-card {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.stat-content h3 {
    font-size: 14px;
    color: var(--gray);
    text-transform: uppercase;
    margin: 0 0 5px 0;
    font-weight: 600;
}

.stat-content .number {
    font-size: 28px;
    font-weight: 800;
    margin: 0;
    color: var(--dark);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #fff;
}

/* Màu riêng cho từng card */
.card-product .stat-icon {
    background: linear-gradient(45deg, #2ecc71, #27ae60);
}

.card-order .stat-icon {
    background: linear-gradient(45deg, #f1c40f, #f39c12);
}

.card-user .stat-icon {
    background: linear-gradient(45deg, #3498db, #2980b9);
}

.card-income .stat-icon {
    background: linear-gradient(45deg, #e74c3c, #c0392b);
}

.card-income .number {
    color: var(--danger);
}

/* MAIN CONTENT GRID */
.main-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    /* Tỷ lệ 2:1 */
    gap: 30px;
}

/* Responsive cho mobile/tablet */
@media (max-width: 992px) {
    .main-grid {
        grid-template-columns: 1fr;
    }
}

.content-box {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    height: 100%;
}

.box-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.box-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
}

/* RECENT ACTIVITY LIST */
.activity-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    padding: 15px 0;
    border-bottom: 1px dashed #eee;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon-small {
    min-width: 35px;
    height: 35px;
    background: #ecf0f1;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    margin-right: 15px;
    font-size: 14px;
}

.activity-info {
    flex: 1;
}

.activity-info a {
    color: var(--primary);
    font-weight: 700;
    text-decoration: none;
}

.activity-info a:hover {
    text-decoration: underline;
}

.activity-meta {
    display: block;
    font-size: 12px;
    color: var(--gray);
    margin-top: 4px;
}

.money {
    color: var(--danger);
    font-weight: 700;
}
</style>

<div class="dashboard-container">
    <div class="page-header">
        <h1 class="page-title"><i class="fas fa-tachometer-alt"></i> TỔNG QUAN (DASHBOARD)</h1>
        <div style="font-size: 14px; color: #7f8c8d;">
            Hôm nay: <?= date('d/m/Y') ?>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card card-product">
            <div class="stat-content">
                <h3>Tổng Sản phẩm</h3>
                <p class="number"><?= isset($stats['products']) ? number_format($stats['products']) : 0 ?></p>
            </div>
            <div class="stat-icon"><i class="fas fa-box"></i></div>
        </div>

        <div class="stat-card card-order">
            <div class="stat-content">
                <h3>Đơn hàng mới</h3>
                <p class="number"><?= isset($stats['new_orders']) ? number_format($stats['new_orders']) : 0 ?></p>
            </div>
            <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
        </div>

        <div class="stat-card card-user">
            <div class="stat-content">
                <h3>Khách hàng</h3>
                <p class="number"><?= isset($stats['users']) ? number_format($stats['users']) : 0 ?></p>
            </div>
            <div class="stat-icon"><i class="fas fa-users"></i></div>
        </div>

        <div class="stat-card card-income">
            <div class="stat-content">
                <h3>Tổng Thu nhập</h3>
                <p class="number"><?= isset($stats['income']) ? number_format($stats['income'], 0, ',', '.') : 0 ?> đ
                </p>
            </div>
            <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
        </div>
    </div>

    <div class="main-grid">
        <div class="content-box">
            <div class="box-header">
                <h3 class="box-title"><i class="fas fa-chart-line"></i> Biểu đồ Doanh thu</h3>
                <select style="padding: 5px; border-radius: 4px; border: 1px solid #ddd; font-size: 12px;">
                    <option>6 tháng gần nhất</option>
                    <option>Năm nay</option>
                </select>
            </div>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="incomeChart"></canvas>
            </div>
        </div>

        <div class="content-box">
            <div class="box-header">
                <h3 class="box-title"><i class="fas fa-history"></i> Đơn hàng mới nhất</h3>
            </div>
            <ul class="activity-list">
                <?php if (isset($recent_activities) && !empty($recent_activities)): ?>
                <?php foreach ($recent_activities as $activity): ?>
                <li class="activity-item">
                    <div class="activity-icon-small">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="activity-info">
                        <span>
                            <a href="?ctrl=admin&act=orderDetail&id=<?= $activity['id'] ?>">#<?= $activity['id'] ?></a>
                            - <?= htmlspecialchars($activity['fullname']) ?>
                        </span>
                        <div style="font-size: 13px; margin-top: 2px;">
                            Tổng: <span class="money"><?= number_format($activity['total_money']) ?> đ</span>
                        </div>
                        <span class="activity-meta">
                            <i class="far fa-clock"></i>
                            <?= date('H:i d/m/Y', strtotime($activity['created_at'])) ?>
                        </span>
                    </div>
                </li>
                <?php endforeach; ?>
                <?php else: ?>
                <li style="text-align: center; color: #999; padding: 20px;">
                    <i class="fas fa-inbox" style="font-size: 30px; margin-bottom: 10px;"></i><br>
                    Chưa có hoạt động nào.
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Lấy Context của thẻ Canvas
    const ctx = document.getElementById('incomeChart').getContext('2d');

    // Dữ liệu từ PHP
    const labels = <?= $chartLabels ?>;
    const dataValues = <?= $chartValues ?>;

    // Cấu hình Gradient màu sắc cho đẹp
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(52, 152, 219, 0.5)'); // Màu đậm ở trên
    gradient.addColorStop(1, 'rgba(52, 152, 219, 0.0)'); // Nhạt dần xuống dưới

    new Chart(ctx, {
        type: 'line', // Loại biểu đồ: Đường (line)
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu (VND)',
                data: dataValues,
                borderColor: '#3498db', // Màu đường kẻ
                backgroundColor: gradient, // Màu nền gradient
                borderWidth: 2,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#3498db',
                pointRadius: 4,
                fill: true, // Tô màu bên dưới đường kẻ
                tension: 0.4 // Độ cong mềm mại của đường (0 là đường thẳng)
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // Ẩn chú thích (vì chỉ có 1 dòng)
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('vi-VN', {
                                    style: 'currency',
                                    currency: 'VND'
                                }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND',
                                maximumSignificantDigits: 3
                            }).format(value);
                        }
                    },
                    grid: {
                        borderDash: [2, 4], // Đường kẻ ngang nét đứt
                        color: '#ecf0f1'
                    }
                },
                x: {
                    grid: {
                        display: false // Ẩn đường kẻ dọc
                    }
                }
            }
        }
    });
});
</script>