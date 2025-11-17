<?php
require_once "../utils/session_handler.php";
require_login();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Beranda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #fe4fd8ff, #00f2fe);
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 700px;
            margin: 80px auto;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            padding: 30px;
            text-align: center;
        }

        h2 {
            color: #444;
            font-size: 28px;
            margin-bottom: 10px;
        }

        p {
            font-size: 18px;
            color: #555;
        }

        .btn {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 25px;
            border: none;
            background: linear-gradient(45deg, #ff6a00, #ee0979);
            color: white;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn:hover {
            transform: scale(1.05);
            background: linear-gradient(45deg, #ee0979, #ff6a00);
        }

        .card {
            margin-top: 25px;
            padding: 20px;
            border-radius: 12px;
            background: #f9f9f9;
            box-shadow: inset 0 0 8px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome-box">
            <h2>Selamat Datang, 
                <span><?php echo htmlspecialchars($_SESSION['full_name'], ENT_QUOTES, 'UTF-8'); ?></span>
            </h2>
            <p>Kamu berhasil login ke sistem</p>
        </div>

        <div class="card">
            <h3>Informasi Akun</h3>
            <ul>
                <li><b>ID:</b> <?php echo htmlspecialchars((string)$_SESSION['user_id'], ENT_QUOTES, 'UTF-8'); ?></li>
                <li><b>Nama:</b> <?php echo htmlspecialchars($_SESSION['full_name'], ENT_QUOTES, 'UTF-8'); ?></li>
                <li><b>Waktu Login:</b> <?php echo date("d-m-Y H:i:s"); ?></li>
            </ul>
        </div>

        <div class="extra-card">
            <h3>Quick Menu</h3>
            <p>Pilih aktivitas berikut:</p>
            <div class="menu-buttons">
                <a href="gallery.php" class="btn">Gallery</a>
                <a href="motivasi.php" class="btn">Motivasi</a>
            </div>
        </div>

        <div class="logout">
            <a href="../controllers/logout.php" class="btn logout-btn">Keluar</a>
        </div>
    </div>
</body>

</html>

