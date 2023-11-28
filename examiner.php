<?php
require "userinfo.php";

$exam_key = $_GET['key'];

$exam_info = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) AS questions FROM exam_sets WHERE exam_key='$exam_key'"));
$duration = $exam_info['questions']*2;

// Check if the user has already taken the exam
$registration = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM exam_registration WHERE user_id = '$user_id' AND exam_key = '$exam_key'"));

// If the user hasn't taken the exam, insert a new record
if ($registration == NULL || $registration['timeend'] == NULL) {
    $stmt = $conn->prepare('SELECT COUNT(*) FROM exam_sets WHERE exam_key = ?');
    $stmt->bind_param('s', $exam_key);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array();
    $question_count = $row[0];

    $ex = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM exams WHERE exam_key = '$exam_key'"));

    if ($ex['scheduled'] == 'yes') {
        if ($time < $ex['starttime']) {
            $d = date("D d-M-Y h:i A", $ex['starttime']);
            notify("This is a scheduled peer group exam that starts at <b>$d</b>", "info");
            header("Location: exam_start.php?exam_key=$exam_key");
            exit();
        }       
        if ($time > $ex['closingtime']) {
            $d = date("D d-M-Y h:i A", ($ex['starttime'] + ($question_count*2*60)));
            notify("This peer group exam was closed on <b>$d</b>", "danger");
            header("Location: exam_start.php?exam_key=$exam_key");
            exit();
        }       
    }

    if ($registration == NULL) {
        if (isset($_GET['group_key'])) {
            $current_time = $time;
            $kk = $_GET['group_key'];
            $stmt = $conn->prepare('INSERT INTO exam_registration (user_id, exam_key, questions, timestart, duration,group_key) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->bind_param('ssiiis', $user_id, $exam_key, $question_count, $current_time, $duration, $kk);
            $stmt->execute();
        }else {
            $current_time = $time;
            $stmt = $conn->prepare('INSERT INTO exam_registration (user_id, exam_key, questions, timestart, duration) VALUES (?, ?, ?, ?, ?)');
            $stmt->bind_param('ssiii', $user_id, $exam_key, $question_count, $current_time, $duration);
            $stmt->execute();
        }
    }


    header("Location: exam_page.php?key=$exam_key");

}else{
    notify("You have already taken this exam.", "info");
    header("Location: exam_start.php?exam_key=$exam_key");
}

// Rest of your PHP code
?>
