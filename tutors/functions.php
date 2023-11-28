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
    global $mysqli;
    // Connect to the database
    // Assuming $mysqli is your MySQLi connection object
    $query = "SELECT COUNT(*) AS fls FROM question_answers WHERE user_id = ? AND exam_key = ? AND pass = 'yes'";

        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("ii", $user_id, $exam_key);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return intval($result['fls']);
        }
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

function get_admin_info($id, $requirement)
{
    global $conn;
    $x = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tutors WHERE id = '$id'"));
    return $x[$requirement];
}

function calculate_percentage_Score($exam_key, $user_id)
{
    global $conn;
    // Connect to the database
    $query = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM exam_registration WHERE user_id = '$user_id' AND exam_key = '$exam_key'"));
    if (isset($query['ID'])) {
        $mc = round(($query['score']/$query['questions'])*100)."%";
    }else {
        $mc = "n/a";
    }
    return $mc;
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
        return round($average_score,2);
    }
    $mysqli->close();
}

function calculateExamAverageScore($exam_key)
{
    global $mysqli, $conn;
    // Connect to the database
    // Assuming $mysqli is your MySQLi connection object
    $query = "SELECT score, questions FROM exam_registration WHERE exam_key = ?";
    $query2 = mysqli_query($conn, "SELECT score, questions FROM exam_registration WHERE exam_key = '$exam_key'");
    $scores = array();
    while($scc = mysqli_fetch_array($query2)){
        $psd = round(($scc['score'] / $scc['questions']) * 100);
        array_push($scores, $psd);
    }
    
    $average_score = count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : 0;
    return round($average_score,2);
}

function calculateGroupMeanAverage($group_key) {
    global $mysqli;
    // Assuming $mysqli is your MySQLi connection object

    // Query to get all users in a group
    $userQuery = "SELECT DISTINCT user_id FROM exam_registration WHERE group_key = ?";

    if ($userStmt = $mysqli->prepare($userQuery)) {
        $userStmt->bind_param("s", $group_key);
        $userStmt->execute();
        $userResult = $userStmt->get_result();
        $userAverages = array();

        while ($user = $userResult->fetch_assoc()) {
            $userId = $user['user_id'];

            // Query to get the average score for each user
            $scoreQuery = "SELECT score, questions FROM exam_registration WHERE user_id = ? AND group_key = ?";
            if ($scoreStmt = $mysqli->prepare($scoreQuery)) {
                $scoreStmt->bind_param("is", $userId, $group_key);
                $scoreStmt->execute();
                $scoreResult = $scoreStmt->get_result();

                $scores = array();
                while ($exam = $scoreResult->fetch_assoc()) {
                    if ($exam['score'] !== null && $exam['questions'] !== null && $exam['questions'] != 0) {
                        $score_percentage = ($exam['score'] / $exam['questions']) * 100;
                        array_push($scores, $score_percentage);
                    }
                }

                if (count($scores) > 0) {
                    $userAverage = array_sum($scores) / count($scores);
                    array_push($userAverages, $userAverage);
                }
            }
        }

        $groupMeanAverage = count($userAverages) > 0 ? array_sum($userAverages) / count($userAverages) : 0;
        $userStmt->close();
        return round($groupMeanAverage, 2);
    }
    $mysqli->close();
}

if (isset($_SESSION['notification'])) {
    $message = $_SESSION['notification']['message'];
    $type = $_SESSION['notification']['type'];

    // Use the notify function
    notify($message, $type);

    // Unset the notification
    unset($_SESSION['notification']);
}

function get_exam_info($exam_key, $requirement)
{
    global $conn;
    $x = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM exams WHERE exam_key = '$exam_key'"));
    return $x[$requirement];
}

function get_user_exam_info($user_id, $exam_key){

    global $conn;
    $x = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM exam_registration WHERE exam_key = '$exam_key' AND user_id = '$user_id'"));
    $score = round(($x['score']/$x['questions'])*100);
    $sc = "$x[score]/$x[questions] ($score %)";
    return $sc;
}

function get_admin_student_info(){
    global $conn;
    $x = mysqli_query($conn, "SELECT * FROM members WHERE role = 'student'");
    $count = mysqli_num_rows($x);
    return $count;

}

function getDistinctTopics($examKey, $mysqli) {
    // SQL query to fetch distinct topics for a given exam
    $query = "SELECT DISTINCT topic FROM exam_questions 
              INNER JOIN exam_sets ON exam_questions.id = exam_sets.exam_question_key 
              WHERE exam_sets.exam_key = ?";

    // Prepare and bind parameters
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $examKey);

        // Execute the query
        $stmt->execute();

        // Bind the result variables
        // Fetch and print the results
        $topics = array();
        $topic = null; // Declare the $topic variable
        while ($stmt->fetch()) {
            array_push($topics, $topic);
        }


        // Close the statement
        $stmt->close();

        return $topics;

        // Fetch and print the results
        $topics = array();
        while ($stmt->fetch()) {
            array_push($topics, $topic);
        }

        // Close the statement
        $stmt->close();

        return $topics;
    } else {
        // Handle errors
        return "Error: " . $mysqli->error;
    }
}



?>

<script>
        // Define some JavaScript functions for the submit and explanation buttons
        function submitAnswer(id, option) {
            // Get the selected choice and the correct answer
            var choice = document.querySelector(`input[name=${option}_${id}]:checked`).value;
            var answer = document.getElementById(`${option}_${id}_answer`).value;

            // Compare the choice and the answer and show an alert message
            if (choice == answer) {
                alert("Correct!");
            } else {
                alert("Wrong!");
            }
        }

        function showExplanation(id, option) {
            // Get the explanation element and toggle its visibility
            var explanation = document.getElementById(`${option}_${id}_explanation`);
            if (explanation.classList.contains("hidden")) {
                explanation.classList.remove("hidden");
            } else {
                explanation.classList.add("hidden");
            }
        }
    </script>