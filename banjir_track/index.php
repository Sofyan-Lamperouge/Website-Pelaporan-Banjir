<?php
// index.php - Halaman Depan SiBanjir
$title = 'Beranda - SiBanjir';
include 'includes/header.php';
?>

<div style="background: linear-gradient(135deg, #1e5f7a 0%, #0f4b63 100%); border-radius: 20px; padding: 50px; text-align: center; color: white; margin-bottom: 30px;">
    <h1 style="font-size: 42px;">🌊 SiBanjir</h1>
    <p style="font-size: 18px; margin: 15px 0;">Sistem Informasi Pelaporan Banjir Warga</p>
    <p>Laporkan kejadian banjir di lingkungan Anda, agar segera ditangani oleh BPBD.</p>
    <a href="lapor.php" style="display: inline-block; margin-top: 20px; background: #f4a261; color: white; padding: 12px 30px; border-radius: 30px; text-decoration: none; font-weight: 600;">Laporkan Sekarang →</a>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px;">
    <div style="background: white; border-radius: 16px; padding: 25px; text-align: center;">
        <h2>📝 1</h2>
        <h3>Lapor Banjir</h3>
        <p>Isi formulir laporan dengan lokasi & foto</p>
    </div>
    <div style="background: white; border-radius: 16px; padding: 25px; text-align: center;">
        <h2>✅ 2</h2>
        <h3>Diproses BPBD</h3>
        <p>Laporan akan diverifikasi dan ditindaklanjuti</p>
    </div>
    <div style="background: white; border-radius: 16px; padding: 25px; text-align: center;">
        <h2>📊 3</h2>
        <h3>Cek Status</h3>
        <p>Pantau status laporan Anda secara实时</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>