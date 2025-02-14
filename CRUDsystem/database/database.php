<?php
$conn = new mysqli("localhost", "root", "", "albums_database");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
} 
?>
