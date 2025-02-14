<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

include_once __DIR__ . '/../database/database.php';

$user_id = $_POST['user_id'];
$session_id = $_POST['session_id']; // ✅ Get session_id
$address = trim($_POST['address']);
$payment_method = trim($_POST['payment_method']);
$total_amount = $_POST['total_amount'] ?? 0;

if (empty($address) || empty($payment_method)) {
    $_SESSION['errors'] = "❌ Address and payment method are required!";
    header("Location: ../view/checkout.php");
    exit();
}

// ✅ Insert into `orders` table with correct column count
$query = "INSERT INTO orders (user_id, address, order_date, total_amount, status, payment_method) 
          VALUES (?, ?, NOW(), ?, 'Pending', ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("isss", $user_id, $address, $total_amount, $payment_method);

if ($stmt->execute()) {
    $order_id = $stmt->insert_id;

    // ✅ Insert each cart item into `order_items` table using `session_id`
    $query = "INSERT INTO order_items (order_id, album_id, quantity, price) 
              SELECT ?, album_id, quantity, price FROM cart WHERE session_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $order_id, $session_id);
    $stmt->execute();

    // ✅ Clear the user's cart after order placement
    $query = "DELETE FROM cart WHERE session_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $session_id);
    $stmt->execute();

    // ✅ Redirect to order receipt
    header("Location: ../view/receipt.php?order_id=$order_id");
} else {
    $_SESSION['errors'] = "❌ Failed to place order. Please try again.";
    header("Location: ../view/checkout.php");
}
exit();
?>
