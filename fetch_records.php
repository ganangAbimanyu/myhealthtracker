<?php
include 'db.php';

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $query = isset($_GET['query']) ? $_GET['query'] : ""; // Optional search query

    try {
        // Base query
        $sql = "SELECT * FROM health_records WHERE user_id = :user_id";

        // Add search conditions if a query is provided
        if (!empty($query)) {
            $sql .= " AND (type LIKE :query OR description LIKE :query OR date LIKE :query)";
        }

        $sql .= " ORDER BY date, time";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);

        if (!empty($query)) {
            $searchTerm = "%$query%";
            $stmt->bindParam(':query', $searchTerm);
        }

        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($records);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Error fetching records: " . $e->getMessage()]);
    }
}
?>