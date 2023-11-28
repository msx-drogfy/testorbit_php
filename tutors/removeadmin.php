<?php
require "userinfo.php";

$admin = $_GET['admin'];
$key = $_GET['group_key'];

echo $admin;
$mx = mysqli_query($conn, "SELECT * FROM peer_group_admin WHERE ID = '$admin'");
if ($mx) {
    print_r($mx);
    $mds = mysqli_fetch_array($mx);
    print_r($mds);
    echo $mds['group_key'];
    
    $name = get_admin_info($mds['user_id'], "first_name");
    $name2 = get_admin_info($mds['user_id'], "last_name");
}else{
    echo mysqli_error($conn);
}



$action = mysqli_query($conn, "DELETE FROM peer_group_admin WHERE ID='$admin'");
if ($action) {
    // echo "<b>$name $name2</b> has been removed as group admin";
    notify("<b>$name $name2</b> has been removed as group admin", "warning");
    header("Location: grouppage.php?key=$key");
}else{
    echo mysqli_error($conn);
}