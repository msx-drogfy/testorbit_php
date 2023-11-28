<?php
require "userinfo.php";

$exam_key = $_POST['exam_key'];


// SQL query to check if the exam is registered
$sql = "SELECT * FROM exam_registration WHERE exam_key = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('s', $exam_key); // 'ss' indicates two string parameters
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Exam is registered
    notify("Your exam is ready.", "success");
    header("Location:  exam_start.php?exam_key=$exam_key");
} else {
    // Exam is not registered
    notify("Exam has not been registered.", "danger");
    header("Location: test.php");
}