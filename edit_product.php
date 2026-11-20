<?php
require_once 'config.php';
requireLogin();

$username = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Admin';
$id = $_GET['id'] ?? 0; // Get product ID from URL
$message = "";
$error = "";

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// If product not found, go back to products page
if (!$product) {
    header("Location: products.php");
    exit;
}


// UPDATE Product when form is submitted

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price    = trim($_POST['price'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');

    if (empty($name) || empty($category) || empty($price) || empty($quantity)) {
        $error = "Please fill in all required fields.";
    } else {
        try {
            // UPDATE query using Prepared Statement
            $stmt = $pdo->prepare("UPDATE products SET name=?, category=?, price=?, quantity=? WHERE id=?");
            $stmt->execute([$name, $category, $price, $quantity, $id]);

            // Redirect after successful update
            header("Location: products.php?success=Product updated successfully");
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
  <title>Edit Product - Inventory</title>
  <link rel="stylesheet" href="edit-product.css">
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
      <h2>Edit Product</h2>
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
            <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
          </div>
          <div class="form-group">
            <label>Category</label>
            <select name="category" required>
              <option value="Food" <?= $product['category'] == 'Food' ? 'selected' : '' ?>>Food</option>
              <option value="Grocery" <?= $product['category'] == 'Grocery' ? 'selected' : '' ?>>Grocery</option>
              <option value="Household" <?= $product['category'] == 'Household' ? 'selected' : '' ?>>Household</option>
              <option value="Personal Care" <?= $product['category'] == 'Personal Care' ? 'selected' : '' ?>>Personal Care</option>
            </select>
          </div>
          <div class="form-group">
            <label>Price (Rs.)</label>
            <input type="number" name="price" step="0.01" value="<?= htmlspecialchars($product['price']) ?>" required>
          </div>
          <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" value="<?= htmlspecialchars($product['quantity']) ?>" required>
          </div>
          <div class="actions">
            <a href="products.php"><button type="button" class="btn btn-cancel">Cancel</button></a>
            <button type="submit" class="btn btn-primary">Update Product</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
