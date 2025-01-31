<?php
session_start();

// Redirect to dashboard if user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // Include database connection
    require 'config.php';

    // Prepare statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $input_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Fetch user data from result set
        $row = $result->fetch_assoc();

        // Verify password
        if (password_verify($input_password, $row['password'])) {
            // Password is correct, set session variables
            $_SESSION['username'] = $input_username;

            // Optionally set a cookie for persistent login (example with 10 minutes expiry)
            setcookie("username", $input_username, time() + (600), "/");

            // Redirect to dashboard after successful login
            header("Location: dashboard.php");
            exit();
        } else {
            // Invalid password
            $error_message = "Invalid username or password";
        }
    } else {
        // Invalid username
        $error_message = "Invalid username or password";
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
    <title>Login</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9; /* Light gray */
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background-color: #ffffff; /* White */
            border-radius: 8px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
        }
        .login-container h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #333333; /* Dark gray */
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 8px;
            color: #555555; /* Gray */
        }
        input[type="text"],
        input[type="password"] {
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #dddddd; /* Light gray */
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            padding: 12px;
            border: none;
            border-radius: 5px;
            background-color: #007bff; /* Blue */
            color: #ffffff; /* White */
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3; /* Darker blue */
        }
        .error-message {
            color: #ff0000; /* Red */
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login Page</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <?php if (isset($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
