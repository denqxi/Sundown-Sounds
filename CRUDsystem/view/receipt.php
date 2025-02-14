<?php
session_start();
include_once __DIR__ . '/../database/database.php';

if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    echo "<script>alert('No order ID provided.'); window.location.href='/CRUDsystem/view/orders.php';</script>";
    exit;
}

$order_id = $_GET['order_id'];

// Fetch order details, including payment method
$query = "SELECT o.*, u.customer_name FROM orders o JOIN users u ON o.user_id = u.id WHERE o.order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Order not found.'); window.location.href='/CRUDsystem/view/orders.php';</script>";
    exit;
}

$order = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sundown Sounds - Order Receipt</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .container {
            flex: 1;
        }
        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 10px 0;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <?php include_once __DIR__ . '/../includes/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center">Order Receipt</h2>
        <table class="table table-bordered">
            <tr>
                <th>Order ID</th>
                <td><?= htmlspecialchars($order['order_id']); ?></td>
            </tr>
            <tr>
                <th>Customer Name</th>
                <td><?= htmlspecialchars($order['customer_name']); ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?= htmlspecialchars($order['address']); ?></td>
            </tr>
            <tr>
                <th>Order Date</th>
                <td><?= date("F j, Y, g:i A", strtotime($order['order_date'])); ?></td>
            </tr>
            <tr>
                <th>Payment Method</th>
                <td><?= htmlspecialchars(ucwords(str_replace("_", " ", $order['payment_method']))); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><strong><?= htmlspecialchars(ucwords($order['status'])); ?></strong></td>
            </tr>
        </table>

        <!-- Navigation buttons -->
        <div class="text-center mt-4">
            <a href="/CRUDsystem/index.php" class="btn btn-primary">Go to Homepage</a>
            <a href="/CRUDsystem/view/orders.php" class="btn btn-secondary">View Orders</a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Sundown Sounds. All rights reserved.</p>
    </footer>
</body>
</html>
