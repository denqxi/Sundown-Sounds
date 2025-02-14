<?php
session_start();
include '../database/database.php'; // Ensure this is correct

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = trim($_POST['customer_name']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION['errors'] = "Passwords do not match!";
        header("Location: ../register.php");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users (customer_name, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $customer_name, $username, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful! You can now log in.";
        header("Location: ../index.php");
    } else {
        $_SESSION['errors'] = "Something went wrong. Try again!";
        header("Location: ../register.php");
    }

    $stmt->close();
    $conn->close();
    exit();
} else {
    header("Location: ../register.php");
    exit();
}
