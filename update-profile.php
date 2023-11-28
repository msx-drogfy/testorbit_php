<?php
// Assuming you're using PDO for database connection
require 'userinfo.php'; // Include your database connection file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $action = mysqli_query($conn, "UPDATE members SET first_name='$firstName', last_name='$lastName', username='$username', email='$email', phone='$phone' WHERE id='$user_id'");
    if ($action) {
        notify("Your account details have been updated successfully", "success");
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
