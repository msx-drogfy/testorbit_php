<?php
require "userinfo.php";

$exam_key = $_GET['key'];

$exam_info = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) AS questions FROM exam_sets WHERE exam_key='$exam_key'"));
$duration = $exam_info['questions']*2;

// Check if the user has already taken the exam
$stmt = $conn->prepare('SELECT * FROM exam_registration WHERE user_id = ? AND exam_key = ?');
$stmt->bind_param('ss', $user_id, $exam_key);
$stmt->execute();
$registration = $stmt->get_result()->fetch_assoc();

// If the user hasn't taken the exam, insert a new record
if (!$registration) {
    $stmt = $conn->prepare('SELECT COUNT(*) FROM exam_sets WHERE exam_key = ?');
    $stmt->bind_param('s', $exam_key);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array();
    $question_count = $row[0];


    $current_time = time();
    $stmt = $conn->prepare('INSERT INTO exam_registration (user_id, exam_key, questions, timestart, duration) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('ssiii', $user_id, $exam_key, $question_count, $current_time, $duration);
    $stmt->execute();


    header("Location: exam_page.php?key=$exam_key");

}else{
    notify("You have already taken this exam.", "info");
    header("Location: exam_start.php?exam_key=$exam_key");
}

// Rest of your PHP code
?>
