<?php
// admin/detail_laporan.php - Detail Laporan & Update Status
$title = 'Detail Laporan - Admin SiBanjir';
include '../includes/header_admin.php';
include '../config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = "SELECT * FROM laporan WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: laporan.php");
    exit();
}

$laporan = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $catatan_admin = mysqli_real_escape_string($conn, $_POST['catatan_admin']);
    
    mysqli_query($conn, "UPDATE laporan SET status = '$status', catatan_admin = '$catatan_admin' WHERE id = $id");
    
    // Log aktivitas
    mysqli_query($conn, "INSERT INTO log_aktivitas (id_laporan, aktivitas) VALUES ($id, 'Status diubah menjadi $status')");
    
    echo "<script>alert('Status berhasil diupdate!'); window.location.href='detail_laporan.php?id=$id';</script>";
}
?>

<style>
    .detail-container {
        background: white;
        border-radius: 16px;
        padding: 25px;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    
    .status-badge {
        padding: 6px 15px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 600;
    }
    
    .status-diterima { background: #fff3cd; color: #856404; }
    .status-ditindaklanjuti { background: #cce5ff; color: #004085; }
    .status-dikerjakan { background: #d4edda; color: #155724; }
    .status-selesai { background: #d4edda; color: #155724; }
    
    .info-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid #eee;
    }
    
    .info-label {
        width: 140px;
        font-weight: 600;
        color: #555;
    }
    
    .info-value {
        flex: 1;
        color: #333;
    }
    
    .foto-preview {
        max-width: 300px;
        border-radius: 10px;
        margin-top: 5px;
    }
    
    select, textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
    }
    
    .btn-update {
        background: #1e5f7a;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 25px;
        cursor: pointer;
        margin-top: 15px;
    }
    
    .btn-back {
        background: #888;
        color: white;
        padding: 10px 20px;
        border-radius: 25px;
        text-decoration: none;
        display: inline-block;
        margin-right: 10px;
    }
</style>

<div class="detail-container">
    <div class="detail-header">
        <h2>📋 Detail Laporan</h2>
        <div>
            <span class="status-badge status-<?= $laporan['status'] ?>">
                <?= ucfirst($laporan['status']) ?>
            </span>
        </div>
    </div>
    
    <div class="info-row">
        <div class="info-label">ID Laporan</div>
        <div class="info-value">BJR-<?= str_pad($laporan['id'], 4, '0', STR_PAD_LEFT) ?></div>
    </div>
    <div class="info-row">
        <div class="info-label">Nama Pelapor</div>
        <div class="info-value"><?= htmlspecialchars($laporan['nama_pelapor']) ?></div>
    </div>
    <div class="info-row">
        <div class="info-label">Email</div>
        <div class="info-value"><?= htmlspecialchars($laporan['email']) ?></div>
    </div>
    <div class="info-row">
        <div class="info-label">Lokasi</div>
        <div class="info-value"><?= htmlspecialchars($laporan['jalan']) ?></div>
    </div>
    <div class="info-row">
        <div class="info-label">Keterangan</div>
        <div class="info-value"><?= nl2br(htmlspecialchars($laporan['keterangan'])) ?></div>
    </div>
    
    <?php if ($laporan['foto'] && file_exists('../uploads/banjir/' . $laporan['foto'])): ?>
    <div class="info-row">
        <div class="info-label">Foto Banjir</div>
        <div class="info-value">
            <img src="../uploads/banjir/<?= $laporan['foto'] ?>" class="foto-preview" alt="Foto Banjir">
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($laporan['latitude'] && $laporan['longitude']): ?>
    <div class="info-row">
        <div class="info-label">Lokasi GPS</div>
        <div class="info-value">
            Lat: <?= $laporan['latitude'] ?>, Lng: <?= $laporan['longitude'] ?>
            <a href="https://www.google.com/maps?q=<?= $laporan['latitude'] ?>,<?= $laporan['longitude'] ?>" target="_blank">📍 Lihat di Peta</a>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="info-row">
        <div class="info-label">Tanggal Lapor</div>
        <div class="info-value"><?= date('d F Y H:i', strtotime($laporan['created_at'])) ?></div>
    </div>
    
    <form method="POST">
        <div class="info-row">
            <div class="info-label">Update Status</div>
            <div class="info-value">
                <select name="status">
                    <option value="diterima" <?= $laporan['status'] == 'diterima' ? 'selected' : '' ?>>Diterima</option>
                    <option value="ditindaklanjuti" <?= $laporan['status'] == 'ditindaklanjuti' ? 'selected' : '' ?>>Ditindaklanjuti</option>
                    <option value="dikerjakan" <?= $laporan['status'] == 'dikerjakan' ? 'selected' : '' ?>>Dikerjakan</option>
                    <option value="selesai" <?= $laporan['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                </select>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Catatan Admin</div>
            <div class="info-value">
                <textarea name="catatan_admin" rows="3" placeholder="Catatan untuk pelapor..."><?= htmlspecialchars($laporan['catatan_admin']) ?></textarea>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label"></div>
            <div class="info-value">
                <a href="laporan.php" class="btn-back">← Kembali</a>
                <button type="submit" class="btn-update">💾 Update Status</button>
            </div>
        </div>
    </form>
</div>

<?php include '../includes/footer_admin.php'; ?>