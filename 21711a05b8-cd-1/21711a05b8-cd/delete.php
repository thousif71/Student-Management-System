<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'config.php';

// Check if HT No is provided via GET parameter
if (isset($_GET['htno'])) {
    $htno = $_GET['htno'];

    // Prepare DELETE statement
    $stmt = $conn->prepare("DELETE FROM students WHERE htno = ?");
    $stmt->bind_param("s", $htno);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Student deleted successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "HT No parameter is missing";
}

$conn->close();

// Redirect back to view_students.php after deletion
header("Location: view_students.php");
exit();
?>
