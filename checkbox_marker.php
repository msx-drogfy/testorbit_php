<?php
require "userinfo.php";

$questionId = $_POST['question_id'] ?? null;
$trials = $_POST['trials'] + 1;
if ($questionId) {
    // Fetch the question
    $query = "SELECT id, question, ans1, ans2, ans3, ans4, ans5, ans6, correct_ans, explanation FROM exam_questions WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $questionId); 
    $stmt->execute();
    $result = $stmt->get_result();
    $question = $result->fetch_assoc();

    if ($question) {
        $choices = array_filter([$question['ans1'], $question['ans2'], $question['ans3'], $question['ans4'], $question['ans5'], $question['ans6']]);
        $correctAnswer = str_replace(" ", "", $question['correct_ans']);

        $userAnswers = $_POST['option_' . $questionId];
        echo $userAnswerStr = implode(',', $userAnswers);


        // Fetch user ID
        $userIdQuery = "SELECT ID FROM members WHERE username = ? OR email = ?";
        $userIdStmt = $mysqli->prepare($userIdQuery);
        $userIdStmt->bind_param("ss", $username, $username);
        $userIdStmt->execute();
        $userIdResult = $userIdStmt->get_result()->fetch_assoc();
        $userId = $userIdResult['ID'] ?? null;

        $isCorrect = $userAnswerStr === $correctAnswer;

        if ($isCorrect) {
            // Check if the user has already answered
            $checkAnswerQuery = "SELECT chosen_answer FROM question_answers WHERE user_id = ? AND question_id = ?";
            $checkAnswerStmt = $mysqli->prepare($checkAnswerQuery);
            $checkAnswerStmt->bind_param("ii", $userId, $questionId);
            $checkAnswerStmt->execute();
            $answerResult = $checkAnswerStmt->get_result()->fetch_assoc();

            if (!$answerResult) {
                // Insert the answer as it's correct and not answered before
                $timestamp = time();
                $insertQuery = "INSERT INTO question_answers (user_id, question_id, chosen_answer, timestamp) VALUES (?, ?, ?, ?)";
                $insertStmt = $mysqli->prepare($insertQuery);
                $insertStmt->bind_param("iiii", $userId, $questionId, $userAnswerStr, $timestamp);
                $insertStmt->execute();
            }

            notify("Option $userAnswerStr are the Correct answers!.", "success");
            header("Location: exam_checkbox.php?q=$questionId&check=pass&select=$userAnswer&trials=$trials"); // Redirect to the next question
        } else {
            notify("Option $userAnswerStr are not correct. Please try again.", "warning");
            header("Location: exam_checkbox.php?q=$questionId&trials=$trials"); // Redirect to retry the same question
        }
    }
    $stmt->close();
}
$mysqli->close();
?>
