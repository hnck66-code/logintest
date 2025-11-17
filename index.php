<?php
session_start();

// Jika user sudah login → arahkan ke home
if (isset($_SESSION['user_id'])) {
    header("Location: views/home.php");
    exit;
}

// Jika belum login → arahkan ke login
header("Location: views/login.php");
exit;
?>
