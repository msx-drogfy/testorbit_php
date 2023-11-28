<?php
require "functions.php";
session_start(); 

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

// Check if the user is logged in through session
if (isset($_SESSION['user_id'])) {
    // User is logged in, proceed with the page
} else {
    // Check if the user cookie is set
    if (isset($_COOKIE['user_id'])) {
        // Cookie is set, user can be considered logged in
        $_SESSION['user_id'] = $_COOKIE['user_id']; // Optional: Set the session from the cookie
        $_SESSION['username'] = $_COOKIE['username']; // Optional: Set the session from the cookie
    } else {
        // User is not logged in, redirect to logout or login page
        header('Location: logout.php'); // Replace 'logout.php' with your logout or login page URL
        exit();
    }
}