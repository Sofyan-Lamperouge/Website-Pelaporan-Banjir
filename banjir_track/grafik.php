<?php
// grafik.php - Grafik Status Laporan Banjir
$title = 'Grafik Laporan - SiBanjir';
include 'includes/header.php';
include 'config/database.php';

// Ambil data statistik per status
$query = "SELECT status, COUNT(*) as total FROM laporan GROUP BY status";
$result = mysqli_query($conn, $query);

$stats = [
    'diterima' => 0,
    'ditindaklanjuti' => 0,
    'dikerjakan' => 0,
    'selesai' => 0
];

while ($row = mysqli_fetch_assoc($result)) {
    $stats[$row['status']] = $row['total'];
}

// Total laporan
$total_laporan = array_sum($stats);
?>

<style>
    .page-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .page-header h1 {
        color: #1e5f7a;
        font-size: 32px;
    }
    
    .page-header p {
        color: #666;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }
    
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .stat-number {
        font-size: 36px;
        font-weight: bold;
        color: #1e5f7a;
    }
    
    .stat-label {
        color: #888;
        margin-top: 8px;
    }
    
    .stat-label span {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 6px;
    }
    
    .chart-container {
        background: white;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .chart-container h3 {
        color: #1e5f7a;
        margin-bottom: 20px;
        text-align: center;
    }
    
    canvas {
        max-height: 400px;
        width: 100% !important;
    }
    
    .two-columns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }
    
    @media (max-width: 768px) {
        .two-columns {
            grid-template-columns: 1fr;
        }
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="page-header">
    <h1>📊 Grafik Laporan Banjir</h1>
    <p>Statistik laporan banjir berdasarkan status penanganan</p>
</div>

<!-- Ringkasan Card -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number"><?= $total_laporan ?></div>
        <div class="stat-label">📋 Total Laporan</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $stats['diterima'] ?></div>
        <div class="stat-label"><span style="background: #ffc107;"></span> Diterima</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $stats['ditindaklanjuti'] ?></div>
        <div class="stat-label"><span style="background: #17a2b8;"></span> Ditindaklanjuti</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $stats['dikerjakan'] ?></div>
        <div class="stat-label"><span style="background: #28a745;"></span> Dikerjakan</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $stats['selesai'] ?></div>
        <div class="stat-label"><span style="background: #6c757d;"></span> Selesai</div>
    </div>
</div>

<div class="two-columns">
    <!-- Bar Chart -->
    <div class="chart-container">
        <h3>📊 Grafik Batang</h3>
        <canvas id="barChart"></canvas>
    </div>
    
    <!-- Pie Chart -->
    <div class="chart-container">
        <h3>🥧 Diagram Lingkaran</h3>
        <canvas id="pieChart"></canvas>
    </div>
</div>

<!-- Donut Chart -->
<div class="chart-container">
    <h3>🍩 Diagram Donut</h3>
    <canvas id="donutChart" style="max-height: 350px; margin: 0 auto;"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Data dari PHP
    const statusLabels = ['Diterima', 'Ditindaklanjuti', 'Dikerjakan', 'Selesai'];
    const statusData = [<?= $stats['diterima'] ?>, <?= $stats['ditindaklanjuti'] ?>, <?= $stats['dikerjakan'] ?>, <?= $stats['selesai'] ?>];
    const statusColors = ['#ffc107', '#17a2b8', '#28a745', '#6c757d'];
    
    // Bar Chart
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: statusLabels,
            datasets: [{
                label: 'Jumlah Laporan',
                data: statusData,
                backgroundColor: statusColors,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: { callbacks: { label: (ctx) => `${ctx.raw} laporan` } }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, title: { display: true, text: 'Jumlah Laporan' } }
            }
        }
    });
    
    // Pie Chart
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: { labels: statusLabels, datasets: [{ data: statusData, backgroundColor: statusColors }] },
        options: { responsive: true, plugins: { legend: { position: 'bottom' }, tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${ctx.raw} laporan (${((ctx.raw / <?= $total_laporan ?>) * 100).toFixed(1)}%)` } } } }
    });
    
    // Donut Chart
    const donutCtx = document.getElementById('donutChart').getContext('2d');
    new Chart(donutCtx, {
        type: 'doughnut',
        data: { labels: statusLabels, datasets: [{ data: statusData, backgroundColor: statusColors }] },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
</script>

<?php include 'includes/footer.php'; ?>