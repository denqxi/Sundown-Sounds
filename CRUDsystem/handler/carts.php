<?php
session_start();

// Check if the cart is set, if not, initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if the form is submitted to add an item to the cart
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get album details from the form
    $album_id = $_POST['album_id'];
    $album_name = $_POST['album_name'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];
    $quantity = $_POST['quantity'];

    // Check if the album already exists in the cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['album_id'] == $album_id) {
            // If found, update the quantity
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }

    // If not found, add the album as a new item
    if (!$found) {
        $_SESSION['cart'][] = [
            'album_id' => $album_id,
            'album_name' => $album_name,
            'price' => $price,
            'image_url' => $image_url,
            'quantity' => $quantity
        ];
    }

    // Redirect back to the album page or cart page after adding the item
    header('Location: view_cart.php');
    exit();
}
?>
