<?php
require "userinfo.php";

// SQL query to fetch the question
$query = "
    SELECT q.id, q.question, q.ans1, q.ans2, q.ans3, q.ans4, q.ans5, q.ans6, q.correct_ans, q.explanation, q.check_radio
    FROM exam_questions q
    LEFT JOIN question_answers a ON q.id = a.question_id AND a.user_id = ?
    WHERE a.question_id IS NULL
    ORDER BY RAND()
    LIMIT 1
";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$questions_1 = [];
$type = "";

if ($result->num_rows > 0) {
    while ($q = $result->fetch_assoc()) {
        $question = [
            'id' => $q['id'],
            'question' => $q['question'],
            'choices' => array_filter([$q['ans1'], $q['ans2'], $q['ans3'], $q['ans4'], $q['ans5'], $q['ans6']]),
            'answer' => $q['correct_ans'],
            'explanation' => $q['explanation']
        ];

        $type = $q['check_radio'];
        $question_id = $q['id'];

        // Query to check if the user has answered this question
        $answerQuery = "
            SELECT chosen_answer
            FROM question_answers
            WHERE user_id = ? AND question_id = ?
        ";

        $answerStmt = $mysqli->prepare($answerQuery);
        $answerStmt->bind_param("ii", $user_id, $q['id']);
        $answerStmt->execute();
        $answerResult = $answerStmt->get_result()->fetch_assoc();

        $hasAnswered = $answerResult !== null;
        $chosenAnswer = $hasAnswered ? $answerResult['chosen_answer'] : null;
        $isCorrect = "yes";

        $question['has_answered'] = $hasAnswered;
        $question['chosen_answer'] = $chosenAnswer;
        $question['is_correct'] = $isCorrect;

        $questions_1[] = $question;
    }

    // Set question_id in session
    $_SESSION['question_id'] = $questions_1[0]['id'];
}

// Close the statement
$stmt->close();

// Decide which template to render based on the question type
if ($type == "radio") {
    // Render the radio template with the questions
    header("Location: exam_radio.php?q=$question_id"); // Replace with your actual template file
} else {
    // Render the checkbox template with the questions
    header("Location: exam_checkbox.php?q=$question_id"); // Replace with your actual template file
}
?>
