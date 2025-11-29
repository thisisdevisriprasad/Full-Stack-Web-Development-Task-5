<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
include('../includes/connect.php');

$order_id = intval($_GET['id']);
$o = $conn->query("SELECT * FROM orders WHERE id=$order_id")->fetch_assoc();
$items = $conn->query("SELECT oi.*, p.name FROM order_items oi JOIN products p ON p.id = oi.menu_item_id WHERE oi.order_id=$order_id");
?>
<!doctype html>
<html>
<head><title>Order #<?php echo $order_id; ?></title></head>
<body>
<a href="view_orders.php">Back to Orders</a>
<h2>Order #<?php echo $order_id; ?></h2>
<p><strong>Customer:</strong> <?php echo htmlspecialchars($o['customer_name']); ?></p>
<p><strong>Email:</strong> <?php echo htmlspecialchars($o['customer_email']); ?></p>
<p><strong>Total:</strong> <?php echo number_format($o['total_amount'],2); ?></p>

<h3>Items</h3>
<table border="1" cellpadding="8">
  <tr><th>Product</th><th>Qty</th><th>Price</th></tr>
  <?php while($it = $items->fetch_assoc()): ?>
    <tr>
      <td><?php echo htmlspecialchars($it['name']); ?></td>
      <td><?php echo intval($it['quantity']); ?></td>
      <td><?php echo number_format($it['price'],2); ?></td>
    </tr>
  <?php endwhile; ?>
</table>
</body>
</html>