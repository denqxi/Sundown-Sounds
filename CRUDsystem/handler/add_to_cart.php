<?php
session_start();
include_once __DIR__ . '/../database/database.php';

// Check if the necessary POST data is set
if (isset($_POST['album_id'], $_POST['album_name'], $_POST['price'], $_POST['quantity'], $_POST['image_url'])) {
    $album_id = $_POST['album_id'];
    $album_name = $_POST['album_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $image_url = $_POST['image_url'];
    $session_id = session_id(); // Get the current session ID

    // Query to check if the album is already in the cart
    $query = "SELECT * FROM cart WHERE album_id = ? AND session_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $album_id, $session_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the album is already in the cart, update the quantity
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $new_quantity = $row['quantity'] + $quantity; // Increase the quantity
        $update_query = "UPDATE cart SET quantity = ? WHERE cart_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ii", $new_quantity, $row['cart_id']);
        $update_stmt->execute();
    } else {
        // Insert the album into the cart
        $query = "INSERT INTO cart (album_id, album_name, price, quantity, session_id) 
        VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isdis", $album_id, $album_name, $price, $quantity, $session_id);
        $stmt->execute();
    }

    // Redirect to view_cart.php after adding to cart
    header('Location: /../CRUDsystem/view/view_cart.php');
    exit();
} else {
    // If required data is missing
    die("Missing data for adding item to cart.");
}
