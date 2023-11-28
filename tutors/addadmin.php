<?php
require "userinfo.php"; 

$admin = $_POST['admin_id'];
$group = $_POST['group_key'];


$action = mysqli_query($conn, "INSERT INTO peer_group_admin(user_id, group_key) VALUES('$admin', '$group')");

$name = get_admin_info($admin, "first_name");
$name2 = get_admin_info($admin, "last_name");

if ($action) {
    notify("<b>$name $name2</b> has been added as group admin", "success");
    header("Location: grouppage.php?key=$group");
}else {
    echo mysqli_error($conn);
}