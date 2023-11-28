<?php

require "userinfo.php";

    $user = mysqli_real_escape_string($conn, $_GET['user_id']);
    $group_key = mysqli_real_escape_string($conn, $_GET['group_key']);

    // Check if the connection is established
    if ($conn) {
        // Check if the user exists in the database
        $user_check = mysqli_query($conn, "SELECT * FROM members WHERE id='$user'");

        if ($user_check && mysqli_num_rows($user_check) > 0) {
            // User exists, proceed with update
            $update = mysqli_query($conn, "UPDATE peer_group_members SET statusx='joined' WHERE group_key='$group_key' AND user_id='$user'");

            // Check if the update query was successful
            if ($update) {
                $name = get_user_info($user, "first_name")." ".get_user_info($user, "last_name");
                notify("$name has been admitted successfully", "success");
                header("Location: grouppage.php?key=$group_key");
                exit;
            } else {
                // Handle query error
                notify("Error updating record: " . mysqli_error($conn), "error");
                header("Location: grouppage.php?key=$group_key");
                exit;
            }
        } else {
            // User does not exist
            notify("User does not exist", "error");
            header("Location: grouppage.php?key=$group_key");
            exit;
        }
    } else {
        // Handle connection error
        notify("Database connection failed: " . mysqli_connect_error(), "error");
        header("Location: grouppage.php?key=$group_key");
        exit;
    }

