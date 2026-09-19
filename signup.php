<?php
require_once 'config.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $username  = trim($_POST['username'] ?? '');
    $password  = trim($_POST['password'] ?? '');
    $confirm   = trim($_POST['confirm_password'] ?? '');

    if (empty($full_name) || empty($email) || empty($username) || empty($password) || empty($confirm)) {
        $error = "Please fill in all fields.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // ===== TEMPORARY SUCCESS (replace with database later) =====
        $success = "Account created successfully! You can now <a href='login.php'>Login</a>.";

        // ===== REAL DATABASE VERSION =====
        try {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (full_name, email, username, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$full_name, $email, $username, $hashed]);
            $success = "Account created successfully! You can now <a href='login.php'>Login</a>.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Username or Email already exists.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
        
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - Inventory Management System</title>
  <link rel="stylesheet" href="signup.css">
</head>
<body>
  <div class="login-card">
    <div class="logo">📦</div>
    <h1>Inventory Management System</h1>
    <p class="subtitle">Create a new account</p>

    <?php if ($error): ?>
      <div style="background:#fee2e2; color:#b91c1c; padding:10px; border-radius:8px; margin-bottom:15px; font-size:14px;">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div style="background:#dcfce7; color:#166534; padding:10px; border-radius:8px; margin-bottom:15px; font-size:14px;">
        <?= $success ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="input-group">
        <span class="icon">👤</span>
        <input type="text" name="full_name" placeholder="Full Name" required value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
      </div>
      <div class="input-group">
        <span class="icon">✉️</span>
        <input type="email" name="email" placeholder="Email Address" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="input-group">
        <span class="icon">👤</span>
        <input type="text" name="username" placeholder="Username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
      </div>
      <div class="input-group">
        <span class="icon">🔒</span>
        <input type="password" name="password" placeholder="Password" required>
      </div>
      <div class="input-group">
        <span class="icon">🔒</span>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      </div>
      <button type="submit" class="btn">Sign Up</button>
    </form>

    <p class="footer-link">
      Already have an account? <a href="login.php">Login</a>
    </p>
  </div>
</body>
</html>