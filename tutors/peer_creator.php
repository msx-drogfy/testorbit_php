<?php
require "userinfo.php";

$name = $_POST['group_key'];

// Generate a unique 7-character key for the exam
$group_key = substr(str_shuffle(str_repeat("ABCDEFGHIJKLMNOPQRSTUVWXYZ", 3)), 0, 3) . '-' . substr(str_shuffle(str_repeat("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", 4)), 0, 4) . '-' . substr(str_shuffle(str_repeat("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", 4)), 0, 4);

$action = mysqli_query($conn, "INSERT INTO peergroups(creator_id, group_name, group_key, time_created) VALUES('$user_id', '$name', '$group_key', '$time')");

if ($action) {
    $action2 = mysqli_query($conn, "INSERT INTO peer_group_admin(user_id, group_key) VALUES('$user_id', '$group_key')");
    if ($action2) {
        notify("Your peer group <b>$name</b> has been created.", "success");
        header("Location: peergroups.php");
    } else {
        echo mysqli_error($conn);
    }
} else {
    echo mysqli_error($conn);
}
