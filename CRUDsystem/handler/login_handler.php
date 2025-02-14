<?php
session_start();
include '../database/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("❌ Not a POST request!");
}

$username = trim($_POST['username']);
$password = trim($_POST['password']);

if (empty($username) || empty($password)) {
    die("❌ Username and password are required!");
}

// Verify user
$stmt = $conn->prepare("SELECT id, username, password FROM users WHERE LOWER(username) = LOWER(?)");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
        // ✅ Corrected session storage
        $_SESSION['id'] = $row['id']; // Store correct user ID
        $_SESSION['username'] = $row['username'];
        unset($_SESSION['errors']); // Remove error messages if login is successful

        header("Location: ../view/home.php");
        exit();
    } else {
        $_SESSION['errors'] = "❌ Password incorrect!";
    }
} else {
    $_SESSION['errors'] = "❌ No user found!";
}

// Redirect back to login if failed
header("Location: ../index.php");
exit();
?>
