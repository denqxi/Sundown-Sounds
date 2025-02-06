<?php
session_start();
include_once __DIR__ . '/../database/database.php';

if (isset($_GET['cart_id'])) {
    $cart_id = $_GET['cart_id'];

    // Delete the item from the cart
    $query = "DELETE FROM cart WHERE cart_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();

    // Optional: Flash message
    $_SESSION['cart_message'] = "Item removed from cart successfully.";

    // Redirect back to cart page
    header("Location: /CRUDsystem/view/view_cart.php");
    exit();
} else {
    die("Invalid request.");
}
?>
