<?php
// controllers/logout.php
require_once "../utils/session_handler.php";
if (session_status() === PHP_SESSION_NONE) session_start();
session_unset();
session_destroy();
header("Location: ../views/login.php");
exit;
