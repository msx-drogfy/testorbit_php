<?php
require "userinfo.php";

// Assuming the number of questions is sent as form-data in the POST request
$num_questions = isset($_POST['number_of_questions']) ? (int)$_POST['number_of_questions'] : null;
$ranks = isset($_POST['ranks']) ? $_POST['ranks'] : null;

// Check if both num_questions and ranks are provided
if ($num_questions === null || !$ranks) {
    // Flash message and redirect (You need to define the flash and redirect function)
    notify("Please provide both the number of questions and choice of ranking.", "danger");
    header("Location: test.php");
    exit(); 
}

if ($num_questions === null) {
    // Flash message and show form again
    notify('Please specify the number of questions.', 'danger');
    header("Location: test.php");
    exit();
}

// Generate a unique 7-character key for the exam
$exam_key = substr(str_shuffle(str_repeat("ABCDEFGHIJKLMNOPQRSTUVWXYZ", 3)), 0, 3) . '-' . substr(str_shuffle(str_repeat("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", 4)), 0, 4);

$duration = $num_questions * 2;
// Insert the new exam into the exams table

if (isset($_POST['topics']) && is_array($_POST['topics'])) {
    $selectedTopics = $_POST['topics'];
    $topics = implode(", ", $selectedTopics);
} else {
    notify("Topics are not set properly.","warning");
    header("Location: test.php?key=$group_key"); // Replace 'previouspage.php' with the actual page to return to
    exit();
}

$time_added = time(); 
$stmt = $mysqli->prepare("INSERT INTO exams (creator, exam_key, timeadded, showparticipants, duration) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $user_id, $exam_key, $time_added, $ranks, $duration);
$stmt->execute();

// Select the required number of questions not yet answered by the user

// Create placeholders for the selected topics
$placeholders = implode(',', array_fill(0, count($selectedTopics), '?'));

// Prepare the SQL query with both conditions: filter by selected topics and check for unanswered questions
$sql = "SELECT q.id, q.question, q.ans1, q.ans2, q.ans3, q.ans4, q.ans5, q.ans6, q.correct_ans, q.check_radio 
        FROM exam_questions q 
        LEFT JOIN question_answers a ON q.id = a.question_id AND a.user_id = ?
        WHERE q.topic IN ($placeholders) AND a.question_id IS NULL
        ORDER BY RAND() 
        LIMIT ?";

// Prepare the statement
$stmt = $mysqli->prepare($sql);

// Combine user_id, selected topics, and num_questions into one array for binding
$params = array_merge([$user_id], $selectedTopics, [$num_questions]);

// Create a string with types for bind_param (i for integer and s for string)
$types = 's' . str_repeat('s', count($selectedTopics)) . 'i'; // Assuming topic IDs and user_id are integers

// Dynamically bind parameters
$stmt->bind_param($types, ...$params);

// Execute the statement
$stmt->execute();
$result = $stmt->get_result();
$questions = $result->fetch_all(MYSQLI_ASSOC);

$exam_question = [];
foreach ($questions as $question) {
    // Create the exam_sets entry. This assumes `question['id']` is the `exam_question_key`.
    $k1 = $question['id'];
    // Insert exam set entries into the database
    $stmt = $mysqli->prepare('INSERT INTO exam_sets (exam_key, exam_question_key) VALUES (?, ?)');
    $stmt->bind_param("si", $exam_key, $k1);
    $stmt->execute();
}

// Redirect to the exam start page
notify("Congratulations, your exam has been created successfully", "success");
Header("Location: exam_start.php?exam_key=$exam_key");
?>
