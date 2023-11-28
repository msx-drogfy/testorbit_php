<?php
require "userinfo.php";

function notifyError($message) {
    notify($message, "danger");
    header("Location: peergroups.php");
    exit();
}

// Check if group_key is posted
if (!isset($_POST['group_key'])) {
    notifyError("Group key not provided.");
}

$group_key = $_POST['group_key'];

// Check if the user is already a member of the group
$stmt = $conn->prepare("SELECT statusx FROM peer_group_members WHERE group_key = ? AND user_id = ?");
$stmt->bind_param("si", $group_key, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['statusx'] == "declined") {
        notifyError("You cannot join this peer group");
        header("Location: peergroups.php");
        exit();
    }
    notifyError("You have already joined this peer group");
    header("Location: peergroups.php");
    exit();
}

// Check if the group exists
$stmt = $conn->prepare("SELECT group_name FROM peergroups WHERE group_key = ?");
$stmt->bind_param("s", $group_key);
$stmt->execute();
$groupResult = $stmt->get_result();
if (!$groupResult || $groupResult->num_rows == 0) {
    notifyError("Group with the specified key does not exist.");
}
$group = $groupResult->fetch_assoc();
$stmt->close();

// Insert the user into the group
$insertStmt = $conn->prepare("INSERT INTO peer_group_members(group_key, user_id, time_joined) VALUES(?, ?, ?)");
$insertStmt->bind_param("sis", $group_key, $user_id, $time);
$action = $insertStmt->execute();
$insertStmt->close();

if ($action) {
    notify("You have successfully joined the group <b>{$group['group_name']}</b>", "success");
    header("Location: peergroups.php");
    exit();
} else {
    notifyError("Error joining the group: " . htmlspecialchars(mysqli_error($conn)));
    header("Location: peergroups.php");
}

$conn->close();
?>
