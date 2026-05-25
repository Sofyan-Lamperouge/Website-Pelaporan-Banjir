<?php
// lapor.php - Form Laporan Banjir
$title = 'Lapor Banjir - SiBanjir';
include 'includes/header.php';
include 'config/database.php'; 

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pelapor = mysqli_real_escape_string($conn, $_POST['nama_pelapor']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $jalan = mysqli_real_escape_string($conn, $_POST['jalan']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $latitude = mysqli_real_escape_string($conn, $_POST['latitude']);
    $longitude = mysqli_real_escape_string($conn, $_POST['longitude']);
    
    // Upload foto
    $foto = '';
    $target_dir = "uploads/banjir/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto = time() . '_' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], $target_dir . $foto);
    }
    
    $query = "INSERT INTO laporan (nama_pelapor, email, jalan, foto, keterangan, latitude, longitude, status) 
              VALUES ('$nama_pelapor', '$email', '$jalan', '$foto', '$keterangan', '$latitude', '$longitude', 'diterima')";
    
    if (mysqli_query($conn, $query)) {
        $id_laporan = mysqli_insert_id($conn);
        $success = 'BJR-' . str_pad($id_laporan, 4, '0', STR_PAD_LEFT);
    } else {
        $error = "❌ Gagal menyimpan laporan: " . mysqli_error($conn);
    }
}
?>

<style>
    .form-container {
        max-width: 700px;
        margin: 0 auto;
        background: white;
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .form-container h1 {
        color: #1e5f7a;
        margin-bottom: 10px;
        text-align: center;
    }
    .form-container p {
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
    input, textarea {
        width: 100%;
        padding: 12px;
        border: 2px solid #e1e1e1;
        border-radius: 10px;
        font-size: 14px;
    }
    input:focus, textarea:focus {
        outline: none;
        border-color: #f4a261;
    }
    .btn-submit {
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
    .btn-submit:hover {
        background: #0f4b63;
    }
    .alert-error {
        background: #fee;
        color: #e63946;
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        text-align: center;
    }
    .row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .location-info {
        background: #f0f2f5;
        padding: 10px;
        border-radius: 10px;
        font-size: 12px;
        margin-top: 5px;
    }
    button[type="button"] {
        background: #f4a261;
        margin-top: 5px;
        padding: 8px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        color: white;
        font-weight: 600;
    }
    @media (max-width: 768px) {
        .row-2 {
            grid-template-columns: 1fr;
        }
        .form-container {
            padding: 20px;
        }
    }
</style>

<div class="form-container">
    <h1>🌊 Lapor Banjir</h1>
    <p>Isi formulir di bawah untuk melaporkan kejadian banjir di lingkungan Anda</p>
    
    <?php if ($error): ?>
        <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert-success">
            <h2>✅ Laporan Berhasil Dikirim!</h2>
            <p style="font-size: 18px; margin: 15px 0;">
                ID Laporan Anda: <strong style="font-size: 28px; color: #1e5f7a;"><?= $success ?></strong>
            </p>
            <p>Simpan ID laporan ini untuk mengecek status penanganan banjir.</p>
            <div style="margin-top: 20px;">
                <a href="status.php" style="display: inline-block; background: #1e5f7a; color: white; padding: 10px 25px; border-radius: 25px; text-decoration: none;">🔍 Cek Status Laporan</a>
                <a href="lapor.php" style="display: inline-block; background: #f4a261; color: white; padding: 10px 25px; border-radius: 25px; text-decoration: none; margin-left: 10px;">📝 Lapor Lagi</a>
            </div>
        </div>
    <?php else: ?>
    
    <form method="POST" enctype="multipart/form-data" id="laporForm">
        <div class="row-2">
            <div class="form-group">
                <label>Nama Pelapor *</label>
                <input type="text" name="nama_pelapor" required placeholder="Nama lengkap">
            </div>
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" required placeholder="email@example.com">
            </div>
        </div>
        
        <div class="form-group">
            <label>📍 Lokasi Jalan / Perumahan *</label>
            <input type="text" name="jalan" required placeholder="Contoh: Jl. Merdeka No.10, RT 01/RW 02, Kelurahan...">
        </div>
        
        <div class="form-group">
            <label>📷 Foto Banjir</label>
            <input type="file" name="foto" accept="image/*">
            <small style="color: #888;">Upload foto banjir (JPG, PNG, maks 2MB)</small>
        </div>
        
        <div class="form-group">
            <label>📝 Keterangan</label>
            <textarea name="keterangan" rows="3" placeholder="Contoh: Ketinggian air sekitar 50cm, akses jalan terputus, warga mengungsi..."></textarea>
        </div>
        
        <div class="row-2">
            <div class="form-group">
                <label>📍 Latitude (GPS)</label>
                <input type="text" name="latitude" id="latitude" readonly placeholder="Klik 'Ambil Lokasi'">
            </div>
            <div class="form-group">
                <label>📍 Longitude (GPS)</label>
                <input type="text" name="longitude" id="longitude" readonly placeholder="Klik 'Ambil Lokasi'">
            </div>
        </div>
        
        <button type="button" onclick="getLocation()">📍 Ambil Lokasi Saya</button>
        <div id="locationStatus" class="location-info"></div>
        
        <button type="submit" class="btn-submit">📤 Kirim Laporan</button>
    </form>
    
    <?php endif; ?>
</div>

<script>
    function getLocation() {
        const statusDiv = document.getElementById('locationStatus');
        statusDiv.innerHTML = '⏳ Mengambil lokasi...';
        
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            statusDiv.innerHTML = '❌ Browser tidak mendukung GPS';
        }
    }
    
    function showPosition(position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;
        
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        document.getElementById('locationStatus').innerHTML = '✅ Lokasi berhasil diambil: ' + lat + ', ' + lng;
    }
    
    function showError(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED:
                document.getElementById('locationStatus').innerHTML = '❌ Izin lokasi ditolak. Silakan aktifkan GPS.';
                break;
            case error.POSITION_UNAVAILABLE:
                document.getElementById('locationStatus').innerHTML = '❌ Lokasi tidak tersedia.';
                break;
            case error.TIMEOUT:
                document.getElementById('locationStatus').innerHTML = '❌ Timeout. Coba lagi.';
                break;
            default:
                document.getElementById('locationStatus').innerHTML = '❌ Terjadi kesalahan.';
        }
    }
</script>

<?php include 'includes/footer.php'; ?>