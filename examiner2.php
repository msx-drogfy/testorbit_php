<?php
require "userinfo.php";

$exam_key = $_POST['exam_key'];


// SQL query to check if the exam is registered
$sql = "SELECT * FROM exam_registration WHERE exam_key = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('s', $exam_key); // 'ss' indicates two string parameters
$stmt->execute();

$result = $stmt->get_result();

notify("Your exam is ready.", "success");
header("Location:  exam_start.php?exam_key=$exam_key");