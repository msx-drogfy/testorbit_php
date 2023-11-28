<?php
require "userinfo.php";

$key = $_GET['exam_key'];
$gkey = $_GET['group_key'];
$name = get_exam_info($key, "exam_name");

$action = mysqli_query($conn, "DELETE FROM exams WHERE exam_key='$key'");
if ($action) {
    $action2 = mysqli_query($conn, "DELETE FROM exam_sets WHERE exam_key='$key'");
    if ($action2) {
        notify("$name exam of key <b>$key</b> has been deleted successfully", "warning");
        header("Location: grouppage.php?key=$gkey");
    }else{
        notify(mysqli_error($conn), "danger");
        header("Location: grouppage.php?key=$gkey");
    }
}else{
    notify(mysqli_error($conn), "danger");
    header("Location: grouppage.php?key=$gkey");
}