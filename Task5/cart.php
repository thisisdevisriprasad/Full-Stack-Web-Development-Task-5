<?php
session_start();
include('includes/connect.php');

// ✅ Add item to cart
if (isset($_POST['add_to_cart']) && isset($_POST['product_id'])) {
    $id = intval($_POST['product_id']);

    // Create cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add or increase quantity
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += 1;
    } else {
        $_SESSION['cart'][$id] = 1;
    }

    header("Location: cart.php");
    exit;
}

// ✅ Remove item
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    unset($_SESSION['cart'][$remove_id]);
    header("Location: cart.php");
    exit;
}

// ✅ Fetch items from database
$cart_items = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $ids = array_map('intval', $ids);
    $ids_list = implode(',', $ids);

    if (!empty($ids_list)) {
        $query = "SELECT * FROM products WHERE id IN ($ids_list)";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            $pid = $row['id'];
            $qty = $_SESSION['cart'][$pid];
            $row['quantity'] = $qty;
            $row['subtotal'] = $row['price'] * $qty;
            $total += $row['subtotal'];
            $cart_items[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        table { border-collapse: collapse; width: 70%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background: #eee; }
        a { text-decoration: none; color: red; }
        button { padding: 8px 15px; background: green; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: darkgreen; }
    </style>
</head>
<body>

<h1>🛒 Your Cart</h1>
<a href="index.php">⬅ Back to Menu</a>
<hr>

<?php if (empty($cart_items)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Item</th><th>Price</th><th>Qty</th><th>Subtotal</th><th>Action</th>
        </tr>
        <?php foreach ($cart_items as $item): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td>₹<?php echo number_format($item['price']); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>₹<?php echo number_format($item['subtotal']); ?></td>
            <td><a href="cart.php?remove=<?php echo $item['id']; ?>">Remove</a></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3"><b>Total</b></td>
            <td colspan="2"><b>₹<?php echo number_format($total); ?></b></td>
        </tr>
    </table>

    <h2>Place Order</h2>
    <form method="POST" action="place_order.php">
        <label>Name:</label> 
        <input type="text" name="name" required><br><br>
        <label>Email:</label> 
        <input type="email" name="email" required><br><br>
        <button type="submit" name="place_order">Confirm Order</button>
    </form>
<?php endif; ?>

</body>
</html>