<?php
session_start();

// Redirect to login page if the user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MyHealthTracker</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Top Navigation Bar -->
    <div class="top-bar">
    <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- Dashboard Content -->
    <div class="container dashboard-container">
        <h1 class="dashboard-header">Welcome to MyHealthTracker</h1>
        
        <!-- Cards Section -->
        <div class="row justify-content-center">
            <!-- Check Nutrition -->
            <div class="col-md-12 mb-4">
                <a href="nutritionix.html" class="card dashboard-card blue-card">
                    <img src="assets/3.png" alt="Check Nutrition" class="card-image">
                    <h5 class="card-title">Check Nutrition</h5>
                </a>
            </div>

            <!-- Add Record -->
            <div class="col-md-6 mb-4">
                <a href="add_record.php" class="card dashboard-card yellow-card">
                    <img src="assets/4.png" alt="Add Record" class="card-image">
                    <h5 class="card-title">Add Activity</h5>
                </a>
            </div>

            <!-- View Records -->
            <div class="col-md-6 mb-4">
                <a href="view_records.php" class="card dashboard-card yellow-card">
                    <img src="assets/5.png" alt="View Record" class="card-image">
                    <h5 class="card-title">View Activity</h5>
                </a>
            </div>

            <!-- GPS Section -->
            <div class="col-md-6 mb-4">
                <a href="gps.html" class="card dashboard-card yellow-card">
                    <img src="assets/gps.png" alt="GPS Location" class="card-image">
                    <h5 class="card-title">View GPS Location</h5>
                </a>
            </div>

            <!-- View Calories Daily -->
            <div class="col-md-12 mb-4">
                <a href="view_calories.php" class="card dashboard-card blue-card">
                    <img src="assets/calories.png" alt="View Calories Daily" class="card-image">
                    <h5 class="card-title">View Calories Daily</h5>
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
