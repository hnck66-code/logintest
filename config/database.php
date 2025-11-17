<?php

// Jika berjalan di GitHub Actions (CI), jangan connect database
if (getenv('CI') === 'true') {
    // Return dummy connection
    return [
        'connection' => null
    ];
}

// Normal di localhost XAMPP
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_login";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

return [
    'connection' => $conn
];
