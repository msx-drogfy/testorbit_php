<?php
require "userinfo.php";
// id	

$feed = $_POST['feedback'];

$cc = mysqli_query($conn, "INSERT INTO feedback(username, feedback, times) VALUES('$username', '$feed', '$time')");

if ($cc) {
    notify("Your feedback has been recorder.", "success");
    header("Location: feedback.php");
}