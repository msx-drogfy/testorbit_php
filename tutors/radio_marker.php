<?php
require "userinfo.php"; // Start the session

    $questionId = $_POST['question_id'];
    $userAnswerKey = 'option_' . $questionId;
    $userAnswer = isset($_POST[$userAnswerKey]) ? $_POST[$userAnswerKey] : null;
    $userId = $_SESSION['tutor_user_id']; // Assuming the user's ID is stored in the session

    // Fetch the correct answer and other question details from the database
    $query = "SELECT correct_ans FROM exam_questions WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $questionId);
    $stmt->execute();
    $result = $stmt->get_result();
    $question = $result->fetch_assoc();
    
    // Redirect or display a message based on whether the answer was correct
    $option = leterfy($userAnswer);
    
    echo "$userAnswer == $question[correct_ans]";
    $ans1 = $userAnswer;
    $ans2 = $question['correct_ans'];
    if ($ans1 == $ans2) {
        // Record the user's answer in the database
        $timestamp = $time;
        $insertQuery = "INSERT INTO question_answers (user_id, question_id, chosen_answer, timestamp) VALUES (?, ?, ?, ?)";
        $insertStmt = $mysqli->prepare($insertQuery);
        $insertStmt->bind_param("iiii", $userId, $questionId, $userAnswer, $timestamp);
        $insertStmt->execute();
        notify("Option $option is correct.", "success");
        header("Location: exam_radio.php?q=$questionId&check=pass&select=$userAnswer"); // Redirect to the next question
        echo "Correct answer";
    }else{
        notify("Option $option is not correct.", "warning");
        header("Location: exam_radio.php?q=$questionId"); // Redirect to retry the same question
    }
