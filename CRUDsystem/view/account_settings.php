<?php
session_start();
include_once __DIR__ . '/../database/database.php';

// Ensure user is logged in
if (!isset($_SESSION['id'])) {
    die("Unauthorized access. Please log in.");
}

$user_id = $_SESSION['id'];
$success_message = "";
$error_message = "";

// Fetch user data
$query = "SELECT username, password, last_username_change FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_username'])) {
        $new_username = trim($_POST['new_username']);
        $current_time = date('Y-m-d H:i:s');
        $last_change = $user['last_username_change'];

        // Check if username was changed within 14 days
        if ($last_change && strtotime($last_change) > strtotime('-14 days')) {
            $error_message = "You can only change your username once every 14 days.";
        } else {
            // Update username and last change date
            $update_query = "UPDATE users SET username = ?, last_username_change = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ssi", $new_username, $current_time, $user_id);
            if ($stmt->execute()) {
                $_SESSION['username'] = $new_username; // Update session
                $success_message = "Username updated successfully!";
            } else {
                $error_message = "Error updating username.";
            }
        }
    }

    if (isset($_POST['update_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

        // Verify current password
        if (!password_verify($current_password, $user['password'])) {
            $error_message = "Current password is incorrect.";
        } else {
            // Update password
            $update_query = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("si", $new_password, $user_id);
            if ($stmt->execute()) {
                $success_message = "Password updated successfully!";
            } else {
                $error_message = "Error updating password.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include_once __DIR__ . '/../includes/navbar.php'; ?>
    
    <div class="container mt-5">
        <h2>Account Settings</h2>
        
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php elseif ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <!-- Update Username -->
                <form method="POST">
                    <h4>Change Username</h4>
                    <div class="mb-3">
                        <label for="new_username" class="form-label">New Username</label>
                        <input type="text" class="form-control" name="new_username" required>
                    </div>
                    <button type="submit" name="update_username" class="btn btn-primary">Update Username</button>
                </form>
            </div>

            <div class="col-md-6">
                <!-- Update Password -->
                <form method="POST">
                    <h4>Change Password</h4>
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" name="new_password" required>
                    </div>
                    <button type="submit" name="update_password" class="btn btn-primary">Update Password</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2025 Sundown Sounds. All rights reserved.</p>
</footer>
</body>
</html>
