<?php
session_start(); // Start the session
include 'functions.php'; // Include your database configuration file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usernameOrEmail = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, passcode FROM tutors WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['passcode'])) {
        $_SESSION['tutor_username'] = $user['username'];
        $_SESSION['tutor_user_id'] = $user['id'];
        setcookie("username", $user['username'], time() + (3600 * 5), "/"); // Sets a cookie named "username" with the value $username that expires in 5 hours
        setcookie("user_id", $user['id'], time() + (3600 * 5), "/"); // Sets a cookie named "user_id" with the value $id that expires in 5 hours

        // Redirect to dashboard or desired page
        header("Location: index.php");
    } else {
        notify("Invalid username or password.", "danger");
    header("Location: auth.php");
        // Include login form or redirect
    }

    $stmt->close();
    $conn->close();
} else {
    // Show the login form
    header("Location: auth.php"); // Assuming you have an HTML form in this file
}
?>
