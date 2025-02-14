<head>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>

<body>
  <nav class="navbar navbar-expand-md navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="/CRUDsystem/view/home.php">
        <img src="/CRUDsystem/images/logo.png" alt="Logo" width="340" height="150">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav ms-auto">
          <a class="nav-link" href="/CRUDsystem/view/home.php" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Home">
            <i class="fa-solid fa-house" style="color: black;"></i>&nbsp;Home
          </a>
          <a class="nav-link" href="/CRUDsystem/view/view_cart.php" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Your Cart">
            <i class="fa-solid fa-cart-shopping" style="color: black;"></i>&nbsp;Cart
          </a>

          <!-- Account Dropdown -->
          <div class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fa-solid fa-user" style="color: black;"></i>&nbsp;Account
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
              <li><a class="dropdown-item" href="/CRUDsystem/view/account_settings.php"><i class="fa-solid fa-cog"></i> Account Settings</a></li>
              <li><a class="dropdown-item" href="/CRUDsystem/view/orders.php"><i class="fa-solid fa-bag-shopping"></i> Orders</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="/CRUDsystem/handler/logout_handler.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            </ul>
          </div>

        </div>
      </div>
    </div>
  </nav>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
