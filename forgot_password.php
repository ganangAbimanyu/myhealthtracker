<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $newPassword = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    try {
        // Check if the email exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // Update the password for the user
            $stmt = $conn->prepare("UPDATE users SET password = :password WHERE email = :email");
            $stmt->bindParam(':password', $newPassword);
            $stmt->bindParam(':email', $email);

            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "Failed to reset password.";
            }
        } else {
            echo "Email not found in our records.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>