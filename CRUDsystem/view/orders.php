<?php
session_start();
include_once __DIR__ . '/../database/database.php';

// Fetch all orders along with their items
$query = "SELECT o.order_id, o.customer_name, o.address, o.order_date, o.total_amount, o.payment_method, o.status, oi.album_id, oi.quantity, a.album_name 
          FROM orders o
          LEFT JOIN order_items oi ON o.order_id = oi.order_id
          LEFT JOIN albums a ON oi.album_id = a.album_id
          ORDER BY o.order_date ASC";

$stmt = $conn->prepare($query);
if ($stmt === false) {
    die('Error preparing query: ' . $conn->error);
}

$stmt->execute();
$result = $stmt->get_result();

// Handling order cancellation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_order'])) {
    // Get the order ID and reason for cancellation
    $order_id = $_POST['order_id'];
    $reason = $_POST['cancel_reason'];

    // Update the order status to 'Cancelled' and store the reason
    $updateQuery = "UPDATE orders SET status = 'Cancelled', cancellation_reason = ? WHERE order_id = ? AND status = 'Processing'";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("si", $reason, $order_id);
    $updateStmt->execute();

    // If the order was successfully cancelled, update album quantities
    if ($updateStmt->affected_rows > 0) {
        // Update the quantities of the albums back to what they were in the cart
        $updateAlbumQuantity = "UPDATE albums a
                                JOIN order_items oi ON a.album_id = oi.album_id
                                JOIN orders o ON oi.order_id = o.order_id
                                SET a.albmQty = a.albmQty + oi.quantity
                                WHERE o.order_id = ? AND o.status = 'Cancelled'";
        $stmt = $conn->prepare($updateAlbumQuantity);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        echo "<script>alert('Your order has been cancelled.'); window.location.href='/../CRUDsystem/view/orders.php';</script>";
    } else {
        echo "<script>alert('Error: Could not cancel the order.'); window.location.href='/../CRUDsystem/view/orders.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sundown Sounds - Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Ensure the footer stays at the bottom */
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            flex-grow: 1; /* Ensures content takes up available space */
        }

        /* Make the table scrollable on small screens */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Footer styling */
        footer {
            margin-top: auto;
            flex-shrink: 0; /* Ensures the footer does not shrink */
        }
    </style>
</head>
<body>
    <?php 
    include_once __DIR__ . '/../includes/navbar.php'; 
    ?>
    <div class="container mt-5">
        <h2>Orders</h2>
        <!-- Make table responsive for small/medium devices -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Address</th>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        $orders = []; // Array to store orders and their items
                        while ($row = $result->fetch_assoc()) {
                            $order_id = $row['order_id'];
                            if (!isset($orders[$order_id])) {
                                $orders[$order_id] = [
                                    'order_id' => $row['order_id'],
                                    'customer_name' => $row['customer_name'],
                                    'address' => $row['address'],
                                    'order_date' => $row['order_date'],
                                    'total_amount' => $row['total_amount'],
                                    'payment_method' => $row['payment_method'],
                                    'status' => $row['status'],
                                    'items' => [] // Initialize an empty array for items
                                ];
                            }
                            // Add item details to the current order
                            if ($row['album_name']) {
                                $orders[$order_id]['items'][] = [
                                    'album_name' => $row['album_name'],
                                    'quantity' => $row['quantity']
                                ];
                            }
                        }

                        // Loop through orders and display them
                        foreach ($orders as $order) {
                    ?>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo $order['customer_name']; ?></td>
                        <td><?php echo $order['address']; ?></td>
                        <td><?php echo $order['order_date']; ?></td>
                        <td><?php echo number_format($order['total_amount'], 2); ?></td>
                        <td><?php echo $order['payment_method']; ?></td>
                        <td><?php echo $order['status']; ?></td>
                        <td>
                            <?php if ($order['status'] == 'Processing'): ?>
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal<?php echo $order['order_id']; ?>">Cancel</button>

                                <!-- Cancel Reason Modal -->
                                <div class="modal fade" id="cancelModal<?php echo $order['order_id']; ?>" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="cancelModalLabel">Cancel Order</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="orders.php">
                                                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                                    <div class="mb-3">
                                                        <label for="cancel_reason" class="form-label">Reason for Cancellation</label>
                                                        <textarea class="form-control" name="cancel_reason" required></textarea>
                                                    </div>
                                                    <button type="submit" name="cancel_order" class="btn btn-danger">Confirm Cancellation</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <span></span>
                            <?php endif; ?>

                            <!-- Button to View Ordered Items -->
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#itemsModal<?php echo $order['order_id']; ?>">View Items</button>

                            <!-- Items Modal -->
                            <div class="modal fade" id="itemsModal<?php echo $order['order_id']; ?>" tabindex="-1" aria-labelledby="itemsModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="itemsModalLabel">Ordered Items</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <ul>
                                                <?php foreach ($order['items'] as $item): ?>
                                                    <li><?php echo $item['album_name']; ?> (x<?php echo $item['quantity']; ?>)</li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='8'>No orders found.</td></tr>";
                    }
                    ?>
                </tbody>

            </table>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 Sundown Sounds. All rights reserved.</p>
    </body>
</body>
</html>
