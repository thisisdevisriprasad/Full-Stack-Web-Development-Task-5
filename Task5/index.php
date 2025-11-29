<?php
session_start();
include('includes/connect.php');

// ✅ Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ✅ Add item to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? max(1, intval($_POST['quantity'])) : 1;

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    header("Location: index.php");
    exit;
}

// ✅ Fetch products
$query = "SELECT * FROM products";
$result = $conn->query($query);

// ✅ Cart count (sum of quantities)
$cart_count = !empty($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Food Ordering System</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; }
        .product-grid { display: flex; flex-wrap: wrap; gap: 20px; }
        .product { border: 1px solid #ccc; background: #fff; padding: 15px; width: 250px; border-radius: 10px; text-align: center; }
        .product img { width: 100%; height: 160px; object-fit: cover; border-radius: 10px; }
        .cart-link { background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; }
        .cart-link:hover { background: #218838; }
        button { background: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        h1 { color: #333; }
    </style>
</head>
<body>

<div class="header">
    <h1>Welcome to Our Food Ordering System</h1>
    <a class="cart-link" href="cart.php">🛒 View Cart (<?php echo $cart_count; ?>)</a>
</div>
<hr>

<div class="product-grid">
<?php if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="product">
            <img src="assets/upload/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
            <strong>₹<?php echo number_format($row['price'], 2); ?></strong><br><br>
            <form method="POST" action="">
                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                <input type="number" name="quantity" value="1" min="1" style="width:60px;">
                <button type="submit" name="add_to_cart">Add to Cart</button>
            </form>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No products found. Please add some from the admin panel.</p>
<?php endif; ?>
</div>

</body>
</html>