<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'config.php';

// Initialize variables to hold form field values
$first_name = $last_name = $date_of_birth = $student_id = $gender = $phone = $email = $htno = $branch = $year = $sem = "";

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $htno = $_POST['htno'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $student_id = $_POST['student_id'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $branch = $_POST['branch'];
    $year = $_POST['year'];
    $sem = $_POST['sem'];

    // Prepare and execute SQL statement to update student details
    $stmt = $conn->prepare("UPDATE students SET first_name = ?, last_name = ?, date_of_birth = ?, student_id = ?, gender = ?, phone = ?, email = ?, branch = ?, year = ?, sem = ? WHERE htno = ?");
    $stmt->bind_param("sssssssssss", $first_name, $last_name, $date_of_birth, $student_id, $gender, $phone, $email, $branch, $year, $sem, $htno);

    if ($stmt->execute()) {
        // Redirect to view_students.php after successful update
        header("Location: view_students.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
}

// Retrieve the student's current details for editing
if (isset($_GET['htno'])) {
    $htno = $_GET['htno'];
    
    // Prepare and execute SQL statement to fetch student details by htno
    $stmt = $conn->prepare("SELECT * FROM students WHERE htno = ?");
    $stmt->bind_param("s", $htno);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        // Assign fetched values to variables
        $first_name = $student['first_name'];
        $last_name = $student['last_name'];
        $date_of_birth = $student['date_of_birth'];
        $student_id = $student['student_id'];
        $gender = $student['gender'];
        $phone = $student['phone'];
        $email = $student['email'];
        $branch = $student['branch'];
        $year = $student['year'];
        $sem = $student['sem'];
    } else {
        echo "No student found with HT No: " . htmlspecialchars($htno);
        exit();
    }

    $stmt->close();
} else {
    echo "HT No parameter is missing";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0; /* Light Gray */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .edit-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 600px;
            margin: 20px;
        }

        .edit-container h2 {
            color: #333333; /* Dark Gray */
            text-align: center;
            margin-bottom: 20px;
        }

        .edit-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-gap: 10px;
        }

        .edit-form label {
            color: #666666; /* Medium Gray */
            font-weight: bold;
            margin-bottom: 5px;
        }

        .edit-form input, .edit-form select {
            width: 100%;
            padding: 10px;
            border: 1px solid #cccccc; /* Light Gray */
            border-radius: 5px;
            font-size: 16px;
        }

        .edit-form select {
            cursor: pointer;
        }

        .edit-form button {
            grid-column: span 2;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff; /* Bootstrap Blue */
            color: #ffffff;
            font-size: 16px;
            cursor: pointer;
        }

        .edit-form button:hover {
            background-color: #0056b3; /* Darker Blue */
        }

        .edit-form .back-link {
            text-align: center;
            margin-top: 10px;
        }

        .edit-form .back-link a {
            color: #007bff; /* Bootstrap Blue */
            text-decoration: none;
        }

        .edit-form .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <h2>Edit Student Details</h2>
        <form class="edit-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="htno" value="<?php echo htmlspecialchars($htno); ?>">
            
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>

            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($date_of_birth); ?>" required>

            <label for="student_id">Student ID:</label>
            <input type="text" id="student_id" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male" <?php if ($gender === 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($gender === 'Female') echo 'selected'; ?>>Female</option>
                <option value="Other" <?php if ($gender === 'Other') echo 'selected'; ?>>Other</option>
            </select>

            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label for="branch">Branch:</label>
            <input type="text" id="branch" name="branch" value="<?php echo htmlspecialchars($branch); ?>" required>

            <label for="year">Year:</label>
            <input type="number" id="year" name="year" value="<?php echo htmlspecialchars($year); ?>" required>

            <label for="sem">Semester:</label>
            <input type="number" id="sem" name="sem" value="<?php echo htmlspecialchars($sem); ?>" required>

            <button type="submit">Update Student</button>
        </form>
        <div class="back-link">
            <a href="view_students.php">Back to Student List</a>
        </div>
    </div>
</body>
</html>
