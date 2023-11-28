<?php
require "functions.php";

session_start();

$user_id = $_POST['select'];

$user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tutors WHERE id = '$user_id'"));


$_SESSION['tutor_username'] = $user['username'];
$_SESSION['tutor_user_id'] = $user['id'];
setcookie("username", $user['username'], time() + (3600 * 5), "/"); // Sets a cookie named "username" with the value $username that expires in 5 hours
setcookie("user_id", $user['id'], time() + (3600 * 5), "/"); // Sets a cookie named "user_id" with the value $id that expires in 5 hours


// Redirect to dashboard or desired page
header("Location: ./index.php");