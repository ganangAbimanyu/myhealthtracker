<?php
session_start();
require_once 'db.php'; // Include database connection

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Get the selected date or use today's date by default
$selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

try {
    // Query to fetch daily calories data for the logged-in user
    $query = "SELECT * FROM nutrition_data WHERE DATE(created_at) = :selected_date AND user_id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':selected_date', $selected_date, PDO::PARAM_STR);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $calories_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Query to fetch average calories per day for the logged-in user
    $avgQuery = "
        SELECT DATE(created_at) AS date, AVG(calories) AS avg_calories 
        FROM nutrition_data 
        WHERE user_id = :user_id 
        GROUP BY DATE(created_at)
        ORDER BY DATE(created_at)";
    $avgStmt = $conn->prepare($avgQuery);
    $avgStmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $avgStmt->execute();
    $avgCaloriesData = $avgStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error fetching data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Daily Calories - MyHealthTracker</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        table {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            border-radius: 20px;
        }
        h1 {
            color: #333;
        }
        #caloriesChart {
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Daily Calorie Tracker</h1>
        <form class="text-center mb-4" method="GET" action="view_calories.php">
            <label for="date">Select Date:</label>
            <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($selected_date); ?>">
            <button type="submit" class="btn btn-primary">View</button>
        </form>
        <?php if (count($calories_data) > 0): ?>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Food Name</th>
                        <th>Calories</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($calories_data as $data): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($data['food_name']); ?></td>
                            <td><?php echo htmlspecialchars($data['calories']); ?> kcal</td>
                            <td><?php echo htmlspecialchars($data['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center text-muted">No records found for the selected date.</p>
        <?php endif; ?>

        <!-- Chart Section -->
        <canvas id="caloriesChart"></canvas>

        <div class="text-center mt-4">
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script>
        // Prepare data for the chart
        const labels = <?php echo json_encode(array_column($avgCaloriesData, 'date')); ?>;
        const data = <?php echo json_encode(array_column($avgCaloriesData, 'avg_calories')); ?>;

        const ctx = document.getElementById('caloriesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Average Calories per Day',
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Calories (kcal)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
