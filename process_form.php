<?php
session_start();

require_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $note = $_POST['note'];
    $url_name = $_POST['url_name'];
    $password = $_POST['password'];
    $user_id = $_SESSION["id"];


    // Concatenate user inputs directly into the SQL query (vulnerable to SQL injection)
    $sql = "INSERT INTO passwords_for_users (user_id, note, url_name, password) VALUES ($user_id, '$note', '$url_name', '$password')";

    // Execute the SQL query
    if(mysqli_query($link, $sql)){
        echo "Records inserted successfully.";
    } else{
        echo "ERROR: Could not execute query: $sql. " . mysqli_error($link);
    }
}

mysqli_close($link);
?>
