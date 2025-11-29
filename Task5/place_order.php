<?php
session_start();
include('includes/connect.php');

if (isset($_POST['place_order']) && !empty($_SESSION['cart'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $total = 0;

    foreach ($_SESSION['cart'] as $id => $qty) {
        $res = $conn->query("SELECT price FROM products WHERE id=$id");
        $row = $res->fetch_assoc();
        $total += $row['price'] * $qty;
    }

    $conn->query("INSERT INTO orders (customer_name, customer_email, total_amount) VALUES ('$name', '$email', '$total')");
    $order_id = $conn->insert_id;

    foreach ($_SESSION['cart'] as $id => $qty) {
        $res = $conn->query("SELECT price FROM products WHERE id=$id");
        $row = $res->fetch_assoc();
        $price = $row['price'];
        $conn->query("INSERT INTO order_items (order_id, menu_item_id, quantity, price) VALUES ($order_id, $id, $qty, $price)");
    }

    unset($_SESSION['cart']);
    echo "<h2>✅ Order placed successfully! Your Order ID is <b>$order_id</b></h2>";
    echo "<a href='index.php'>Back to Home</a>";
} else {
    header("Location: index.php");
    exit;
}
?>