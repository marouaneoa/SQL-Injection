<?php
/* Initialize the session */
session_start();
 
/* Check if the user is logged in, if not then redirect him to login page */
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="assets/create.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <nav>
        <div class="logo">
            <img src="assets/img/logo.png">
            <h3>Alpha Password</h3>
        </div>
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i></a>
        
    </nav>
    <h1>Create New Password</h1>

<form action="process_form.php" method="post">
    <input type="text" id="note" name="note" placeholder="Note"><br>
    <input type="text" id="url_name" name="url_name" placeholder="URL/Name">
    <?php if(isset($url_error)) { ?>
        <p style="color:red;"><?php echo $url_error; ?></p>
    <?php } ?>
    <br>
    <div class="pass">
    <input type="text" id="password" name="password" placeholder="Password">
    <button type="button" onclick="generatePassword()"><i class="bi bi-magic"></i></button><br>
    </div>
    <br>
    <input type="submit" value="Add" id="add">
</form>
<script>
    function generatePassword() {
    var passwordInput = document.getElementById("password");
    var generatedPassword = generateRandomPassword(10); // Generate password with 10 characters
    passwordInput.value = generatedPassword;
}

function generateRandomPassword(length) {
    var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    var password = "";
    for (var i = 0; i < length; i++) {
        var randomIndex = Math.floor(Math.random() * charset.length);
        password += charset.charAt(randomIndex);
    }
    return password;
}

</script>
</body>
</html>