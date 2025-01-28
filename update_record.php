<?php
session_start();
require_once 'db.php'; // Include your database connection file

// Ensure the user is authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Validate that the `id` parameter exists
if (!isset($_GET['id'])) {
    die("Record ID is missing.");
}

$id = $_GET['id']; // Record ID to be updated
$user_id = $_SESSION['user_id']; // Logged-in user's ID

// Fetch the record to pre-fill the form
try {
    $query = "SELECT * FROM health_records WHERE id = :id AND user_id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->execute([':id' => $id, ':user_id' => $user_id]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$record) {
        die("Record not found or you do not have permission to access it.");
    }
} catch (PDOException $e) {
    die("Error fetching record: " . $e->getMessage());
}

// Handle form submission for updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    try {
        $updateQuery = "UPDATE health_records SET type = :type, description = :description, date = :date, time = :time WHERE id = :id AND user_id = :user_id";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->execute([
            ':type' => $type,
            ':description' => $description,
            ':date' => $date,
            ':time' => $time,
            
            ':id' => $id,
            ':user_id' => $user_id
        ]);

        header("Location: view_records.php?message=Record updated successfully");
        exit();
    } catch (PDOException $e) {
        die("Error updating record: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Record - MyHealthTracker</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Update Health Record</h1>
        <form method="POST" action="">
        <div class="form-group">
            <label for="type">Type</label>
            <select id="type" name="type" class="form-control" required>
                <option value="Appointment" <?php echo ($record['type'] === 'Appointment') ? 'selected' : ''; ?>>Appointment</option>
                <option value="Medication" <?php echo ($record['type'] === 'Medication') ? 'selected' : ''; ?>>Medication</option>
                <option value="Exercise" <?php echo ($record['type'] === 'Exercise') ? 'selected' : ''; ?>>Exercise</option>
            </select>
        </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" required><?php echo htmlspecialchars($record['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" class="form-control" value="<?php echo htmlspecialchars($record['date']); ?>" required>
            </div>
            <div class="form-group">
                <label for="time">Time</label>
                <input type="time" id="time" name="time" class="form-control" value="<?php echo htmlspecialchars($record['time']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Record</button>
            <a href="view_records.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
