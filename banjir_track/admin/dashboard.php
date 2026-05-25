<?php
// admin/dashboard.php - Dashboard Admin
$title = 'Dashboard - Admin SiBanjir';
include '../includes/header_admin.php';
include '../config/database.php';

// Statistik
$total_laporan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan"))['total'] ?? 0;
$total_diterima = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan WHERE status = 'diterima'"))['total'] ?? 0;
$total_ditindaklanjuti = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan WHERE status = 'ditindaklanjuti'"))['total'] ?? 0;
$total_dikerjakan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan WHERE status = 'dikerjakan'"))['total'] ?? 0;
$total_selesai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan WHERE status = 'selesai'"))['total'] ?? 0;

// Laporan terbaru
$latest = mysqli_query($conn, "SELECT * FROM laporan ORDER BY created_at DESC LIMIT 5");
?>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .stat-number {
        font-size: 32px;
        font-weight: bold;
        color: #1e5f7a;
    }
    
    .stat-label {
        color: #888;
        margin-top: 8px;
    }
    
    .table-container {
        background: white;
        border-radius: 16px;
        padding: 20px;
        overflow-x: auto;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    th {
        background: #1e5f7a;
        color: white;
        padding: 12px;
        text-align: left;
    }
    
    td {
        padding: 10px;
        border-bottom: 1px solid #eee;
    }
    
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }
    
    .status-diterima { background: #fff3cd; color: #856404; }
    .status-ditindaklanjuti { background: #cce5ff; color: #004085; }
    .status-dikerjakan { background: #d4edda; color: #155724; }
    .status-selesai { background: #d4edda; color: #155724; }
    
    .btn-detail {
        background: #1e5f7a;
        color: white;
        padding: 5px 12px;
        border-radius: 15px;
        text-decoration: none;
        font-size: 12px;
    }
</style>

<h1>📊 Dashboard Admin BPBD</h1>
<p>Selamat datang, <?= $_SESSION['admin_nama'] ?></p>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number"><?= $total_laporan ?></div>
        <div class="stat-label">Total Laporan</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $total_diterima ?></div>
        <div class="stat-label">Diterima</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $total_ditindaklanjuti ?></div>
        <div class="stat-label">Ditindaklanjuti</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $total_dikerjakan ?></div>
        <div class="stat-label">Dikerjakan</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $total_selesai ?></div>
        <div class="stat-label">Selesai</div>
    </div>
</div>

<div class="table-container">
    <h3>📋 Laporan Terbaru</h3>
    <table>
        <thead>
            <tr><th>ID</th><th>Nama</th><th>Lokasi</th><th>Status</th><th>Tgl Lapor</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($latest)): ?>
            <tr>
                <td>BJR-<?= str_pad($row['id'], 4, '0', STR_PAD_LEFT) ?></td>
                <td><?= $row['nama_pelapor'] ?></td>
                <td><?= substr($row['jalan'], 0, 40) ?>...</td>
                <td><span class="status-badge status-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
                <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                <td><a href="detail_laporan.php?id=<?= $row['id'] ?>" class="btn-detail">Detail</a></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer_admin.php'; ?>