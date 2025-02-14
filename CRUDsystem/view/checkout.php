<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    echo "<script>alert('Please log in to proceed to checkout.'); window.location.href='/CRUDsystem/index.php';</script>";
    exit();
}

include_once __DIR__ . '/../database/database.php';
include_once __DIR__ . '/../includes/navbar.php'; // ✅ Include the navbar

$user_id = $_SESSION['id'];
$session_id = session_id(); // ✅ Get the session ID for cart tracking

// Fetch user details (Only customer_name since address is not stored in users table)
$query = "SELECT customer_name FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$customer_name = $user['customer_name'] ?? '';

if (!$customer_name) {
    die("User not found in the database. Check if you are logged in.");
}

// ✅ Fetch total items and total amount from the cart using `session_id`
$query = "SELECT COALESCE(SUM(quantity), 0) AS total_items, COALESCE(SUM(quantity * price), 0) AS total_amount 
          FROM cart WHERE session_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $session_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_data = $result->fetch_assoc();

$total_items = $cart_data['total_items'];
$total_amount = $cart_data['total_amount'];

// ✅ Prevent checkout if cart is empty
if ($total_items == 0) {
    echo "<script>alert('Your cart is empty. Add items before checkout.'); window.location.href='/CRUDsystem/cart.php';</script>";
    exit();
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
    <div class="container mt-5">
        <h2>Checkout</h2>
        <form method="POST" action="/CRUDsystem/handler/order_handler.php"> <!-- ✅ Submits to order_handler.php -->
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
            <input type="hidden" name="session_id" value="<?= htmlspecialchars($session_id) ?>"> <!-- ✅ Add session_id -->
            <input type="hidden" name="total_items" value="<?= htmlspecialchars($total_items) ?>">
            <input type="hidden" name="total_amount" value="<?= htmlspecialchars($total_amount) ?>">

            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($customer_name) ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Address <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="address" placeholder="Enter delivery address" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                <select class="form-control" name="payment_method" required>
                    <option value="" disabled selected>Select a payment method</option>
                    <option value="cash_on_delivery">Cash on Delivery</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="gcash">GCash</option>
                    <option value="paypal">PayPal</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Total Items</label>
                <input type="text" class="form-control" value="<?= $total_items ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Total Amount (₱)</label>
                <input type="text" class="form-control" value="<?= number_format($total_amount, 2) ?>" readonly>
            </div>
            <button type="submit" class="btn btn-success">Place Order</button>
        </form>
    </div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2025 Sundown Sounds. All rights reserved.</p>
</footer>
</body>
</html>
