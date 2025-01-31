<?php
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'config.php'; // Include your database connection configuration file

    // Prepare and bind SQL statement to insert student data
    $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, date_of_birth, gender, phone, email, htno, branch, year, sem) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssii", $first_name, $last_name, $date_of_birth, $gender, $phone, $email, $htno, $branch, $year, $sem);

    // Set parameters and execute
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $htno = $_POST['htno'];
    $branch = $_POST['branch'];
    $year = $_POST['year'];
    $sem = $_POST['sem'];

    if ($stmt->execute()) {
        // Insertion successful
        echo "<script>alert('Student details added successfully.')</script>";
    } else {
        // Insertion failed
        echo "<script>alert('Failed to add student details.')</script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0; /* Light Gray */
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .dashboard {
            background-color: #ffffff; /* White */
            border: 2px solid #e0e0e0; /* Light Gray */
            border-radius: 10px;
            padding: 20px;
            width: 90%; /* Adjusted width for better layout */
            max-width: 800px;
            text-align: center;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        .dashboard h2 {
            color: #333333; /* Dark Gray */
            margin-bottom: 20px;
        }

        .dashboard h3 {
            color: #555555; /* Gray */
            margin-top: 10px;
        }

        .dashboard form {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .dashboard .form-group {
            flex-basis: 48%; /* Adjust width for form items */
            margin-bottom: 10px;
        }

        .dashboard label {
            color: #555555; /* Gray */
            margin-bottom: 5px;
            text-align: left;
            display: block;
        }

        .dashboard input[type="text"],
        .dashboard input[type="email"],
        .dashboard input[type="date"],
        .dashboard input[type="number"],
        .dashboard select {
            width: 100%; /* Full width input fields */
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #e0e0e0; /* Light Gray */
            border-radius: 5px;
            box-sizing: border-box;
        }

        .dashboard button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 18px;
            background-color: #007bff; /* Blue */
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .dashboard button:hover {
            background-color: #0056b3; /* Darker Blue */
        }

        .dashboard a {
            color: #007bff; /* Blue */
            text-decoration: none;
            margin-top: 10px;
            display: block;
        }

        .dashboard a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, Admin!</h2>
        <div class="dashboard">
            <h3>Add Student Details</h3>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="date_of_birth">Date of Birth:</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" required>
                </div>
                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="htno">HT No:</label>
                    <input type="text" id="htno" name="htno" required>
                </div>
                <div class="form-group">
                    <label for="branch">Branch:</label>
                    <input type="text" id="branch" name="branch" required>
                </div>
                <div class="form-group">
                    <label for="year">Year:</label>
                    <input type="number" id="year" name="year" required>
                </div>
                <div class="form-group">
                    <label for="sem">Semester:</label>
                    <input type="number" id="sem" name="sem" required>
                </div>
                <button type="submit">Add Student</button>
            </form>
            <br>
            <a href="view_students.php">View All Students</a>
            <br>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
