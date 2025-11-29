<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
include('../includes/connect.php');

$res = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
?>
<!doctype html>
<html>
<head><title>Orders</title></head>
<body>
<h1>Orders</h1>
<a href="index.php">Back</a>
<table border="1" cellpadding="8" cellspacing="0">
  <tr><th>ID</th><th>Customer</th><th>Email</th><th>Total</th><th>Date</th><th>Status</th><th>Action</th></tr>
  <?php while($o = $res->fetch_assoc()): ?>
    <tr>
      <td><?php echo $o['id']; ?></td>
      <td><?php echo htmlspecialchars($o['customer_name']); ?></td>
      <td><?php echo htmlspecialchars($o['customer_email']); ?></td>
      <td><?php echo number_format($o['total_amount'],2); ?></td>
      <td><?php echo $o['created_at']; ?></td>
      <td><?php echo htmlspecialchars($o['status']); ?></td>
      <td>
        <a href="order_details.php?id=<?php echo $o['id']; ?>">View</a>
        |
        <a href="view_orders.php?mark_shipped=<?php echo $o['id']; ?>">Mark Shipped</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<?php
if (isset($_GET['mark_shipped'])) {
    $id = intval($_GET['mark_shipped']);
    $conn->query("UPDATE orders SET status='shipped' WHERE id=$id");
    header("Location: view_orders.php");
    exit;
}
?>
</body>
</html>