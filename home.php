<?php
// Start session to access session variables
session_start();

// Check if user is logged in, otherwise redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit();
}

// Display welcome message and user details
$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <style>
        .wrapper {width: 360px; padding: 20px;}
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Welcome, <?php echo $username; ?>!</h2>
        <p>This is your home page.</p>
        <p><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>
