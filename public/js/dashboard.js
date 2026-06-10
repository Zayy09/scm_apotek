$(document).ready(function() {
    const ctx = document.getElementById('penjualanChart');
    if (ctx) {
        const labels = JSON.parse(ctx.getAttribute('data-bulan'));
        const dataMasuk = JSON.parse(ctx.getAttribute('data-masuk'));
        const dataKeluar = JSON.parse(ctx.getAttribute('data-keluar'));

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Barang Masuk',
                        data: dataMasuk,
                        backgroundColor: '#f1556c',
                        borderRadius: 4,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    },
                    {
                        label: 'Barang Keluar',
                        data: dataKeluar,
                        backgroundColor: '#f7b84b',
                        borderRadius: 4,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // We use custom HTML legend in the view
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#333',
                        bodyColor: '#666',
                        borderColor: '#e3e6f0',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6,
                        usePointStyle: true,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6',
                            drawBorder: false
                        },
                        ticks: {
                            font: { family: "'Segoe UI', sans-serif", size: 12 },
                            color: '#9ca3af'
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: { family: "'Segoe UI', sans-serif", size: 12 },
                            color: '#9ca3af'
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
            }
        });
    }
});
