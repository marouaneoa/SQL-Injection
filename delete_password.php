<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "connection.php";

// Check if an ID was provided
if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $id = trim($_GET['id']);

    $sql = "DELETE FROM passwords_for_users WHERE id = ? AND user_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $id, $_SESSION["id"]);
        
        if (mysqli_stmt_execute($stmt)) {
            // Redirect back to search.php with the same search query if it was present
            $redirectUrl = "search.php";
            if (!empty($_GET['search_query'])) {
                $redirectUrl .= "?search_query=" . urlencode($_GET['search_query']);
            }
            header("location: $redirectUrl");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Error: No ID provided.";
}

mysqli_close($link);
?>
