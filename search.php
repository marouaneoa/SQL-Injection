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

$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

if ($search_query !== '') {


   $sql = "SELECT * FROM passwords_for_users WHERE user_id = ? AND url_name LIKE ?";
    $stmt = $link->prepare($sql);
    $search_term = "%{$search_query}%";
    $stmt->bind_param("is", $_SESSION["id"], $search_term);
    $stmt->execute();
    $result = $stmt->get_result();

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="assets/search.css">
    <script>
        function togglePasswordVisibility(passwordFieldId) {
            var passwordField = document.getElementById(passwordFieldId);
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }
    </script>
</head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<body>
    <header>
    <div class="header-left">
        <img src="assets/img/Security.png" alt="Password Manager Logo">
        <a href="#">Password Manager</a>
    </div>
    <a href="logout.php" class="logout-icon" title="Sign Out of Your Account">
        <img src="assets/img/logout.png" alt="Logout" class="return-icon">
    </a>
</header>

    <main>
        <div class="search-and-add">
            <form action="search.php" method="get">
                <input type="text" name="search_query" placeholder="Search by Name or URL" value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Search</button>
                <a id='addpassword' href="create.php"><i class="bi bi-plus-circle-fill"></i></a>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>S.L</th>
                    <th>Date Created</th>
                    <th>URL/Unique Name</th>
                    <th>Password</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($search_query !== ''): ?>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <?php $passwordFieldId = "password" . htmlspecialchars($row['id']); ?>
                            <tr>
                                <td><?= htmlspecialchars($row['user_id']); ?></td>
                                <td><?= htmlspecialchars($row['created_at']); ?></td>
                                <td><?= htmlspecialchars($row['url_name']); ?></td>
                                <td>
                                    <input type="password" id="<?= $passwordFieldId ?>" value="<?= htmlspecialchars($row['password']); ?>" readonly>
                                    <button type="button" onclick="togglePasswordVisibility('<?= $passwordFieldId ?>')" style="margin-top: 3%; border: none; background-color: #75FFDE; color: black;">View</button>

                                </td>
                                <td style="text-align: center; vertical-align: middle;">
    <a href='delete_password.php?id=<?= $row['id'] ?>&search_query=<?= urlencode($search_query) ?>' onclick='return confirm("Are you sure you want to delete this password?");'>
        <span style='color: red;'>&#10006;</span>
    </a>
</td>

                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No results found.</td></tr>
                    <?php endif; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
