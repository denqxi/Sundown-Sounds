<?php
session_start();
include_once __DIR__ . '/../database/database.php';

// Get the current session ID
$session_id = session_id();

// Query to fetch cart items for the current session
$query = "SELECT * FROM cart WHERE session_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $session_id);
$stmt->execute();
$result = $stmt->get_result();

// Initialize total variable
$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sundown Sounds - Cart</title>
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
        table {
            max-width: 100;
            overflow-x: hidden;
        }
    </style>
</head>
<body>
<?php 
include_once __DIR__ . '/../includes/navbar.php'; ?>

<div class="container mt-5">
    <h2>Your Cart</h2>
    
    <?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Album</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php 
                    $subtotal = $row['price'] * $row['quantity'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['album_name']); ?></td>
                    <td>
                        <!-- Update quantity form -->
                        <form action="update_cart.php" method="POST">
                            <input type="hidden" name="cart_id" value="<?= $row['cart_id']; ?>">
                            <input type="number" name="quantity" value="<?= $row['quantity']; ?>" min="1" class="form-control" required>
                            <button type="submit" class="btn btn-warning btn-sm mt-2">Update</button>
                        </form>
                    </td>
                    <td>₱<?= number_format($row['price'], 2); ?></td>
                    <td>₱<?= number_format($subtotal, 2); ?></td>
                    <td>
                        <a href="/CRUDsystem/handler/remove_cart_item.php?cart_id=<?= $row['cart_id']; ?>" class="btn btn-danger btn-sm">Remove</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    </div>

    <div class="d-flex justify-content-between">
        <h4>Total: ₱<?= number_format($total, 2); ?></h4>
        <a href="/../CRUDsystem/view/checkout.php" class="btn btn-primary">Proceed to Checkout</a>
    </div>

    <!-- Go Back Browsing Button -->
    <div class="mt-3">
        <a href="/../CRUDsystem/view/albums.php" class="btn btn-secondary">Go Back Browsing</a>
    </div>
    
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2025 Sundown Sounds. All rights reserved.</p>
</body>
</body>
</html>
