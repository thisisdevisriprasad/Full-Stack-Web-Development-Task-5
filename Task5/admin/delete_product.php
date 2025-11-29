<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
include('../includes/connect.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get image name
    $res = $conn->query("SELECT image FROM products WHERE id=$id");
    $data = $res->fetch_assoc();

    if ($data && file_exists("../assets/upload/" . $data['image'])) {
        unlink("../assets/upload/" . $data['image']); // delete image file
    }

    $conn->query("DELETE FROM products WHERE id=$id");
    echo "<script>alert('Product deleted successfully'); window.location='manage_products.php';</script>";
}
?>