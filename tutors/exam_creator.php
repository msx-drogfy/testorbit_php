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

// Insert the new exam into the exams table
$time_added = time();
$stmt = $mysqli->prepare("INSERT INTO exams (creator, exam_key, timeadded, showparticipants) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $user_id, $exam_key, $time_added, $ranks);
$stmt->execute();

// Select the required number of questions not yet answered by the user
$stmt = $mysqli->prepare("SELECT q.id, q.question, q.ans1, q.ans2, q.ans3, q.ans4, q.ans5, q.ans6, q.correct_ans, q.check_radio FROM exam_questions q LEFT JOIN question_answers a ON q.id = a.question_id AND a.user_id = ? WHERE a.question_id IS NULL ORDER BY RAND() LIMIT ?");
$stmt->bind_param("ii", $user_id, $num_questions);
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
