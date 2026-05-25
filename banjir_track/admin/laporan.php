<?php
// admin/laporan.php - Daftar Semua Laporan
$title = 'Daftar Laporan - Admin SiBanjir';
include '../includes/header_admin.php';
include '../config/database.php';

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan");
$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total_data / $limit);

$query = "SELECT * FROM laporan ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
?>

<style>
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
    
    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 20px;
    }
    
    .pagination a {
        padding: 8px 12px;
        border-radius: 8px;
        text-decoration: none;
        color: #1e5f7a;
        background: white;
        border: 1px solid #ddd;
    }
    
    .pagination a.active {
        background: #1e5f7a;
        color: white;
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>📋 Semua Laporan Banjir</h1>
    <a href="laporan_excel.php" style="background: #28a745; color: white; padding: 10px 20px; border-radius: 25px; text-decoration: none;">📊 Export Excel</a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Tgl Lapor</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td>BJR-<?= str_pad($row['id'], 4, '0', STR_PAD_LEFT) ?></td>
                <td><?= $row['nama_pelapor'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= substr($row['jalan'], 0, 40) ?>...</td>
                <td><span class="status-badge status-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
                <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                <td><a href="detail_laporan.php?id=<?= $row['id'] ?>" class="btn-detail">Detail</a></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php if ($total_pages > 1): ?>
<div class="pagination">
    <a href="?page=1">«</a>
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
    <a href="?page=<?= $total_pages ?>">»</a>
</div>
<?php endif; ?>

<?php include '../includes/footer_admin.php'; ?>