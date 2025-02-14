<?php
session_start();
include_once __DIR__ . '/../database/database.php';

// Check if the album ID is provided
if (!isset($_GET['id'])) {
    die("Album ID not provided.");
}

$album_id = $_GET['id'];

// Query to fetch album details along with artist name, genre, and release date
$query = "SELECT albums.*, artists.artist_name, genres.genre_name, albums.release_date 
          FROM albums 
          INNER JOIN artists ON albums.artist_id = artists.artist_id
          INNER JOIN genres ON albums.genre_id = genres.genre_id 
          WHERE albums.album_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $album_id);  // Binding the album ID parameter
$stmt->execute();
$result = $stmt->get_result();

// Fetch album data
$album = $result->fetch_assoc();

if (!$album) {
    die("Album not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($album['album_name']); ?> - Sundown Sounds</title>
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

        .album-cover {
            width: 500px;
            height: 500px;
            object-fit: cover;
            border-radius: 10px;
        }

        .back-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .back-icon {
            width: 20px;
            height: 20px;
        }

        .back-button:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
<?php include_once __DIR__ . '/../includes/navbar.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <img src="<?= htmlspecialchars($album['image_url']); ?>" class="img-fluid rounded album-cover" alt="Album Cover">
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="fw-bold"><?= htmlspecialchars($album['album_name']); ?></h2>
                <a href="/../CRUDsystem/view/albums.php" class="btn btn-outline-secondary back-button">
                    <img src="../images/icons/back.png" alt="Back" class="back-icon">
                </a>
            </div>
            <p class="text-muted">by <?= htmlspecialchars($album['artist_name']); ?></p>
            <p><strong>Release Date:</strong> <?= date("F d, Y", strtotime($album['release_date'])); ?></p>
            <p><strong>Genre:</strong> <?= htmlspecialchars($album['genre_name']); ?></p>
            <p><strong>Available Stock:</strong> <?= $album['albmQty']; ?> units</p>
            <p><strong>Price:</strong> ₱<?= number_format($album['price'], 2); ?></p>

            <!-- Add to Cart Form -->
            <form action="/../CRUDsystem/handler/add_to_cart.php" method="POST">
                <input type="hidden" name="album_id" value="<?= $album['album_id']; ?>">
                <input type="hidden" name="album_name" value="<?= htmlspecialchars($album['album_name']); ?>">
                <input type="hidden" name="price" value="<?= $album['price']; ?>">
                <input type="hidden" name="image_url" value="<?= htmlspecialchars($album['image_url']); ?>">
                <label for="quantity" class="form-label">Quantity:</label>
                <input type="number" name="quantity" id="quantity" class="form-control" min="1" max="<?= $album['albmQty']; ?>" required>
                <button type="submit" class="btn btn-dark mt-3">Add to Cart</button>
            </form>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2025 Sundown Sounds. All rights reserved.</p>
</footer>
</body>
</html>
