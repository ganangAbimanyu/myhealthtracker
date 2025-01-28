<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $image = $_POST['image'] ?? null; // Optional field

    try {
        $stmt = $conn->prepare("INSERT INTO health_records (user_id, type, description, date, time) 
                                VALUES (:user_id, :type, :description, :date, :time)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Failed to add the record.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
