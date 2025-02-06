<?php
session_start();
include_once __DIR__ . '/../database/database.php';

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Fetch the order details, including payment method
    $query = "SELECT * FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
    } else {
        echo "Order not found.";
        exit;
    }
} else {
    echo "No order ID provided.";
    exit;
}
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
        <h2>Order Receipt</h2>
        <table class="table table-bordered">
            <tr>
                <th>Order ID</th>
                <td><?php echo $order['order_id']; ?></td>
            </tr>
            <tr>
                <th>Customer Name</th>
                <td><?php echo $order['customer_name']; ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo $order['address']; ?></td>
            </tr>
            <tr>
                <th>Order Date</th>
                <td><?php echo $order['order_date']; ?></td>
            </tr>
            <tr>
                <th>Total Amount</th>
                <td><?php echo $order['total_amount']; ?></td>
            </tr>
            <tr>
                <th>Payment Method</th>
                <td><?php echo $order['payment_method']; ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo $order['status']; ?></td>
            </tr>
        </table>

        <!-- Navigation buttons -->
        <div class="mt-3">
            <a href="/../CRUDsystem/index.php" class="btn btn-primary">Go to Homepage</a>
            <a href="/../CRUDsystem/view/orders.php" class="btn btn-secondary">View Orders</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 Sundown Sounds. All rights reserved.</p>
    </body>
</body>
</html>
