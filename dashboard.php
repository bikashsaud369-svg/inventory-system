<?php
// ============================================
// DASHBOARD.PHP - Main Overview Page
// ============================================

require_once 'config.php';
requireLogin(); // Only logged-in users can access this page

// Get logged-in user's name from session
$username = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Admin';

// ============================================
// Get Real Data from Database
// ============================================

// Total number of products
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();

// Total stock quantity of all products
$totalStock = $pdo->query("SELECT SUM(quantity) FROM products")->fetchColumn() ?? 0;

// Count products that have low stock (quantity 10 or less)
$lowStock = $pdo->query("SELECT COUNT(*) FROM products WHERE quantity <= 10")->fetchColumn();

// Get latest 5 products for the table
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC LIMIT 5");
$recentProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Inventory</title>
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>
  <div class="sidebar">
    <div class="brand"><span>📦</span> Inventory</div>
    <nav class="nav">
      <a href="dashboard.php" class="active">🏠 Dashboard</a>
      <a href="products.php">📦 Products</a>
      <a href="add_product.php">➕ Add Product</a>
      <a href="logout.php">🚪 Logout</a>
    </nav>
  </div>

  <div class="main">
    <div class="topbar">
      <h2>Dashboard</h2>
      <div class="user">Welcome, <?= htmlspecialchars($username) ?> 👤</div>
    </div>

    <div class="content">
      <!-- Statistics Cards -->
      <div class="stats">
        <div class="stat-card">
          <div class="icon">📦</div>
          <div>
            <h3>Total Products</h3>
            <p><?= $totalProducts ?></p> <!-- Real count from database -->
          </div>
        </div>
        <div class="stat-card">
          <div class="icon">🗄️</div>
          <div>
            <h3>Total Stock</h3>
            <p><?= $totalStock ?></p> <!-- Real sum from database -->
          </div>
        </div>
        <div class="stat-card">
          <div class="icon">⚠️</div>
          <div>
            <h3>Low Stock</h3>
            <p><?= $lowStock ?></p> <!-- Real low stock count -->
          </div>
        </div>
      </div>

      <!-- Recent Products Table -->
      <div class="table-card">
        <h3>Recent Products</h3>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Category</th>
              <th>Price</th>
              <th>Stock</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($recentProducts) > 0): ?>
              <?php foreach ($recentProducts as $row): ?>
                <tr>
                  <td><?= $row['id'] ?></td>
                  <td><?= htmlspecialchars($row['name']) ?></td>
                  <td><?= htmlspecialchars($row['category']) ?></td>
                  <td>Rs. <?= number_format($row['price'], 2) ?></td>
                  <td><?= $row['quantity'] ?></td>
                  <td>
                    <a href="edit_product.php?id=<?= $row['id'] ?>">
                      <button class="btn-sm">Edit</button>
                    </a>
                    <a href="products.php?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">
                      <button class="btn-sm btn-danger">Delete</button>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" style="text-align:center; padding:20px;">
                  No products found. <a href="add_product.php">Add your first product</a>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
