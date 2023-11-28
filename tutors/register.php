<?php
session_start(); // Start the session
include 'functions.php'; // Include your database configuration file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Consider using password_hash() for security

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT id FROM tutors WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        notify("Username or Email already exists. Choose another one.", "info");
        // Redirect or include the registration form page
        header("Location: auth.php");
    } else {
        // Insert new user
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $insertStmt = $conn->prepare("INSERT INTO tutors (first_name, last_name, username, email, passcode) VALUES (?, ?, ?, ?, ?)");
        $insertStmt->bind_param("sssss", $fname, $lname, $username, $email, $passwordHash);
        if ($insertStmt->execute()) {
            $_SESSION['tutor_username'] = $username;
            notify("Your account has been created, Log in.", "success");
            // Redirect to login page or wherever
            header("Location: auth.php");
        } else {
            echo "Error: " . $insertStmt->error;
        }
        $insertStmt->close();
    }
    $stmt->close();
    $conn->close();
} else {
    // Show the registration form
    header("Location: auth.php"); // Assuming you have an HTML form in this file
}
?>
