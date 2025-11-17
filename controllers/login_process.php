<?php
// controllers/login_process.php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once "../utils/csrf.php";
require_once "../utils/session_handler.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/login.php");
    exit;
}

if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Permintaan tidak valid.'];
    header("Location: ../views/login.php");
    exit;
}

// Ambil input
$email = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Semua kolom wajib diisi.'];
    header("Location: ../views/login.php");
    exit;
}

// Konstanta kebijakan
define('MAX_ATTEMPTS_EMAIL', 10);
define('LOCK_SECONDS', 300); 
define('MAX_ATTEMPTS_IP', 20);
define('IP_LOCK_SECONDS', 200);

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

// 1) Cek rate-limit by IP
$ip_stmt = $conn->prepare("SELECT attempts, last_attempt FROM login_attempts_ip WHERE ip_address = ?");
$ip_stmt->bind_param("s", $ip);
$ip_stmt->execute();
$ip_row = $ip_stmt->get_result()->fetch_assoc();
$ip_stmt->close();

if ($ip_row) {
    $elapsed_ip = time() - strtotime($ip_row['last_attempt']);
    if ($ip_row['attempts'] >= MAX_ATTEMPTS_IP && $elapsed_ip < IP_LOCK_SECONDS) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Terlalu banyak percobaan dari alamat ini. Coba lagi beberapa menit.'];
        header("Location: ../views/login.php");
        exit;
    } elseif ($elapsed_ip >= IP_LOCK_SECONDS) {
        $del_ip = $conn->prepare("DELETE FROM login_attempts_ip WHERE ip_address = ?");
        $del_ip->bind_param("s", $ip);
        $del_ip->execute();
        $del_ip->close();
        $ip_row = null;
    }
}

// 2) Cek lock berdasarkan email
$lock_stmt = $conn->prepare("SELECT attempts, last_attempt FROM login_attempts WHERE email = ?");
$lock_stmt->bind_param("s", $email);
$lock_stmt->execute();
$lock = $lock_stmt->get_result()->fetch_assoc();
$lock_stmt->close();

if ($lock && $lock['attempts'] >= MAX_ATTEMPTS_EMAIL) {
    $elapsed = time() - strtotime($lock['last_attempt']);
    if ($elapsed < LOCK_SECONDS) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Akun terkunci. Coba lagi beberapa menit.'];
        header("Location: ../views/login.php");
        exit;
    } else {
        $del_lock = $conn->prepare("DELETE FROM login_attempts WHERE email = ?");
        $del_lock->bind_param("s", $email);
        $del_lock->execute();
        $del_lock->close();
        $lock = null;
    }
}

// 3) Ambil user
$user_stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE LOWER(email) = ?");
$user_stmt->bind_param("s", $email);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();
$user_stmt->close();

// 4) Verifikasi
$login_success = false;
if ($user && password_verify($password, $user['password'])) {
    $login_success = true;
}

// 5) Jika sukses
if ($login_success) {
    $del_email = $conn->prepare("DELETE FROM login_attempts WHERE email = ?");
    $del_email->bind_param("s", $email);
    $del_email->execute();
    $del_email->close();

    $del_ip = $conn->prepare("DELETE FROM login_attempts_ip WHERE ip_address = ?");
    $del_ip->bind_param("s", $ip);
    $del_ip->execute();
    $del_ip->close();

    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['last_active'] = time();

    header("Location: ../views/home.php");
    exit;
}

// 6) Jika gagal: increment attempts
$conn->begin_transaction();

try {
    $exists_stmt = $conn->prepare("SELECT attempts FROM login_attempts WHERE email = ?");
    $exists_stmt->bind_param("s", $email);
    $exists_stmt->execute();
    $row = $exists_stmt->get_result()->fetch_assoc();
    $exists_stmt->close();

    if ($row) {
        $upd = $conn->prepare("UPDATE login_attempts SET attempts = attempts + 1, last_attempt = NOW() WHERE email = ?");
        $upd->bind_param("s", $email);
        $upd->execute();
        $upd->close();
    } else {
        $ins = $conn->prepare("INSERT INTO login_attempts (email, attempts, last_attempt) VALUES (?, 1, NOW())");
        $ins->bind_param("s", $email);
        $ins->execute();
        $ins->close();
    }

    $exists_ip = $conn->prepare("SELECT attempts FROM login_attempts_ip WHERE ip_address = ?");
    $exists_ip->bind_param("s", $ip);
    $exists_ip->execute();
    $row_ip = $exists_ip->get_result()->fetch_assoc();
    $exists_ip->close();

    if ($row_ip) {
        $upd_ip = $conn->prepare("UPDATE login_attempts_ip SET attempts = attempts + 1, last_attempt = NOW() WHERE ip_address = ?");
        $upd_ip->bind_param("s", $ip);
        $upd_ip->execute();
        $upd_ip->close();
    } else {
        $ins_ip = $conn->prepare("INSERT INTO login_attempts_ip (ip_address, attempts, last_attempt) VALUES (?, 1, NOW())");
        $ins_ip->bind_param("s", $ip);
        $ins_ip->execute();
        $ins_ip->close();
    }

    $conn->commit();
} catch (Exception $e) {
    $conn->rollback();
}

// 7) Jika sampai sini berarti gagal login
$_SESSION['flash'] = ['type' => 'error', 'message' => 'Email atau password salah.'];
header("Location: ../views/login.php");
exit;


