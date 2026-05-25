<?php
// admin/login.php - Login Admin BPBD dengan CAPTCHA
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit();
}

include '../config/database.php';

// ========== GENERATE CAPTCHA ==========
function generateCaptcha() {
    $angka1 = rand(1, 20);
    $angka2 = rand(1, 20);
    $_SESSION['captcha_angka1'] = $angka1;
    $_SESSION['captcha_angka2'] = $angka2;
    $_SESSION['captcha_hasil'] = $angka1 + $angka2;
    return [$angka1, $angka2];
}

if (!isset($_SESSION['captcha_angka1'])) {
    generateCaptcha();
}

$angka1 = $_SESSION['captcha_angka1'];
$angka2 = $_SESSION['captcha_angka2'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);
    $captcha = (int)$_POST['captcha'];
    
    // Validasi CAPTCHA
    if ($captcha != $_SESSION['captcha_hasil']) {
        $error = "❌ Kode keamanan salah!";
        generateCaptcha();
        $angka1 = $_SESSION['captcha_angka1'];
        $angka2 = $_SESSION['captcha_angka2'];
    } else {
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $admin = mysqli_fetch_assoc($result);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_nama'] = $admin['nama'];
            
            generateCaptcha(); // Reset captcha
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "❌ Username atau password salah!";
            generateCaptcha();
            $angka1 = $_SESSION['captcha_angka1'];
            $angka2 = $_SESSION['captcha_angka2'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SiBanjir</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #1e5f7a 0%, #0f4b63 100%);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .login-container {
            background: white;
            border-radius: 20px;
            width: 420px;
            max-width: 90%;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .login-header {
            background: #1e5f7a;
            color: white;
            text-align: center;
            padding: 30px;
        }
        
        .login-header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .login-body {
            padding: 30px;
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
        
        .captcha-group {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
        }
        
        .captcha-question {
            font-size: 24px;
            font-weight: bold;
            background: #1e5f7a;
            color: white;
            display: inline-block;
            padding: 8px 25px;
            border-radius: 40px;
            margin-bottom: 12px;
        }
        
        .captcha-group input {
            text-align: center;
            font-size: 16px;
        }
        
        .refresh-captcha {
            background: #f4a261;
            color: white;
            border: none;
            padding: 6px 15px;
            border-radius: 20px;
            margin-top: 10px;
            cursor: pointer;
        }
        
        .refresh-captcha:hover {
            background: #e76f51;
        }
        
        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background: #1e5f7a;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
        }
        
        button[type="submit"]:hover {
            background: #0f4b63;
        }
        
        .alert-error {
            background: #fee;
            color: #e63946;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .info {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>🌊 SiBanjir</h1>
            <p>Admin BPBD</p>
        </div>
        <div class="login-body">
            <?php if ($error): ?>
                <div class="alert-error"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required placeholder="Masukan username">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Masukan password">
                </div>
                
                <div class="form-group">
                    <label>🔐 Captcha</label>
                    <div class="captcha-group">
                        <div class="captcha-question">
                            <?= $angka1 ?> + <?= $angka2 ?> = ?
                        </div>
                        <input type="number" name="captcha" required placeholder="Hasil penjumlahan">
                        <button type="button" class="refresh-captcha" id="refreshCaptcha">🔄 Ganti Soal</button>
                    </div>
                </div>
                
                <button type="submit">Login</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('refreshCaptcha').addEventListener('click', async function() {
            try {
                const response = await fetch('refresh_captcha.php');
                const data = await response.json();
                document.querySelector('.captcha-question').innerHTML = data.angka1 + ' + ' + data.angka2 + ' = ?';
                document.querySelector('input[name="captcha"]').value = '';
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal mengganti soal, silakan reload halaman.');
            }
        });
    </script>
</body>
</html>