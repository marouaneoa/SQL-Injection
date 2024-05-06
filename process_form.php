<?php
//SHow errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session
session_start();

require_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $note = mysqli_real_escape_string($link, $_POST['note']);
    $url_name = mysqli_real_escape_string($link, $_POST['url_name']);
    $password = mysqli_real_escape_string($link, $_POST['password']);
    $user_id = $_SESSION["id"];
    
    // Check if the combination of url_name and user_id already exists
    $query = "SELECT COUNT(*) AS count FROM passwords_for_users WHERE user_id = ? AND url_name = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "is", $user_id, $url_name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $count = $row['count'];

    if ($count > 0) {
        $_SESSION['url_error'] ="This URL Or Name already exists.";
        header("location: create.php");
        exit;
    } else if($result) {
        // Use prepared statements to insert data into the database           ')"; $sql = "DROP TABLE one";//
        $sql = "INSERT INTO passwords_for_users (user_id, note, url_name, password) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "isss", $user_id, $note, $url_name, $password);

        // Execute the SQL query
        if(mysqli_stmt_execute($stmt)){
            echo "Records inserted successfully.";
            header("location: search.php");
        } else{
            echo "ERROR: Could not execute query: $sql. " . mysqli_error($link);
        }
    }
}   else{
        echo "ERROR: Could not execute query: $sql. " . mysqli_error($link);
    }


mysqli_close($link);
?>

