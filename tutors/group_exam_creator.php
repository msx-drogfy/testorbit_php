<?php
require "userinfo.php";


$group_key = $_POST['group_key'];
if (isset($_POST['topics']) && is_array($_POST['topics'])) {
    $selectedTopics = $_POST['topics'];
    $topics = implode(", ", $selectedTopics);
} else {
    notify("Topics are not set properly.","warning");
    header("Location: grouppage.php?key=$group_key"); // Replace 'previouspage.php' with the actual page to return to
    exit();
}

if (isset($_POST['number_of_questions']) && is_numeric($_POST['number_of_questions'])) {
    $question = $_POST['number_of_questions'];
} else {
    notify("Number of questions is not set properly.","warning");
    header("Location: grouppage.php?key=$group_key");
    exit();
}


if (isset($_POST['exam_time'])) {
    $duration = $_POST['exam_time'];
}else {
    echo 2;
    $duration = $question * 2;
}


if (isset($_POST['schedule'])) {
    $schedule = $_POST['schedule'];

    if ($schedule === "yes") {
        // Check for dates and times only if schedule is set to "yes"
        if (isset($_POST['dates']) && isset($_POST['times'])) {
            $dates = $_POST['dates'];
            $times = $_POST['times'];

            // Perform further validation if necessary
        } else {
            notify("Dates or times are not set properly.", "warning");
            header("Location: grouppage.php?key=$group_key");
            exit();
        }
    } else {
        // If schedule is not "yes", set $dates and $times to 0
        $dates = 0;
        $times = 0;
    }
} else {
    notify("Schedule is not set properly.", "warning");
    header("Location: grouppage.php?key=$group_key");
    exit();
}

$exam_name = $_POST['exam_name'];
if (isset($_POST['ranks'])) {
    $show = $_POST['ranks'];
}else{
    $show= "no";
}
$date_time = $dates." ".$times;
$unixTimestamp = strtotime($date_time);

$exam_key = substr(str_shuffle(str_repeat("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", 4)), 0, 4) . '-' . substr(str_shuffle(str_repeat("ABCDEFGHIJKLMNOPQRSTUVWXYZ", 3)), 0, 3) . '-' . substr(str_shuffle(str_repeat("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", 4)), 0, 4);
if ($schedule == "no") {
    $unixTimestamp = $time;
}

$closingtime = $unixTimestamp + ($duration * 60);
// Insert the new exam into the exams table
$time_added = $time;
$stmt = $mysqli->prepare("INSERT INTO exams (creator, exam_key, timeadded, scheduled, group_key, starttime, exam_topics, exam_name, showparticipants, duration, closingtime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssssssii", $user_id, $exam_key, $time_added, $schedule, $group_key, $unixTimestamp, $topics, $exam_name, $show, $duration, $closingtime);
$stmt->execute();
if (count($selectedTopics) > 0) {
    // Create a string of placeholders
    $placeholders = implode(',', array_fill(0, count($selectedTopics), '?'));

    // SQL query
    // $sql = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM exam_questions WHERE topic IN ($selectedTopics)"));
    $sql = "SELECT * FROM exam_questions WHERE topic IN ($placeholders) ORDER BY RAND() LIMIT $question";
    
    // Prepare statement
    $stmt = $mysqli->prepare($sql);

    // Dynamically bind parameters
    $types = str_repeat('s', count($selectedTopics)); // 's' for string type
    $stmt->bind_param($types, ...$selectedTopics);
    
    // Execute the statement
    $stmt->execute();

    // Get result set
    $result = $stmt->get_result();

}
$s = 0;

while ($row = $result->fetch_assoc()) {
    $s++;
    // Display each topic
    // echo "$s : ".htmlspecialchars($row['question'])."<br>"; // Replace 'topic_name' with the actual column name
    // Create the exam_sets entry. This assumes `question['id']` is the `exam_question_key`.
    $k1 = $row['id'];
    // Insert exam set entries into the database
    $stmt = $mysqli->prepare('INSERT INTO exam_sets (exam_key, exam_question_key) VALUES (?, ?)');
    $stmt->bind_param("si", $exam_key, $k1);
    $stmt->execute();
    // Additional topic details
}


// Generate a unique 7-character key for the exam


// Redirect to the exam start page
notify("Congratulations, your exam has been created successfully", "success");
Header("Location: grouppage.php?key=$group_key");

