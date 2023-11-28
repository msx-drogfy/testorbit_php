<?php
require "userinfo.php"; 

$exam_key = $_POST['exam_key'];



    // Fetch the questions for the given exam key from exam_sets
    $stmt = $mysqli->prepare("SELECT * FROM exam_sets WHERE exam_key = ?");
    $stmt->bind_param('s', $exam_key);
    $stmt->execute();
    $result = $stmt->get_result();
    $exam_questions_keys = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($exam_questions_keys as $question_key_dict) {
        $exam_question_key = $question_key_dict['exam_question_key'];

        // Fetch question details
        $stmt = $mysqli->prepare("SELECT * FROM exam_questions WHERE id = ?");
        $stmt->bind_param('s', $exam_question_key);
        $stmt->execute();
        $question = $stmt->get_result()->fetch_assoc();

        if ($question != null) {
            $question_id = $question['id'];
            $check_radio = $question['check_radio'];
            $correct_answer = str_replace(" ", "", $question['correct_ans']); // Normalize the correct answer

            if ($check_radio == 'radio') {
                $user_answer_key = 'option_' . $question_id;
                $user_answer = $_POST[$user_answer_key];
                $is_correct = ($user_answer == $correct_answer);
            } elseif ($check_radio == 'check') {
                // Handling checkbox inputs
                $user_answers = $_POST['option_' . $question_id];
                $user_answer = implode(',', $user_answers); // Concatenate user answers
                $is_correct = ($user_answer == $correct_answer);
            }

            // Fetch user ID
            $stmt = $mysqli->prepare("SELECT ID FROM members WHERE username = ? OR email = ?");
            $stmt->bind_param('ss', $username, $username);
            $stmt->execute();
            $user_id_result = $stmt->get_result()->fetch_assoc();
            $user_id = $user_id_result['ID'] ?? null;

            // Check existing answer
            $stmt = $mysqli->prepare("SELECT * FROM question_answers WHERE user_id = ? AND question_id = ? AND exam_key = ? AND isexam = 'yes'");
            $stmt->bind_param('sss', $user_id, $question_id, $exam_key);
            $stmt->execute();
            $existing_answer = $stmt->get_result()->fetch_assoc();

            $pass_status = $is_correct ? 'yes' : 'no';
            $timestamp = time();

            if ($existing_answer) {
                // Update existing answer
                $stmt = $mysqli->prepare("UPDATE question_answers SET chosen_answer = ?, pass = ?, timestamp = ? WHERE user_id = ? AND question_id = ? AND exam_key = ? AND isexam = 'yes'");
                $stmt->bind_param('ssssss', $user_answer, $pass_status, $timestamp, $user_id, $question_id, $exam_key);
            } else {
                // Insert new answer
                $stmt = $mysqli->prepare("INSERT INTO question_answers (user_id, question_id, chosen_answer, timestamp, exam_key, pass, isexam) VALUES (?, ?, ?, ?, ?, ?, 'yes')");
                $stmt->bind_param('ssssss', $user_id, $question_id, $user_answer, $timestamp, $exam_key, $pass_status);
            }
            $stmt->execute();
        }
    }

    // Calculate score and update exam registration
    $current_time = $time;
    $stmt = $mysqli->prepare('SELECT timestart FROM exam_registration WHERE user_id = ? AND exam_key = ?');
    $stmt->bind_param('ss', $user_id, $exam_key);
    $stmt->execute();
    $time_start = $stmt->get_result()->fetch_assoc();

    $exam_duration = round(($current_time - $time_start['timestart']) / 60);
    $score = calculateScore($exam_key, $user_id); // Assuming this function is defined

    // Update exam registration with score and time_end
    $stmt = $mysqli->prepare('UPDATE exam_registration SET score = ?, timeend = ?, duration = ? WHERE user_id = ? AND exam_key = ?');
    $stmt->bind_param('sssss', $score, $current_time, $exam_duration, $user_id, $exam_key);
    $stmt->execute();
    
    // Redirect to results page or display message
    header("Location: results_page.php?exam_key=$exam_key&time_taken=$exam_duration&score=$score");

?>
