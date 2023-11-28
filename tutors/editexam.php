<?php 
require "userinfo.php";

$exam_key = $_POST['exam_key'];
$add_time = $_POST['add_time'];
$group_key = $_POST['group_key'];

$secs = $add_time * 60;

$exam = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM exams WHERE exam_key = '$exam_key'"));

$closingtime = $exam['closingtime'];

$new_closingtime = $closingtime + $secs;

$update = mysqli_query($conn, "UPDATE exams SET closingtime = '$new_closingtime' WHERE exam_key = '$exam_key'");

$name = $exam['exam_name'];

if ($update) {
    notify("<b>$name</b> closing time has been updated successfully", "success");
    header("Location: grouppage.php?key=$group_key");
}else{
    echo mysqli_error($conn);
}