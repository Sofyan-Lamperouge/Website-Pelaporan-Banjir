<?php
// admin/includes/header_admin.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin - SiBanjir' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f2f5;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .navbar {
            background: #1e5f7a;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .logo h2 {
            color: #f4a261;
        }
        
        .nav-links {
            display: flex;
            gap: 20px;
            list-style: none;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
        }
        
        .nav-links a:hover {
            color: #f4a261;
        }
        
        .btn-logout {
            background: #e63946;
            padding: 8px 20px;
            border-radius: 25px;
            color: white;
            text-decoration: none;
        }
        
        .container {
            flex: 1;
            max-width: 1280px;
            margin: 0 auto;
            padding: 30px 20px;
            width: 100%;
        }
        
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <h2>🌊 SiBanjir - Admin BPBD</h2>
        </div>
        <ul class="nav-links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="laporan.php">Laporan</a></li>
            <li><a href="grafik.php">Grafik</a></li>
        </ul>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>
    <div class="container">