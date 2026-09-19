<?php
require_once 'config.php';
requireLogin();

$username = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Admin';
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
      <a href="add-product.php">➕ Add Product</a>
      <a href="logout.php">🚪 Logout</a>
    </nav>
  </div>

  <div class="main">
    <div class="topbar">
      <h2>Dashboard</h2>
      <div class="user">Welcome, <?= htmlspecialchars($username) ?> 👤</div>
    </div>

    <div class="content">
      <div class="stats">
        <div class="stat-card">
          <div class="icon">📦</div>
          <div>
            <h3>Total Products</h3>
            <p>12</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="icon">🗄️</div>
          <div>
            <h3>Total Stock</h3>
            <p>248</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="icon">⚠️</div>
          <div>
            <h3>Low Stock</h3>
            <p>3</p>
          </div>
        </div>
      </div>

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
            <tr>
              <td>1</td><td>Rice</td><td>Food</td><td>Rs. 120</td><td>50</td>
              <td>
                <a href="edit-product.php?id=1"><button class="btn-sm">Edit</button></a>
                <button class="btn-sm btn-danger">Delete</button>
              </td>
            </tr>
            <tr>
              <td>2</td><td>Cooking Oil</td><td>Grocery</td><td>Rs. 250</td><td>20</td>
              <td>
                <a href="edit-product.php?id=2"><button class="btn-sm">Edit</button></a>
                <button class="btn-sm btn-danger">Delete</button>
              </td>
            </tr>
            <tr>
              <td>3</td><td>Soap</td><td>Household</td><td>Rs. 55</td><td>5</td>
              <td>
                <a href="edit-product.php?id=3"><button class="btn-sm">Edit</button></a>
                <button class="btn-sm btn-danger">Delete</button>
              </td>
            </tr>
            <tr>
              <td>4</td><td>Biscuit</td><td>Food</td><td>Rs. 40</td><td>30</td>
              <td>
                <a href="edit-product.php?id=4"><button class="btn-sm">Edit</button></a>
                <button class="btn-sm btn-danger">Delete</button>
              </td>
            </tr>
            <tr>
              <td>5</td><td>Shampoo</td><td>Personal Care</td><td>Rs. 180</td><td>12</td>
              <td>
                <a href="edit-product.php?id=5"><button class="btn-sm">Edit</button></a>
                <button class="btn-sm btn-danger">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>