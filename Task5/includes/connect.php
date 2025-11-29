<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "foodorder_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// (No echo here — keep it silent to avoid interfering with other pages)
?>