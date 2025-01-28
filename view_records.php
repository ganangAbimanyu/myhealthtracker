<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Activity - MyHealthTracker</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .record-container {
            margin-top: 50px;
            max-width: 700px;
        }
        .record-item {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .btn-yellow {
            background-color: #f1c232; /* Yellow background */
            color: #000; /* Black text */
            border: none;
        }
        .btn-yellow:hover {
            background-color: #d4a917; /* Darker yellow */
        }
        .btn-red {
            background-color: #dc3545;
            color: #fff;
        }
        .btn-red:hover {
            background-color: #b52d3a;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    $userId = $_SESSION['user_id'];
    ?>
    <div class="container">
        <div class="record-container mx-auto">
            <h1 class="text-center mb-4">Activity Records</h1>
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search records by type, description, or date">
            </div>
            <div id="records">
                <p class="text-center">Loading records...</p>
            </div>
            <div class="text-center mt-4">
                <a href="dashboard.php" class="btn btn-yellow">Back to Dashboard</a>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById('searchInput');
            const recordsDiv = document.getElementById('records');

            // Fetch and display records
            function fetchRecords(query = "") {
                fetch(`fetch_records.php?user_id=<?php echo $userId; ?>&query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        recordsDiv.innerHTML = ""; // Clear previous results

                        if (data.length === 0) {
                            recordsDiv.innerHTML = "<p class='text-center'>No records found.</p>";
                        } else {
                            data.forEach(record => {
                                recordsDiv.innerHTML += `
                                    <div class="record-item">
                                        <h3>${record.type}</h3>
                                        <p><strong>Description:</strong> ${record.description}</p>
                                        <p><strong>Date:</strong> ${record.date}</p>
                                        <p><strong>Time:</strong> ${record.time}</p>
                                        <div class="text-right">
                                            <a href="update_record.php?id=${record.id}" class="btn btn-primary btn-sm">Update</a>
                                            <a href="delete_record.php?id=${record.id}" class="btn btn-red btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                                        </div>
                                    </div>
                                `;
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching records:', error);
                        recordsDiv.innerHTML = "<p class='text-center text-danger'>Error loading records. Please try again later.</p>";
                    });
            }

            // Fetch all records initially
            fetchRecords();

            // Listen for search input
            searchInput.addEventListener('input', function () {
                const query = searchInput.value;
                fetchRecords(query); // Fetch records based on search query
            });
        });
    </script>
</body>
</html>
