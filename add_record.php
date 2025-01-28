<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Record - MyHealthTracker</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* Custom Button Styles */
        .btn-blue {
            background-color: #0056a5; /* Blue background */
            color: #fff; /* White text */
            border: none;
        }

        .btn-blue:hover {
            background-color: #004080; /* Darker blue on hover */
        }

        .btn-yellow {
            background-color: #f1c232; /* Yellow background */
            color: #000; /* Black text */
            border: none;
        }

        .btn-yellow:hover {
            background-color: #d4a917; /* Darker yellow on hover */
        }
    </style>
</head>
<body>
    <?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php"); // Redirect to login if not authenticated
        exit();
    }
    $userId = $_SESSION['user_id']; // Get logged-in user's ID
    ?>
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-body">
                <h2 class="text-center mb-4">Add New Activity</h2>
                <form id="add-record-form">
                    <input type="hidden" id="user_id" value="<?php echo $userId; ?>" name="user_id">
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select id="type" name="type" class="form-control" required>
                            <option value="Appointment">Appointment</option>
                            <option value="Medication">Medication</option>
                            <option value="Exercise">Exercise</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" placeholder="Enter details..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" id="date" name="date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="time">Time</label>
                        <input type="time" id="time" name="time" class="form-control" required>
                    </div>
                  
                    <!-- Blue Add Record Button -->
                    <button type="submit" class="btn btn-blue btn-block">Add Activity</button>
                </form>
                <div class="text-center mt-3">
                    <!-- Yellow Back to Dashboard Button -->
                    <a href="dashboard.php" class="btn btn-yellow btn-block">Back to Dashboard</a>
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
        document.getElementById("add-record-form").addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = {
                user_id: document.getElementById("user_id").value,
                type: document.getElementById("type").value,
                description: document.getElementById("description").value,
                date: document.getElementById("date").value,
                time: document.getElementById("time").value,
            };

            // Send AJAX request to create_record.php
            $.post("create_record.php", formData)
                .done(function (response) {
                    if (response.trim() === "success") {
                        Swal.fire({
                            icon: "success",
                            title: "Record Added Successfully",
                            text: "Your record has been saved.",
                            confirmButtonText: "OK"
                        }).then(() => {
                            window.location.href = "dashboard.php";
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: response
                        });
                    }
                })
                .fail(function () {
                    Swal.fire({
                        icon: "error",
                        title: "Server Error",
                        text: "An error occurred. Please try again later."
                    });
                });
        });
    </script>
</body>
</html>
