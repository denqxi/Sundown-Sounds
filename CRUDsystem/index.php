<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Sundown Sounds</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="col-12 col-sm-8 col-md-5">
            <div class="text-center mb-3">
                <div class="d-flex justify-content-center">
                    <img src="images/logo.png" alt="Sundown Sounds" class="img-fluid w-100" style="max-width: 250px;">
                </div>
                <p class="fw-bold text-muted mt-3">Sign in to access your account</p>
            </div>
            <div class="card p-4 shadow-lg" style="background: #F4A261; border-radius: 15px;">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?php 
                        echo $_SESSION['success']; 
                        unset($_SESSION['success']); 
                        ?>
                    </div>
                <?php endif; ?>
                <form action="/CRUDsystem/handler/login_handler.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label text-dark">Username</label>
                        <input type="text" class="form-control" name="username" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-dark">Password</label>
                        <input type="password" class="form-control" name="password" required />
                    </div>
                    <div class="mb-3 text-end">
                        <a href="register.php" class="text-dark">Don't have an account? Register here!</a>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn text-white" style="background-color: black;">
                            Login&nbsp;<i class="fa-solid fa-right-to-bracket"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
