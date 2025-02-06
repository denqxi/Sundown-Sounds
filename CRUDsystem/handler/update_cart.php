<?php
session_start();
include_once __DIR__ . '/../database/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity'];

    // Ensure quantity is a valid number
    if (is_numeric($quantity) && $quantity > 0) {
        $query = "UPDATE cart SET quantity = ? WHERE cart_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $quantity, $cart_id);
        if ($stmt->execute()) {
            $_SESSION['cart_message'] = "Cart updated successfully!";
        } else {
            $_SESSION['cart_message'] = "Error updating cart.";
        }
    }

    // Redirect back to the cart page
    header('Location: /../CRUDsystem/view/view_cart.php');
    exit();
}
