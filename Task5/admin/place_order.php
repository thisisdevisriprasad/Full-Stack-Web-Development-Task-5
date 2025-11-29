<?php
session_start();
include('includes/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['cart'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    $total = 0;
    $ids = array_map('intval', array_keys($_SESSION['cart']));
    $ids_list = implode(',', $ids);
    $res = $conn->query("SELECT id, price FROM products WHERE id IN ($ids_list)");
    $prices = [];
    while($r = $res->fetch_assoc()) { $prices[$r['id']] = $r['price']; }

    foreach ($_SESSION['cart'] as $pid => $qty) {
        $pid = intval($pid);
        $qty = intval($qty);
        if (isset($prices[$pid])) $total += $prices[$pid] * $qty;
    }

    $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_email, total_amount) VALUES (?, ?, ?)");
    $stmt->bind_param('ssd', $name, $email, $total);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($_SESSION['cart'] as $pid => $qty) {
        $pid = intval($pid); $qty = intval($qty);
        $price = isset($prices[$pid]) ? $prices[$pid] : 0;
        $stmt2->bind_param('iiid', $order_id, $pid, $qty, $price);
        $stmt2->execute();
    }

    unset($_SESSION['cart']);
    echo "<h2>Order placed. ID: $order_id</h2><a href='index.php'>Back to shop</a>";
    exit;
}
header("Location: cart.php");
exit;
?>