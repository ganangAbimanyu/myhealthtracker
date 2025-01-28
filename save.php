<?php
session_start(); // Start the session to access user ID

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if the user is not authenticated
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID
    $food_name = $_POST['food_name'];
    $calories = $_POST['calories'];
    $protein = $_POST['protein'];
    $carbs = $_POST['carbs'];
    $fat = $_POST['fat'];
    $image_url = $_POST['image_url']; // Get the image URL

    // Database credentials
    $servername = "localhost";
    $username = "root"; // Update with your DB username
    $password = ""; // Update with your DB password
    $dbname = "myhealthtracker";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert record with user_id
    $stmt = $conn->prepare("INSERT INTO nutrition_data (user_id, food_name, calories, protein, carbs, fat, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdddds", $user_id, $food_name, $calories, $protein, $carbs, $fat, $image_url);

    if ($stmt->execute()) {
        echo "Record saved successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
