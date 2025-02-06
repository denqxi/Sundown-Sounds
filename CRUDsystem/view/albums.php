<?php
session_start();
include_once __DIR__ . '/../database/database.php';

// Fetch all genres for the filter dropdown
$genreQuery = "SELECT * FROM genres";
$genreStmt = $conn->prepare($genreQuery);
$genreStmt->execute();
$genreResult = $genreStmt->get_result();

// Base query to fetch albums with multiple genres
$query = "SELECT albums.*, artists.artist_name, 
                 COALESCE(GROUP_CONCAT(DISTINCT genres.genre_name SEPARATOR ', '), 'No Genre') AS genre_names
          FROM albums 
          INNER JOIN artists ON albums.artist_id = artists.artist_id 
          LEFT JOIN album_genres ON albums.album_id = album_genres.album_id 
          LEFT JOIN genres ON album_genres.genre_id = genres.genre_id";

// Apply filters if search or genre is selected
$conditions = [];
$params = [];
$types = "";

if (!empty($_GET['search'])) {
    $conditions[] = "albums.album_name LIKE ?";
    $params[] = "%" . $_GET['search'] . "%";
    $types .= "s";
}

if (!empty($_GET['genre'])) {
    $conditions[] = "albums.album_id IN (SELECT album_id FROM album_genres WHERE genre_id = ?)";
    $params[] = $_GET['genre'];
    $types .= "i";
}

// Append conditions dynamically
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$query .= " GROUP BY albums.album_id";

$stmt = $conn->prepare($query);

// Bind parameters dynamically if conditions exist
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sundown Sounds - All Album</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php
include_once __DIR__ . '/../includes/navbar.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>

<div class="container mt-5">
    <h2 class="fw-bold mb-4">All Albums</h2>

    <!-- Search and Filter Form -->
    <form method="GET" class="mb-4 d-flex gap-2">
        <input type="text" name="search" class="form-control" placeholder="Search albums..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        <select name="genre" class="form-select">
            <option value="">All Genres</option>
            <?php while ($genre = $genreResult->fetch_assoc()) { ?>
                <option value="<?= $genre['genre_id']; ?>" <?= isset($_GET['genre']) && $_GET['genre'] == $genre['genre_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($genre['genre_name']); ?>
                </option>
            <?php } ?>
        </select>
        <button type="submit" class="btn btn-dark">Filter</button>
    </form>

    <div class="row">
        <?php if ($result->num_rows > 0) { 
            while ($album = $result->fetch_assoc()) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?= htmlspecialchars($album['image_url']); ?>" class="card-img-top" alt="Album Cover">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($album['album_name']); ?></h5>
                            <p class="card-text">by <?= htmlspecialchars($album['artist_name']); ?></p>
                            <p><strong>Genres:</strong> <?= htmlspecialchars($album['genre_names']); ?></p>
                            <p><strong>Available Stock:</strong> <?= $album['albmQty']; ?> units</p>
                            <a href="/../CRUDsystem/view/albumdetails.php?id=<?= $album['album_id']; ?>" class="btn btn-dark">View Details</a>
                        </div>
                    </div>
                </div>
            <?php } 
        } else { ?>
            <p class="text-center">No albums found.</p>
        <?php } ?>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
