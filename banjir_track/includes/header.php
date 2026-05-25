<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SiBanjir - Laporan Banjir' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* CSS sama seperti sebelumnya */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f2f5;
            color: #1a2a3a;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .main-content {
            flex: 1;
            padding: 30px 0;
        }
        
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .navbar {
            background: #1e5f7a;
            color: white;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .logo h1 {
            font-size: 24px;
            color: #fff;
        }
        
        .logo span {
            color: #f4a261;
        }
        
        .logo p {
            font-size: 10px;
            opacity: 0.8;
        }
        
        .nav-menu {
            display: flex;
            list-style: none;
            gap: 5px;
        }
        
        .nav-menu li a {
            display: block;
            padding: 20px 18px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .nav-menu li a:hover {
            background: #f4a261;
        }
        
        .nav-menu li a.active {
            background: #f4a261;
        }
        
        footer {
            background: #1e5f7a;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 30px;
        }
        
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                padding: 10px 0;
            }
            .nav-menu {
                flex-wrap: wrap;
                justify-content: center;
                margin: 10px 0;
            }
            .nav-menu li a {
                padding: 8px 12px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="nav-container">
            <div class="logo">
                <h1>🌊 Si<span>Banjir</span></h1>
                <p>Sistem Informasi Pelaporan Banjir</p>
            </div>
            <ul class="nav-menu">
                <!-- Menu hanya untuk USER (tidak berubah meskipun admin login) -->
                <li><a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Beranda</a></li>
                <li><a href="lapor.php">Lapor Banjir</a></li>
                <li><a href="status.php">Cek Status</a></li>
                <li><a href="grafik.php">Grafik</a></li>
            </ul>
        </div>
    </div>
    
    <div class="main-content">
        <div class="container">