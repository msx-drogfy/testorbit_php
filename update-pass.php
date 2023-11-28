<?php
// Assuming you're using PDO for database connection
require 'userinfo.php'; // Include your database connection file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $password = $_POST['password'];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $action = mysqli_query($conn, "UPDATE members SET passcode='$passwordHash' WHERE id='$user_id'");
    if ($action) {
        notify("Your account password has been updated successfully", "success");
        $_SESSION['username'] = $username;
        // Redirect to account.php
        header("Location: account.php");
        exit();
    }else {
        notify(mysqli_error($conn), "danger");
        // Redirect to account.php
        header("Location: account.php");
        exit();
    }
}

?>
