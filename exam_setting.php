<?php
require "userinfo.php";

$option = $_POST['ranks'];
$key = $_POST['exam_key'];

$action = mysqli_query($conn, "UPDATE exams SET showparticipants = '$option' WHERE exam_key = '$key'");

if ($action) {
    header("Location: results_page.php?exam_key=$key");
}