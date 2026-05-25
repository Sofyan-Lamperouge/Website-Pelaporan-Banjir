<?php
// admin/grafik.php - Grafik untuk Admin
$title = 'Grafik Laporan - Admin SiBanjir';
include '../includes/header_admin.php';
include '../config/database.php';

$query = "SELECT status, COUNT(*) as total FROM laporan GROUP BY status";
$result = mysqli_query($conn, $query);

$stats = ['diterima' => 0, 'ditindaklanjuti' => 0, 'dikerjakan' => 0, 'selesai' => 0];
while ($row = mysqli_fetch_assoc($result)) {
    $stats[$row['status']] = $row['total'];
}
?>

<style>
    .chart-container {
        background: white;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 25px;
    }
    
    .chart-container h3 {
        margin-bottom: 20px;
        color: #1e5f7a;
    }
    
    canvas {
        max-height: 400px;
        width: 100% !important;
    }
</style>

<h1>📊 Grafik Laporan Banjir</h1>

<div class="chart-container">
    <h3>📊 Status Laporan</h3>
    <canvas id="statusChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Diterima', 'Ditindaklanjuti', 'Dikerjakan', 'Selesai'],
            datasets: [{
                label: 'Jumlah Laporan',
                data: [<?= $stats['diterima'] ?>, <?= $stats['ditindaklanjuti'] ?>, <?= $stats['dikerjakan'] ?>, <?= $stats['selesai'] ?>],
                backgroundColor: ['#ffc107', '#17a2b8', '#28a745', '#6c757d']
            }]
        },
        options: { responsive: true }
    });
</script>

<?php include '../includes/footer_admin.php'; ?>