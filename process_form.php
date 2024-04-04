<?php
session_start();

require_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $note = $_POST['note'];
    $url_name = $_POST['url_name'];
    $password = $_POST['password'];
    $user_id = $_SESSION["id"];

    // Check if the combination of url_name and user_id already exists
    $query = "SELECT COUNT(*) AS count FROM passwords_for_users WHERE user_id = $user_id AND url_name = '$url_name'";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_assoc($result);
    $count = $row['count'];

    if ($count > 0) {
        $_SESSION['url_error'] ="This URL Or Name already exists.";
        header("location: create.php");
        exit;
    } else if($result) {
        // Concatenate user inputs directly into the SQL query (vulnerable to SQL injection)
        $sql = "INSERT INTO passwords_for_users (user_id, note, url_name, password) VALUES ($user_id, '$note', '$url_name', '$password')";

        // Execute the SQL query
        if(mysqli_query($link, $sql)){
            echo "Records inserted successfully.";
            header("location: search.php");
        } else{
            echo "ERROR: Could not execute query: $sql. " . mysqli_error($link);
        }
    }
    else{
        echo "ERROR: Could not execute query: $sql. " . mysqli_error($link);
    }
}

mysqli_close($link);
?>

