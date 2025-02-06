<?php
session_start();
include_once __DIR__ . '/../database/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $payment_method = $_POST['payment_method'];
    $session_id = session_id();

    // Calculate total amount
    $query = "SELECT SUM(a.price * c.quantity) AS total_amount 
              FROM cart c 
              JOIN albums a ON c.album_id = a.album_id 
              WHERE c.session_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total_amount = $row['total_amount'];

    // Insert order
    $insertOrder = "INSERT INTO orders (customer_name, address, order_date, total_amount, payment_method, status) 
                    VALUES (?, ?, NOW(), ?, ?, 'Processing')";
    $stmt = $conn->prepare($insertOrder);
    $stmt->bind_param("ssds", $name, $address, $total_amount, $payment_method);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Move cart items to order_items and update album quantity
    $insertOrderItems = "INSERT INTO order_items (order_id, album_id, quantity, price) 
                         SELECT ?, c.album_id, c.quantity, a.price 
                         FROM cart c 
                         JOIN albums a ON c.album_id = a.album_id
                         WHERE c.session_id = ?";
    $stmt = $conn->prepare($insertOrderItems);
    $stmt->bind_param("is", $order_id, $session_id);
    $stmt->execute();

    // Update album quantities in the albums table
    $updateAlbumQuantity = "UPDATE albums a
                            JOIN cart c ON a.album_id = c.album_id
                            SET a.albmQty = a.albmQty - c.quantity
                            WHERE c.session_id = ?";
    $stmt = $conn->prepare($updateAlbumQuantity);
    $stmt->bind_param("s", $session_id);
    $stmt->execute();

    // Clear cart
    $deleteCart = "DELETE FROM cart WHERE session_id = ?";
    $stmt = $conn->prepare($deleteCart);
    $stmt->bind_param("s", $session_id);
    $stmt->execute();

    // Redirect to receipt.php
    echo "<script>window.location.href='/../CRUDsystem/view/receipt.php?order_id={$order_id}';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        footer {
            margin-top: auto;
        }
    </style>
</head>
<body>
    <?php 
    include_once __DIR__ . '/../includes/navbar.php'; 
    ?>
    <div class="container mt-5">
        <h2>Checkout</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea class="form-control" name="address" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Payment Method</label>
                <select class="form-select" name="payment_method" required>
                    <option value="Cash on Delivery">Cash on Delivery</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Gcash">Gcash</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Place Order</button>
        </form>
    </div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2025 Warm Vibes Music. All rights reserved.</p>
</footer>
</body>
</html>
