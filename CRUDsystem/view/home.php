<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sundown Sounds</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CRUDsystem/css/style.css">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<!-- Hero Section -->
<header class="hero-section">
    <h1 class="fw-bold">🎶 Welcome to Sundown Sounds</h1>
    <p>Your go-to place for the best music albums</p>
    <a href="../view/albums.php" class="btn btn-dark btn-lg mt-3">Browse Albums</a>
</header>

<!-- Featured Albums -->
<section class="container mt-5">
    <h2 class="text-center mb-4">🔥 Featured Albums</h2>
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"><img src="../images/icons/indicator.png" class="d-block w-100" alt="indicator"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"><img src="../images/icons/indicator.png" class="d-block w-100" alt="indicator"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"><img src="../images/icons/indicator.png" class="d-block w-100" alt="indicator"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
            <img src="../images/featured albums/album1.png" class="d-block w-100" alt="Chromakopia">
            </div>
            <div class="carousel-item">
            <img src="../images/featured albums/album2.png" class="d-block w-100" alt="GNX">
            </div>
            <div class="carousel-item">
            <img src="../images/featured albums/album3.png" class="d-block w-100" alt="ABNH">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"><img src="../images/icons/prev.png" class="d-block w-100" alt="prev"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"><img src="../images/icons/next.png" class="d-block w-100" alt="prev"></span>
            <span class="visually-hidden">Next</span>
        </button>
        </div>
</section>

<!-- Footer -->
<?php 
$footerPath = "../includes/footer.php"; 
if (file_exists($footerPath)) {
    include $footerPath; 
} else {
    echo "<p style='color: red; text-align: center;'>Footer file missing.</p>";
}
?>
</body>
</html>
