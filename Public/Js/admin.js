// public/js/admin.js

document.addEventListener('DOMContentLoaded', function () {
    const incomeChartCanvas = document.getElementById('incomeChart');
    
    if (incomeChartCanvas && typeof dashboardStats !== 'undefined') {
        
        console.log("admin.js loaded, dashboardStats received:", dashboardStats);

        let labels = [];
        let monthlyIncomeData = [];

        // Process the monthly income data received from the controller
        if (dashboardStats.monthly_income && dashboardStats.monthly_income.length > 0) {
            // Create a map for all months in the last year to ensure we have 12 points
            const last12Months = new Map();
            let date = new Date();
            date.setDate(1); // Go to the first of the current month
            for (let i = 0; i < 12; i++) {
                // Format key as YYYY-M
                const key = `${date.getFullYear()}-${date.getMonth() + 1}`;
                last12Months.set(key, 0);
                date.setMonth(date.getMonth() - 1);
            }

            // Fill the map with data from the database
            dashboardStats.monthly_income.forEach(item => {
                const key = `${item.year}-${item.month}`;
                last12Months.set(key, parseFloat(item.monthly_income));
            });
            
            // Convert map to arrays for the chart, in chronological order
            const sortedKeys = Array.from(last12Months.keys()).reverse();
            
            sortedKeys.forEach(key => {
                const [year, month] = key.split('-');
                labels.push(`${month}/${year}`);
                monthlyIncomeData.push(last12Months.get(key));
            });

        } else {
            // Fallback to empty data if nothing is passed
            labels = ['Không có dữ liệu'];
            monthlyIncomeData = [0];
        }

        const ctx = incomeChartCanvas.getContext('2d');
        const incomeChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Doanh thu',
                    data: monthlyIncomeData,
                    backgroundColor: 'rgba(231, 76, 60, 0.2)',
                    borderColor: 'rgba(231, 76, 60, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                     label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }
});
