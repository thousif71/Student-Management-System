<?php
session_start();

// Check if user is not logged in or is not an admin, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'config.php'; // Include your database connection configuration file

// Fetch student details from the database
$sql = "SELECT first_name, last_name, date_of_birth, student_id, gender, phone, email, htno, branch, year, sem FROM students";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0; /* Light Gray */
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .student-card {
            background-color: #ffffff; /* White */
            border: 2px solid #e0e0e0; /* Light Gray */
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        .student-card h3 {
            color: #333333; /* Dark Gray */
            margin-bottom: 10px;
        }

        .student-card p {
            margin-bottom: 5px;
            color: #555555; /* Gray */
        }

        .student-card a {
            color: #007bff; /* Blue */
            text-decoration: none;
            margin-top: 10px;
            display: block;
        }

        .student-card a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="student-card">';
                echo '<h3>' . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . '</h3>';
                echo '<p><strong>Date of Birth:</strong> ' . htmlspecialchars($row['date_of_birth']) . '</p>';
                echo '<p><strong>Student ID:</strong> ' . htmlspecialchars($row['student_id']) . '</p>';
                echo '<p><strong>Gender:</strong> ' . htmlspecialchars($row['gender']) . '</p>';
                echo '<p><strong>Phone:</strong> ' . htmlspecialchars($row['phone']) . '</p>';
                echo '<p><strong>Email:</strong> ' . htmlspecialchars($row['email']) . '</p>';
                echo '<p><strong>HT No:</strong> ' . htmlspecialchars($row['htno']) . '</p>';
                echo '<p><strong>Branch:</strong> ' . htmlspecialchars($row['branch']) . '</p>';
                echo '<p><strong>Year:</strong> ' . htmlspecialchars($row['year']) . '</p>';
                echo '<p><strong>Semester:</strong> ' . htmlspecialchars($row['sem']) . '</p>';
                echo '<a href="edit.php?htno=' . urlencode($row['htno']) . '">Edit</a> | ';
                echo '<a href="delete.php?htno=' . urlencode($row['htno']) . '">Delete</a>';
                echo '</div>';
            }
        } else {
            echo "<p>No students found</p>";
        }
        ?>
    </div>
    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
