<?php
session_start();
require_once __DIR__ . '/../utils/csrf.php';
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to right, #4facfe, #00f2fe);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .container {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
      width: 350px;
      text-align: center;
    }
    h2 {
      margin-bottom: 20px;
      color: #333;
    }
    input {
      width: 90%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
    }
    button {
      width: 95%;
      padding: 12px;
      background: #4facfe;
      border: none;
      border-radius: 8px;
      color: white;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }
    button:hover {
      background: #00c6ff;
    }
    p {
      margin-top: 15px;
      font-size: 14px;
    }
    a {
      color: #4facfe;
      text-decoration: none;
      font-weight: bold;
    }
    a:hover {
      text-decoration: underline;
    }
    .toast {
      position: fixed;
      top: 20px;
      right: 20px;
      min-width: 250px;
      padding: 15px 20px;
      border-radius: 8px;
      color: #fff;
      font-weight: bold;
      z-index: 9999;
      opacity: 0;
      transform: translateY(-20px);
      transition: opacity 0.5s, transform 0.5s;
    }
    .toast.show { opacity: 1; transform: translateY(0); }
    .toast.error { background: #e74c3c; }
    .toast.success { background: #2ecc71; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Registrasi Akun</h2>
    <form action="../controllers/register_process.php" method="POST">
        <?= csrf_input(); ?>
        <input type="text" name="full_name" placeholder="Nama Lengkap" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Sign Up</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login</a></p>
  </div>
  <?php if($flash): ?>
<div id="toast" class="toast <?= $flash['type'] ?>">
    <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?>
</div>
<script>
const toast = document.getElementById('toast');
toast.classList.add('show');
setTimeout(() => toast.classList.remove('show'), 3000);
</script>
<?php endif; ?>
</body>
</html>
