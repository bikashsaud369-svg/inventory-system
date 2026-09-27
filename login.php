<?php
// ============================================
// LOGIN.PHP - User Authentication
// ============================================

require_once 'config.php'; // Include database connection and session functions

// If user is already logged in, go directly to dashboard
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

$error = ""; // Variable to store error message

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get username and password from form (trim removes extra spaces)
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validation: Check if fields are empty
    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {

        // ===== Temporary Login (for testing) =====
        // Username: admin  |  Password: admin123
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['user_id']   = 1;
            $_SESSION['username']  = 'admin';
            $_SESSION['full_name'] = 'Admin';
            header("Location: dashboard.php");
            exit;
        }

        // ===== Real Database Login (Recommended) =====
        // Prepare SQL query with placeholder (?) to prevent SQL Injection
        $stmt = $pdo->prepare("SELECT id, username, password, full_name FROM users WHERE username = ?");
        $stmt->execute([$username]); // Execute query with actual username
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Get user data as array

        // Check if user exists and password is correct
        if ($user && password_verify($password, $user['password'])) {
            // Store user information in session
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['username']  = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];

            // Redirect to dashboard after successful login
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
        <?= htmlspecialchars($error) ?> <!-- Show error message safely -->
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
