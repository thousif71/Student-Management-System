<?php
session_start();

// Redirect to dashboard if user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

// Include database connection
require_once 'config.php'; // Assuming your config.php file contains database connection details

// Initialize variables for form fields
$username = '';
$email = '';
$password = '';
$confirm_password = '';

// Initialize error messages
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        $errors[] = "Username should only contain letters, numbers, and underscores";
    }

    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters";
    }

    // Confirm password
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into database (assuming $conn is your database connection)
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $hashed_password, $email);

        if ($stmt->execute()) {
            // Registration successful, redirect to login page
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Failed to register. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #4CAF50; /* Green */
            font-family: Arial, sans-serif;
        }
        .registration-form {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #ffffff; /* White */
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .registration-form h2 {
            margin-top: 0;
            text-align: center;
            color: #333333; /* Dark gray */
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555555; /* Gray */
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #cccccc; /* Light gray */
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #008CBA; /* Blue */
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background-color: #005F6B; /* Darker blue */
        }
        .error-message {
            color: red;
            margin-top: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="registration-form">
        <h2>User Registration</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            <br>
            <span class="error-message"><?php if (!empty($errors) && in_array("Username is required", $errors)) echo "Username is required"; ?></span>
            <span class="error-message"><?php if (!empty($errors) && in_array("Username should only contain letters, numbers, and underscores", $errors)) echo "Username should only contain letters, numbers, and underscores"; ?></span>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            <br>
            <span class="error-message"><?php if (!empty($errors) && in_array("Email is required", $errors)) echo "Email is required"; ?></span>
            <span class="error-message"><?php if (!empty($errors) && in_array("Invalid email format", $errors)) echo "Invalid email format"; ?></span>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <span class="error-message"><?php if (!empty($errors) && in_array("Password is required", $errors)) echo "Password is required"; ?></span>
            <span class="error-message"><?php if (!empty($errors) && in_array("Password must be at least 8 characters", $errors)) echo "Password must be at least 8 characters"; ?></span>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <br>
            <span class="error-message"><?php if (!empty($errors) && in_array("Passwords do not match", $errors)) echo "Passwords do not match"; ?></span>

            <button type="submit">Register</button>
        </form>
        <?php
        if (!empty($errors)) {
            echo '<div class="error-message">' . implode('<br>', $errors) . '</div>';
        }
        ?>
    </div>
</body>
</html>
