<?php
require_once 'config.php';
requireLogin();

$username = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Admin';
$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price    = trim($_POST['price'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');

    if (empty($name) || empty($category) || empty($price) || empty($quantity)) {
        $error = "Please fill in all required fields.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, category, price, quantity) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $category, $price, $quantity]);
            header("Location: products.php?success=Product added successfully");
            exit;
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Product - Inventory</title>
  <link rel="stylesheet" href="add-product.css">
</head>
<body>
  <div class="sidebar">
    <div class="brand"><span>📦</span> Inventory</div>
    <nav class="nav">
      <a href="dashboard.php">🏠 Dashboard</a>
      <a href="products.php">📦 Products</a>
      <a href="add-product.php" class="active">➕ Add Product</a>
      <a href="logout.php">🚪 Logout</a>
    </nav>
  </div>

  <div class="main">
    <div class="topbar">
      <h2>Add Product</h2>
      <div class="user">Welcome, <?= htmlspecialchars($username) ?> 👤</div>
    </div>

    <div class="content">
      <div class="form-card">
        <?php if ($error): ?>
          <div style="background:#fee2e2; color:#b91c1c; padding:10px; border-radius:8px; margin-bottom:15px;">
            <?= htmlspecialchars($error) ?>
          </div>
        <?php endif; ?>

        <form method="POST">
          <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" placeholder="Enter product name" required>
          </div>
          <div class="form-group">
            <label>Category</label>
            <select name="category" required>
              <option value="">Select category</option>
              <option value="Food">Food</option>
              <option value="Grocery">Grocery</option>
              <option value="Household">Household</option>
              <option value="Personal Care">Personal Care</option>
            </select>
          </div>
          <div class="form-group">
            <label>Price (Rs.)</label>
            <input type="number" name="price" step="0.01" placeholder="Enter price" required>
          </div>
          <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" placeholder="Enter quantity" required>
          </div>
          <div class="actions">
            <a href="products.php"><button type="button" class="btn btn-cancel">Cancel</button></a>
            <button type="submit" class="btn btn-primary">Add Product</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>