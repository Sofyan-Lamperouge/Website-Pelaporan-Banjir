<?php
// status.php - Cek Status Laporan Banjir
$title = 'Cek Status Laporan - SiBanjir';
include 'includes/header.php';
include 'config/database.php';

$laporan = null;
$error = '';
$id_laporan = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_laporan = mysqli_real_escape_string($conn, $_POST['id_laporan']);
    
    // Ambil data laporan berdasarkan ID (format BJR-0001)
    $id_number = str_replace('BJR-', '', $id_laporan);
    $id_number = (int)$id_number;
    
    $query = "SELECT * FROM laporan WHERE id = $id_number";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $laporan = mysqli_fetch_assoc($result);
    } else {
        $error = "❌ Laporan dengan ID <strong>$id_laporan</strong> tidak ditemukan.";
    }
}
?>

<style>
    .search-container {
        max-width: 600px;
        margin: 0 auto 30px;
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .search-container h1 {
        color: #1e5f7a;
        margin-bottom: 10px;
        text-align: center;
    }
    
    .search-container p {
        text-align: center;
        color: #666;
        margin-bottom: 25px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }
    
    input {
        width: 100%;
        padding: 12px;
        border: 2px solid #e1e1e1;
        border-radius: 10px;
        font-size: 14px;
    }
    
    input:focus {
        outline: none;
        border-color: #f4a261;
    }
    
    .btn-search {
        width: 100%;
        padding: 14px;
        background: #1e5f7a;
        color: white;
        border: none;
        border-radius: 30px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
    }
    
    .btn-search:hover {
        background: #0f4b63;
    }
    
    .result-container {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .status-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #eee;
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
        width: 130px;
        font-weight: 600;
        color: #555;
    }
    
    .info-value {
        flex: 1;
        color: #333;
    }
    
    .foto-preview {
        max-width: 300px;
        margin-top: 10px;
        border-radius: 10px;
    }
    
    .alert-error {
        background: #fee;
        color: #e63946;
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 20px;
        text-align: center;
    }
    
    .btn-back {
        background: #f4a261;
        color: white;
        padding: 8px 20px;
        border-radius: 25px;
        text-decoration: none;
        display: inline-block;
    }
    
    @media (max-width: 768px) {
        .info-row {
            flex-direction: column;
        }
        .info-label {
            width: 100%;
            margin-bottom: 5px;
        }
    }
</style>

<div class="search-container">
    <h1>🔍 Cek Status Laporan</h1>
    <p>Masukkan ID Laporan yang Anda terima saat melapor</p>
    
    <?php if ($error): ?>
        <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label>ID Laporan</label>
            <input type="text" name="id_laporan" placeholder="Contoh: BJR-0001" value="<?= htmlspecialchars($id_laporan) ?>" required>
        </div>
        <button type="submit" class="btn-search">🔍 Cek Status</button>
    </form>
</div>

<?php if ($laporan): ?>
<div class="result-container">
    <div class="status-header">
        <h2>📋 Detail Laporan</h2>
        <div>
            <?php
            $status_class = '';
            switch($laporan['status']) {
                case 'diterima': $status_class = 'status-diterima'; break;
                case 'ditindaklanjuti': $status_class = 'status-ditindaklanjuti'; break;
                case 'dikerjakan': $status_class = 'status-dikerjakan'; break;
                case 'selesai': $status_class = 'status-selesai'; break;
            }
            ?>
            <span class="status-badge <?= $status_class ?>">
                <?= ucfirst($laporan['status']) ?>
            </span>
        </div>
    </div>
    
    <div class="info-row">
        <div class="info-label">ID Laporan</div>
        <div class="info-value"><strong>BJR-<?= str_pad($laporan['id'], 4, '0', STR_PAD_LEFT) ?></strong></div>
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
    
    <?php if ($laporan['foto'] && file_exists('uploads/banjir/' . $laporan['foto'])): ?>
    <div class="info-row">
        <div class="info-label">Foto Banjir</div>
        <div class="info-value">
            <img src="uploads/banjir/<?= $laporan['foto'] ?>" class="foto-preview" alt="Foto Banjir">
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($laporan['latitude'] && $laporan['longitude']): ?>
    <div class="info-row">
        <div class="info-label">Lokasi GPS</div>
        <div class="info-value">
            Lat: <?= $laporan['latitude'] ?>, Lng: <?= $laporan['longitude'] ?>
            <a href="https://www.google.com/maps?q=<?= $laporan['latitude'] ?>,<?= $laporan['longitude'] ?>" target="_blank" style="color: #1e5f7a;">📍 Lihat di Peta</a>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="info-row">
        <div class="info-label">Tanggal Lapor</div>
        <div class="info-value"><?= date('d F Y H:i', strtotime($laporan['created_at'])) ?></div>
    </div>
    
    <?php if ($laporan['catatan_admin']): ?>
    <div class="info-row">
        <div class="info-label">Catatan BPBD</div>
        <div class="info-value" style="background: #f0f2f5; padding: 10px; border-radius: 10px;">
            <?= nl2br(htmlspecialchars($laporan['catatan_admin'])) ?>
        </div>
    </div>
    <?php endif; ?>
    
    <div style="margin-top: 20px; text-align: center;">
        <a href="lapor.php" class="btn-back">📝 Laporkan Banjir Lain</a>
    </div>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>