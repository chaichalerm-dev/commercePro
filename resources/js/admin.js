import {
    Chart,
    LineController,
    LineElement,
    PointElement,
    LinearScale,
    CategoryScale,
    DoughnutController,
    ArcElement,
    Filler,
    Tooltip,
    Legend,
} from 'chart.js';

// Register only what the dashboard uses to keep the bundle small.
Chart.register(
    LineController, LineElement, PointElement, LinearScale, CategoryScale,
    DoughnutController, ArcElement, Filler, Tooltip, Legend,
);

const PRIMARY = '#f97316';
const STATUS_COLORS = ['#f59e0b', '#3b82f6', '#8b5cf6', '#10b981', '#ef4444'];

document.addEventListener('DOMContentLoaded', () => {
    const dataEl = document.getElementById('dashboard-data');
    if (!dataEl) return;

    const { revenue, status, revenueLabel } = JSON.parse(dataEl.textContent);
    const baht = (value) => '฿' + Number(value).toLocaleString();

    const revenueCanvas = document.getElementById('revenueChart');
    if (revenueCanvas) {
        new Chart(revenueCanvas, {
            type: 'line',
            data: {
                labels: revenue.labels,
                datasets: [{
                    label: revenueLabel,
                    data: revenue.values,
                    borderColor: PRIMARY,
                    backgroundColor: 'rgba(249, 115, 22, 0.08)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 2,
                    pointHoverRadius: 5,
                    borderWidth: 2,
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: (ctx) => baht(ctx.parsed.y) } },
                },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: baht }, grid: { color: '#f3f4f6' } },
                    x: { ticks: { maxTicksLimit: 10 }, grid: { display: false } },
                },
            },
        });
    }

    const statusCanvas = document.getElementById('statusChart');
    if (statusCanvas) {
        new Chart(statusCanvas, {
            type: 'doughnut',
            data: {
                labels: status.labels,
                datasets: [{
                    data: status.values,
                    backgroundColor: STATUS_COLORS,
                    borderWidth: 0,
                }],
            },
            options: {
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 16 } },
                },
            },
        });
    }
});
