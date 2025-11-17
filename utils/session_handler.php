<?php
// utils/session_handler.php
// Panggil ini di awal setiap entry point (index.php, views/home.php, controllers jika perlu)
if (session_status() === PHP_SESSION_NONE) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '', // set domain jika perlu kyahh
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// Fungsi helper untuk proteksi halaman terproteksi
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /project-login/views/login.php");
        exit;
    }
    // timeout 5 menit = 300 detik
    if (!isset($_SESSION['last_active']) || time() - $_SESSION['last_active'] > 300) {
        session_unset();
        session_destroy();
        header("Location: /project-login/views/login.php?timeout=1");
        exit;
    }
    $_SESSION['last_active'] = time();
}
