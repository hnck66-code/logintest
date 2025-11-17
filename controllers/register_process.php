<?php
session_start();
require_once "../config/database.php";
require_once "../utils/csrf.php";
require_once "../utils/session_handler.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/register.php");
    exit;
}

if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Permintaan tidak valid.'];
    header("Location: ../views/register.php");
    exit;
}

// Ambil input
$full_name = trim($_POST['full_name'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';

if ($full_name === '' || $email === '' || $password === '') {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Semua kolom wajib diisi.'];
    header("Location: ../views/register.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Format email tidak valid.'];
    header("Location: ../views/register.php");
    exit;
}

// Password policy
$pw_pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/";
if (!preg_match($pw_pattern, $password)) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Password harus minimal 8 karakter dan mengandung huruf besar, huruf kecil, angka, dan simbol.'];
    header("Location: ../views/register.php");
    exit;
}

// Cek email sudah ada
$check = $conn->prepare("SELECT id FROM users WHERE LOWER(email) = ?");
$check->bind_param("s", $email);
$check->execute();
$res = $check->get_result();
if ($res->num_rows > 0) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Email sudah terdaftar.'];
    $check->close();
    header("Location: ../views/register.php");
    exit;
}
$check->close();

// Hash password
$hashed = password_hash($password, PASSWORD_BCRYPT);

// Simpan user
$ins = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
$ins->bind_param("sss", $full_name, $email, $hashed);
if ($ins->execute()) {
    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Registrasi berhasil! Silakan login.'];
    $ins->close();
    header("Location: ../views/register.php");
    exit;
} else {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Terjadi kesalahan, silakan coba lagi.'];
    $ins->close();
    header("Location: ../views/register.php");
    exit;
}
