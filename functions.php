<?php
require "datacon.php";

function questionType($question_id)
{
    global $mysqli;
    // Connect to the database
    // Assuming $mysqli is your MySQLi connection object
    $query = "SELECT check_radio FROM exam_questions WHERE id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $mysqli->close();

        if ($result) {
            return $result['check_radio'];
        } else {
            return null;
        }
    }
}

function leterfy($input_string)
{
    $mapping = array(
        '1' => 'A',
        '2' => 'B',
        '3' => 'C',
        '4' => 'D',
        '5' => 'E',
        '6' => 'F'
    );

    foreach ($mapping as $number => $letter) {
        $input_string = str_replace($number, $letter, $input_string);
    }

    return $input_string;
}

function leterfy_array($input_array)
{
    $mapping = array(
        '1' => 'A',
        '2' => 'B',
        '3' => 'C',
        '4' => 'D',
        '5' => 'E',
        '6' => 'F'
    );

    $result_array = array();

    foreach ($input_array as $input_string) {
        foreach ($mapping as $number => $letter) {
            $input_string = str_replace($number, $letter, $input_string);
        }
        $result_array[] = $input_string;
    }

    return $result_array;
}


function estimateExamDuration($question_count, $time_per_question = 2)
{
    $total_time = $question_count * $time_per_question;
    return $total_time;
}

function calculateScore($exam_key, $user_id)
{
    global $conn;
    // Connect to the database
    return $query = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM question_answers WHERE user_id = '$user_id' AND exam_key = '$exam_key' AND pass = 'yes'"));
}

function calculateAverageScore($user_id)
{
    global $mysqli;
    // Connect to the database
    // Assuming $mysqli is your MySQLi connection object
    $query = "SELECT score, questions FROM exam_registration WHERE user_id = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $scores = array();

        while ($exam = $result->fetch_assoc()) {
            if ($exam['score'] !== null && $exam['questions'] !== null && $exam['questions'] != 0) {
                $score_percentage = round(($exam['score'] / $exam['questions']) * 100);
                array_push($scores, $score_percentage);
            } else {
                array_push($scores, 0);
            }
        }

        $average_score = count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : 0;
        $stmt->close();
        return $average_score;
    }
    $mysqli->close();
}

function calculateGroupAverageScore($user_id, $group_key)
{
    global $mysqli;
    // Connect to the database
    // Assuming $mysqli is your MySQLi connection object
    $query = "SELECT score, questions FROM exam_registration WHERE user_id = ? AND group_key = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("is", $user_id, $group_key);
        $stmt->execute();
        $result = $stmt->get_result();
        $scores = array();

        while ($exam = $result->fetch_assoc()) {
            if ($exam['score'] !== null && $exam['questions'] !== null && $exam['questions'] != 0) {
                $score_percentage = round(($exam['score'] / $exam['questions']) * 100);
                array_push($scores, $score_percentage);
            } else {
                array_push($scores, 0);
            }
        }

        $average_score = count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : 0;
        $stmt->close();
        return $average_score;
    }
    $mysqli->close();
}


function display_alert()
{
    $message = "";
    $type = "info";
    if (isset($_SESSION['suc'])) {
        $type = "success";
        $message = $_SESSION['suc'];
?>
        <div class="alert alert-<?php echo $type; ?> alert-solid mb-3" role="alert" style="border-radius: 10px;">
            <div class="d-flex align-items-center justify-content-start">
                <span><small><?php echo $message; ?></small></span>
            </div><!-- d-flex -->
        </div>
    <?php
    } elseif (isset($_SESSION['warn'])) {
        $type = "warning";
        $message = $_SESSION['warn'];
    ?>
        <div class="alert alert-<?php echo $type; ?> alert-solid mb-3" role="alert" style="border-radius: 10px;">
            <div class="d-flex align-items-center justify-content-start">
                <span><small><?php echo $message; ?></small></span>
            </div><!-- d-flex -->
        </div>
    <?php
    } elseif (isset($_SESSION['info'])) {
        $type = "info";
        $message = $_SESSION['info'];
    ?>
        <div class="alert alert-<?php echo $type; ?> alert-solid mb-3" role="alert" style="border-radius: 10px;">
            <div class="d-flex align-items-center justify-content-start">
                <span><small><?php echo $message; ?></small></span>
            </div><!-- d-flex -->
        </div>
    <?php
    } elseif (isset($_SESSION['err'])) {
        $type = "danger";
        $message = $_SESSION['err'];
    ?>
        <div class="alert alert-<?php echo $type; ?> alert-solid mb-3" role="alert" style="border-radius: 10px;">
            <div class="d-flex align-items-center justify-content-start">
                <span><small><?php echo $message; ?></small></span>
            </div><!-- d-flex -->
        </div>
<?php
    }
    unset($_SESSION['suc']);
    unset($_SESSION['warn']);
    unset($_SESSION['info']);
    unset($_SESSION['err']);
}
function notify($message, $type)
{
    if ($type == "success") {
        $_SESSION['suc'] = $message;
    } elseif ($type == "warning") {
        $_SESSION['warn'] = $message;
    } elseif ($type == "info") {
        $_SESSION['info'] = $message;
    } elseif ($type == "danger") {
        $_SESSION['err'] = $message;
    }
}

function get_user_info($id, $requirement)
{
    global $conn;
    $x = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM members WHERE id = '$id'"));
    return $x[$requirement];
}
function get_exam_info($exam_key, $requirement)
{
    global $conn;
    $x = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM exams WHERE exam_key = '$exam_key'"));
    return $x[$requirement];
}

function calculateImprovementRate($initialScoreAverage, $initialCorrectAnswers, $currentScoreAverage, $currentCorrectAnswers) {
    // Calculate the improvement in score average
    $scoreImprovement = 0;
    if ($initialScoreAverage > 0) {
        $scoreImprovement = (($currentScoreAverage - $initialScoreAverage) / $initialScoreAverage) * 100;
    }

    // Calculate the improvement in the number of correct answers
    $answersImprovement = 0;
    if ($initialCorrectAnswers > 0) {
        $answersImprovement = (($currentCorrectAnswers - $initialCorrectAnswers) / $initialCorrectAnswers) * 100;
    }

    // Returning the results as an associative array
    return array(
        "scoreImprovement" => $scoreImprovement,
        "answersImprovement" => $answersImprovement
    );
}
