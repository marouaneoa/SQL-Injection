<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Include the database connection file
include 'connection.php';

// Start session to store user data
session_start();

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Process form submission when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check input errors before authenticating
    if (empty($username_err) && empty($password_err)) {
        // SQL injection vulnerability: directly concatenate user input into SQL query
        $sql = "SELECT id, username, password FROM users WHERE username = '$username' AND password = '$password";
        echo $sql; // For demonstration purposes

        // Attempt to execute the SQL query
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // Fetch result row
            $row = $result->fetch_assoc();

            // Start a new session and store user data
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $row["id"];
            $_SESSION["username"] = $row["username"];

            // Redirect to home page after successful login
            header("location: home.php");
            exit; // Ensure no further code execution after redirection
        } else {
            // Display an error message if login fails
            $login_err = "Invalid username or password.";
        }
    }

    // Close connection
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        .wrapper {width: 360px; padding: 20px;}
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label>Username</label>
                <input type="text" name="username" value="<?php echo $username; ?>">
                <span><?php echo $username_err; ?></span>
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password">
                <span><?php echo $password_err; ?></span>
            </div>
            <div>
                <input type="submit" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
            <p><?php echo $login_err; ?></p>
        </form>
    </div>
</body>
</html>
