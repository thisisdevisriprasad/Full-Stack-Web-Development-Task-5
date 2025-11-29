<?php
session_start();
include '../includes/connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Count products and orders for dashboard stats
$totalProducts = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
$totalOrders   = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
$totalUsers    = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard | Food Ordering</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
<div class="container">
  <h2 class="mb-4">Welcome, Admin 👋</h2>

  <div class="row text-center mb-4">
    <div class="col-md-4">
      <div class="card shadow-sm p-3">
        <h4><?php echo $totalProducts; ?></h4>
        <p>Menu Items</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm p-3">
        <h4><?php echo $totalOrders; ?></h4>
        <p>Total Orders</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm p-3">
        <h4><?php echo $totalUsers; ?></h4>
        <p>Registered Users</p>
      </div>
    </div>
  </div>

  <a href="manage_products.php" class="btn btn-primary">Manage Menu Items</a>
  <a href="../logout.php" class="btn btn-danger">Logout</a>
</div>
</body>
</html>