<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
include('../includes/connect.php');

if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // File upload handling
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];

    if (!empty($image)) {
        $upload_dir = "../assets/upload/";

        // Make sure folder exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Generate a unique filename to avoid overwriting
        $new_image_name = time() . "_" . basename($image);

        // Move file to upload folder
        if (move_uploaded_file($image_tmp, $upload_dir . $new_image_name)) {
            // Insert record into database with new image name
            $query = "INSERT INTO products (name, description, price, image) 
                      VALUES ('$name', '$description', '$price', '$new_image_name')";
            if ($conn->query($query)) {
                echo "<script>alert('✅ Product added successfully!'); window.location='manage_products.php';</script>";
            } else {
                echo "<script>alert('❌ Database insert failed!');</script>";
            }
        } else {
            echo "<script>alert('❌ Image upload failed!');</script>";
        }
    } else {
        echo "<script>alert('Please select an image.');</script>";
    }
}

// Fetch products to display below
$result = $conn->query("SELECT * FROM products");
?>


<!DOCTYPE html>
<html>
<head>
    <title>Manage Products - Admin</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #eee; }
        img { width: 80px; height: 80px; object-fit: cover; }
        form { margin-bottom: 30px; }
    </style>
</head>
<body>

<h1>Manage Food Menu</h1>
<a href="index.php">⬅ Back to Dashboard</a>
<hr>

<h2>Add New Product</h2>
<form method="POST" enctype="multipart/form-data">
    <label>Name:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" required></textarea><br><br>

    <label>Price:</label><br>
    <input type="number" step="0.01" name="price" required><br><br>

    <label>Image:</label><br>
    <input type="file" name="image" accept="image/*" required><br><br>

    <button type="submit" name="add_product">Add Product</button>
</form>

<h2>Existing Products</h2>
<table>
    <tr>
        <th>ID</th><th>Image</th><th>Name</th><th>Description</th><th>Price</th><th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><img src="../assets/upload/<?php echo $row['image']; ?>" alt=""></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td>₹<?php echo $row['price']; ?></td>
            <td><a href="delete_product.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this product?')">Delete</a></td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>