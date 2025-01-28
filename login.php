<?php
include 'db.php';
session_start();

// Handle POST request for login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Check if the email exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Start a session and save user ID
            $_SESSION['user_id'] = $user['id'];
            echo "success"; // Response for AJAX
        } else {
            echo "Invalid email or password.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MyHealthTracker - Login</title>
        <!-- Bootstrap CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <!-- SweetAlert2 CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="css/login.css">
    </head>
    <body>
        <div class="container mt-5">
            <div class="card mx-auto" style="max-width: 500px;">
                <div class="card-body">
                    <form id="login-form">
                        <h2 class="text-center mb-4">Login</h2>
                        <div class="form-group">
                            <label for="login-email">Email</label>
                            <input type="email" class="form-control" id="login-email" placeholder="Enter your email" required>
                        </div>
                        <div class="form-group">
                            <label for="login-password">Password</label>
                            <input type="password" class="form-control" id="login-password" placeholder="Enter your password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </form>
                    <div class="text-center mt-3">
                        <p>Don't have an account? <a href="register.php">Register here</a></p>
                        <p><a href="forgot_password.html">Forgot Password?</a></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery and Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
        <!-- SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
        <script>
            // Handle Login Form Submission
            document.getElementById("login-form").addEventListener("submit", function (e) {
                e.preventDefault();

                const email = document.getElementById("login-email").value;
                const password = document.getElementById("login-password").value;

                // Send AJAX request to login.php
                $.post("login.php", { email: email, password: password })
                    .done(function (response) {
                        if (response.trim() === "success") {
                            Swal.fire({
                                icon: "success",
                                title: "Login Successful",
                                text: "Redirecting to your dashboard...",
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = "dashboard.php";
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Login Failed",
                                text: response
                            });
                        }
                    })
                    .fail(function () {
                        Swal.fire({
                            icon: "error",
                            title: "Server Error",
                            text: "An unexpected error occurred. Please try again later."
                        });
                    });
            });
        </script>
    </body>
</html>
