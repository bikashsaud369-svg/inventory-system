<?php
require_once 'config.php';

// If already logged in → go to dashboard
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        // ===== TEMPORARY LOGIN (replace with database later) =====
        // Example: username = admin , password = admin123
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['user_id']   = 1;
            $_SESSION['username']  = 'admin';
            $_SESSION['full_name'] = 'Admin';
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }

        //  ===== REAL DATABASE VERSION (uncomment when ready) =====
        $stmt = $pdo->prepare("SELECT id, username, password, full_name FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['username']  = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
        
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Inventory Management System</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>
  <div class="login-card">
    <div class="logo">📦</div>
    <h1>Inventory Management System</h1>
    <p class="subtitle">Login to your account</p>

    <?php if ($error): ?>
      <div style="background:#fee2e2; color:#b91c1c; padding:10px; border-radius:8px; margin-bottom:15px; font-size:14px;">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="input-group">
        <span class="icon">👤</span>
        <input type="text" name="username" placeholder="Username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
      </div>
      <div class="input-group">
        <span class="icon">🔒</span>
        <input type="password" name="password" placeholder="Password" required>
      </div>
      <button type="submit" class="btn">Login</button>
    </form>

    <p class="footer-link">
      Don't have an account? <a href="signup.php">Sign Up</a>
    </p>
  </div>
</body>
</html>