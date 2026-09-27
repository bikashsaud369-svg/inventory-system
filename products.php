<?php
require_once 'config.php';
requireLogin();

$username = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Admin';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: products.php?success=Product deleted successfully");
    exit;
}

// Fetch all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products - Inventory</title>
  <link rel="stylesheet" href="products.css">
</head>
<body>
  <div class="sidebar">
    <div class="brand"><span></span> Inventory</div>
    <nav class="nav">
      <a href="dashboard.php"> Dashboard</a>
      <a href="products.php" class="active"> Products</a>
      <a href="add_product.php"> Add Product</a>
      <a href="logout.php"> Logout</a>
    </nav>
  </div>

  <div class="main">
    <div class="topbar">
      <h2>Products</h2>
      <div class="user">Welcome, <?= htmlspecialchars($username) ?> 👤</div>
    </div>

    <div class="content">
      <?php if (isset($_GET['success'])): ?>
        <div style="background:#dcfce7; color:#166534; padding:12px; border-radius:8px; margin-bottom:15px;">
          <?= htmlspecialchars($_GET['success']) ?>
        </div>
      <?php endif; ?>

      <div class="filters">
        <a href="add_product.php"><button class="btn-search">+ Add New Product</button></a>
      </div>

      <div class="table-card">
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
            <?php if (count($products) > 0): ?>
              <?php foreach ($products as $row): ?>
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
                    <a href="products.php?delete=<?= $row['id'] ?>" 
                       onclick="return confirm('Are you sure you want to delete this product?')">
                      <button class="btn-sm btn-danger">Delete</button>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" style="text-align:center; padding:20px;">No products found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>