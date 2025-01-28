<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MyHealthTracker - Register</title>
        <!-- Bootstrap CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <!-- SweetAlert2 CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <style>
            /* Custom Yellow Button */
            .btn-yellow {
                background-color: #f1c232; /* Yellow background */
                color: #000; /* Black text */
                border: none;
            }

            .btn-yellow:hover {
                background-color: #d4a917; /* Darker yellow on hover */
                color: #000; /* Retain black text */
            }
        </style>
    </head>
    <body>
        <div class="container mt-5">
            <div class="card mx-auto" style="max-width: 500px;">
                <div class="card-body">
                    <form id="register-form" method="POST" action="">
                        <h2 class="text-center mb-4">Register</h2>
                        <div class="form-group">
                            <label for="register-email">Email</label>
                            <input type="email" class="form-control" id="register-email" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="form-group">
                            <label for="register-password">Password</label>
                            <input type="password" class="form-control" id="register-password" name="password" placeholder="Enter your password" minlength="6" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm-password">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm-password" placeholder="Confirm your password" required>
                        </div>
                        <button type="submit" class="btn btn-yellow btn-block">Register</button>
                    </form>
                    <div class="text-center mt-3">
                        <p>Already have an account? <a href="login.php">Login here</a></p>
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
            document.getElementById("register-form").addEventListener("submit", function (e) {
                e.preventDefault();

                const email = document.getElementById("register-email").value;
                const password = document.getElementById("register-password").value;
                const confirmPassword = document.getElementById("confirm-password").value;

                // Validate password match
                if (password !== confirmPassword) {
                    Swal.fire({
                        icon: "error",
                        title: "Password Mismatch",
                        text: "Passwords do not match. Please try again.",
                    });
                    return;
                }

                // Submit the form
                this.submit();
            });
        </script>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            include 'db.php';

            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            try {
                // Check if email already exists
                $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $count = $stmt->fetchColumn();

                if ($count > 0) {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Registration Failed',
                            text: 'Email already exists!'
                        });
                    </script>";
                } else {
                    // Insert new user into the database
                    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $password);

                    if ($stmt->execute()) {
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Registration Successful',
                                text: 'You can now log in to your account.',
                                confirmButtonText: 'Go to Login'
                            }).then(() => {
                                window.location.href = 'login.php';
                            });
                        </script>";
                    } else {
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Registration Failed',
                                text: 'Error registering user.'
                            });
                        </script>";
                    }
                }
            } catch (PDOException $e) {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: '" . $e->getMessage() . "'
                    });
                </script>";
            }
        }
        ?>
    </body>
</html>
