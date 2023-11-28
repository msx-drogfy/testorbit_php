<!DOCTYPE html>
<html lang="en">

<head>
  <?php require "userinfo.php";
  $exam_key = $_GET['exam_key']; ?>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>
    TestOrbit - Exam test platform
  </title>
  <link rel="stylesheet" href="style2.css" />
  <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
</head>

<body>
  <!-- Dashboard -->
  <div class="d-flex flex-column flex-lg-row h-lg-full bg-surface-secondary">
    <!-- Vertical Navbar -->
    <nav class="navbar show navbar-vertical h-lg-screen navbar-expand-lg px-0 py-3 navbar-light bg-white border-bottom border-bottom-lg-0 border-end-lg" id="navbarVertical">
      <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler ms-n2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse" aria-controls="sidebarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand py-lg-2 mb-lg-5 px-lg-6 me-0" href="#">
          <h3 class="text-success">
            <img src="logo.png" width="40" /><span class="text-info">TEST</span>ORBIT
          </h3>
        </a>
        <!-- User menu (mobile) -->
        <div class="navbar-user d-lg-none">
          <!-- Dropdown -->
          <div class="dropdown">
            <!-- Toggle -->
            <a href="#" id="sidebarAvatar" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <div class="avatar-parent-child">
                <img alt="Image Placeholder" src="https://images.unsplash.com/photo-1548142813-c348350df52b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=3&w=256&h=256&q=80" class="avatar avatar- rounded-circle" />
                <span class="avatar-child avatar-badge bg-success"></span>
              </div>
            </a>
            <!-- Menu -->
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="sidebarAvatar">
              <a href="#" class="dropdown-item">Profile</a>
              <a href="#" class="dropdown-item">Settings</a>
              <a href="#" class="dropdown-item">Billing</a>
              <hr class="dropdown-divider" />
              <a href="#" class="dropdown-item">Logout</a>
            </div>
          </div>
        </div>
        <!-- Collapse -->
        <?php include('menu.php'); ?>
      </div>
    </nav>
    <!-- Main content -->
    <div class="h-screen flex-grow-1 overflow-y-lg-auto">
      <!-- Header -->
      <header class="bg-surface-primary border-bottom pt-6">
        <div class="container-fluid">
          <div class="mb-npx">
            <div class="row align-items-center">
              <div class="col-sm-6 col-12 mb-4 mb-sm-0">
                <!-- Title -->
                <h1 class="h2 mb-0 ls-tight">
                  <img src="logo.png" width="40" />
                  AWS Test Results
                </h1>
              </div>
              <!-- Actions -->
            </div>
          </div>
        </div>
      </header>
      <!-- Main -->
      <main class="py-6 bg-surface-secondary">
        <div class="container-fluid">
          <!-- Card stats -->

          <div class="row g-6 mb-6">
            <a href="./grouppage.php?key=<?php echo $_GET['group_key']; ?>"><button class="btn d-inline-flex btn-sm btn-primary mx-1"><span class="pe-2"></span>Back to group dashboard</button></a>
            <div class="col-12">
              <?php display_alert(); ?>

              <div class="card shadow border-0 mb-3">
                <div class="card-body">
                  <p>Group Exam</p>
                  <h3>


                    <span class="h3 font-bold mb-0" id="examkey"><b><?php echo $exam_key; ?></b></span>
                  </h3>
                  <br>
                  <p>
                    
                                                                    Exam attempts: <?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM exam_registration WHERE exam_key='$exam_key'")); ?> <br>
                                                                    Average Score: <?php echo calculateExamAverageScore($exam_key); ?>%
                                                                    <hr>
                                                                    <small>
                                                                      <?php $xr = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM exam_sets WHERE exam_key = '$exam_key'"));
                                                                      $exam = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM exams WHERE exam_key = '$exam_key'")); ?>
                                                                      Questions: <?php echo $xr; ?> <br>
                                                                      Topics tested: <?php echo $exam['exam_topics']; ?>
                                                                    </small>
                                                                  </p>
                                                                  
                                                                </div>
                                                              </div>
                                                              <hr>
                                                              
                                                              <?php
              $c = 0;
              $exam_questions = mysqli_query($conn, "SELECT * FROM exam_sets WHERE exam_key = '$exam_key'");
              while ($test = mysqli_fetch_array($exam_questions)) {
                $c++;
                $qid = $test['exam_question_key'];
                $question = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM exam_questions WHERE id='$qid'"));
              ?>
                <div class="card shadow border-0 mb-4">
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <h5 class="text-muted">Topic: <?php echo $question['topic']; ?></h5>
                        <span class="h3 font-bold mb-0 text-<?php echo $col; ?>"><?php echo $question['question']; ?></span>
                      </div>
                      <div class=" col-auto">
                        <div class="icon icon-shape bg-info text-white text-lg rounded-circle">
                          <b><small><?php echo $c; ?></small></b>
                        </div>
                      </div>
                      <?php if ($question['check_radio'] == 'radio') { ?>
                        <ul>
                          <label for="option_<?php echo $qid; ?>_1">
                            <li class="ans">
                              <input type="radio" id="option_<?php echo $qid; ?>_1" name="option_<?php echo $qid; ?>" value="1">
                              <?php echo $question['ans1']; ?>
                            </li>
                          </label>
                          <label for="option_<?php echo $qid; ?>_2">
                            <li class="ans">
                              <input type="radio" id="option_<?php echo $qid; ?>_2" name="option_<?php echo $qid; ?>" value="2">
                              <?php echo $question['ans2']; ?>
                            </li>
                          </label>
                          <label for="option_<?php echo $qid; ?>_3">
                            <li class="ans">
                              <input type="radio" id="option_<?php echo $qid; ?>_3" name="option_<?php echo $qid; ?>" value="3">
                              <?php echo $question['ans3']; ?>
                            </li>
                          </label>
                          <label for="option_<?php echo $qid; ?>_4">
                            <li class="ans">
                              <input type="radio" id="option_<?php echo $qid; ?>_4" name="option_<?php echo $qid; ?>" value="4">
                              <?php echo $question['ans4']; ?>
                            </li>
                          </label>
                        </ul>

                        <div class="row">
                          <div class="col-8">
                            <div class="alert alert-info alert-solid mb-3" role="alert" style="border-radius: 10px;">
                              <div class="d-flex align-items-center justify-content-start">
                                <span>Correct answer: Option <?php echo leterfy($question['correct_ans']); ?></span>
                              </div><!-- d-flex -->
                            </div>
                            <div class="row">
                              <div class="col-6">
                                <?php
                                $rst = mysqli_query($conn, "SELECT * FROM question_answers WHERE question_id = '$qid' AND exam_key='$exam_key' AND pass='yes'");
                                while ($pass = mysqli_fetch_array($rst)) {
                                ?><span class="badge badge-success"><?php echo get_user_info($pass['user_id'], "username") . " : " . leterfy($pass['chosen_answer']) . "<br/>"; ?></span><?php
                                                                                                                                                                                      }
                                                                                                                                                                                        ?>
                              </div>
                              <div class="col-6">
                                <div class="col-6">
                                  <?php
                                  $rst = mysqli_query($conn, "SELECT * FROM question_answers WHERE question_id = '$qid' AND exam_key='$exam_key' AND pass='no'");
                                  while ($pass = mysqli_fetch_array($rst)) {
                                  ?><span class="badge badge-danger"><?php echo get_user_info($pass['user_id'], "username") . " : " . leterfy($pass['chosen_answer']) . "<br/>"; ?></span><?php
                                                                                                                                                                                      }
                                                                                                                                                                                        ?>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-4 text-end">
                            <button type="button" class="btn btn-small btn-warning mx-1" onclick="showExplanation(<?php echo $question['id']; ?>, 'option')" style="width: fit-content;">Explain</button>

                          </div>
                        </div>
                        <div id="option_<?php echo $question['id']; ?>_explanation" class="hidden mt-4">
                        <style>
                          p{
                            margin-bottom: 10px;
                          }
                        </style>
                        <?php
                        $sx = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM explanations WHERE question_id = '$question[id]'"));
                        echo $sx['explanation'];
                        ?>
                        </div>
                      <?php } elseif ($question['check_radio'] == 'check') { ?>

                        <ul>
                          <label for="option_<?php echo $question['id']; ?>_1">
                            <li class="ans">
                              <input type="checkbox" id="option_<?php echo $question['id']; ?>_1" name="option_<?php echo $question['id']; ?>[]" value="1">
                              <?php echo $question['ans1']; ?>
                            </li>
                          </label>
                          <label for="option_<?php echo $question['id']; ?>_2">
                            <li class="ans">
                              <input type="checkbox" id="option_<?php echo $question['id']; ?>_2" name="option_<?php echo $question['id']; ?>[]" value="2">
                              <?php echo $question['ans2']; ?>
                            </li>
                          </label>
                          <label for="option_<?php echo $question['id']; ?>_3">
                            <li class="ans">
                              <input type="checkbox" id="option_<?php echo $question['id']; ?>_3" name="option_<?php echo $question['id']; ?>[]" value="3">
                              <?php echo $question['ans3']; ?>
                            </li>
                          </label>
                          <label for="option_<?php echo $question['id']; ?>_4">
                            <li class="ans">
                              <input type="checkbox" id="option_<?php echo $question['id']; ?>_4" name="option_<?php echo $question['id']; ?>[]" value="4">
                              <?php echo $question['ans4']; ?>
                            </li>
                          </label>
                          <label for="option_<?php echo $question['id']; ?>_5">
                            <li class="ans">
                              <input type="checkbox" id="option_<?php echo $question['id']; ?>_5" name="option_<?php echo $question['id']; ?>[]" value="5">
                              <?php echo $question['ans5']; ?>
                            </li>
                          </label>
                        </ul>
                        <div class="row">
                          <div class="col-8">

                            <div class="alert alert-info alert-solid mb-3" role="alert" style="border-radius: 10px;">
                              <div class="d-flex align-items-center justify-content-start">
                                <span>Correct answer: Option <?php echo leterfy($question['correct_ans']); ?></span>
                              </div><!-- d-flex -->
                            </div>
                            
                            <div class="row">
                              <div class="col-6">
                                <?php
                                $rst = mysqli_query($conn, "SELECT * FROM question_answers WHERE question_id = '$qid' AND exam_key='$exam_key' AND pass='yes'");
                                while ($pass = mysqli_fetch_array($rst)) {
                                ?><span class="badge badge-success"><?php echo get_user_info($pass['user_id'], "username") . " : " . leterfy($pass['chosen_answer']) . "<br/>"; ?></span><?php
                                                                                                                                                                                      }
                                                                                                                                                                                        ?>
                              </div>
                              <div class="col-6">
                                <div class="col-6">
                                  <?php
                                  $rst = mysqli_query($conn, "SELECT * FROM question_answers WHERE question_id = '$qid' AND exam_key='$exam_key' AND pass='no'");
                                  while ($pass = mysqli_fetch_array($rst)) {
                                  ?><span class="badge badge-danger"><?php echo get_user_info($pass['user_id'], "username") . " : " . leterfy($pass['chosen_answer']) . "<br/>"; ?></span><?php
                                                                                                                                                                                      }
                                                                                                                                                                                        ?>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-4">
                            <button type="button" class="btn btn-small btn-warning mx-1" onclick="showExplanation(<?php echo $question['id']; ?>, 'option')" style="width: fit-content;">Explain</button>

                          </div>
                        </div>
                        <div id="option_<?php echo $question['id']; ?>_explanation" class="hidden mt-4">
                        <style>
                          p{
                            margin-bottom: 10px;
                          }
                        </style>
                        <?php
                        $sx = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM explanations WHERE question_id = '$question[id]'"));
                        echo $sx['explanation'];
                        ?>
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              <?php }

              $exam = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM exams WHERE exam_key = '$exam_key'"));
              if ($exam['showparticipants'] == "yes" || ($exam['showparticipants'] == "only me" && $exam['creator'] == $user_id)) {
              ?>
                <hr>
                <div class="col-12 mb-3">
                  <div class="card shadow border-0">
                    <div class="card-body">
                      <h5 class="font-semibold text-muted mb-3">Exam participants</h5>
                      <ul class="list-group list-group-flush">
                        <?php
                        $participants = mysqli_query($conn, "SELECT * FROM exam_registration WHERE exam_key = '$exam_key' ORDER BY score/questions DESC");
                        while ($participant = mysqli_fetch_array($participants)) { ?>
                          <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo get_user_info($participant['user_id'], "username"); ?>
                            <span class="badge bg-primary rounded-pill"><?php echo round(($participant['score'] / $participant['questions']) * 100); ?>%</span>
                            <span class="badge bg-primary rounded-pill"><?php echo round(($participant['timeend'] - $participant['timestart']) / 60); ?> Mins</span>
                          </li>
                        <?php } ?>
                      </ul>
                    </div>
                  </div>
                </div>
              <?php }

              if ($exam['creator'] == $user_id) {
              ?>
                <hr>
                <div class="col-12 mb-3">
                  <div class="card shadow border-0">
                    <div class="card-body">

                      <form action="exam_setting.php" method="post">
                        <input type="hidden" name="exam_key" value="<?php echo $exam_key; ?>">
                        <span class="h3 font-bold mb-0">Change exam Settings
                        </span>
                        <label for="query">Do you want to show ranking of the participants</label>
                        <label for="showParticipants">
                          <li class="ans">
                            <input type="radio" id="showParticipants" name="ranks" value="yes">
                            Yes
                          </li>
                        </label>
                        <style>
                          .ans2:hover {
                            background-color: orangered;
                            box-shadow: 0 0 5px 0 gray;
                            cursor: pointer;
                          }
                        </style>
                        <label for="rank2">
                          <li class="ans ans2">
                            <input type="radio" id="rank2" name="ranks" value="no">
                            No
                          </li>
                        </label>
                        <style>
                          .ans3:hover {
                            background-color: cornflowerblue;
                            box-shadow: 0 0 5px 0 gray;
                            cursor: pointer;
                          }
                        </style>
                        <label for="rank3">
                          <li class="ans ans3">
                            <input type="radio" id="rank3" name="ranks" value="only me">
                            Only Me
                          </li>
                        </label>
                        <style>
                          #numQuestions {
                            border: 1px solid grey;
                            padding: 5px;
                            padding-left: 10px;
                            border-radius: 15px;
                            width: 100%;
                            margin: 5px;
                          }
                        </style>

                        <br>
                        <input type="submit" class="btn d-inline-flex btn-sm btn-warning text-white mx-1" value="Update">
                      </form>
                    </div>
                  </div>
                </div>
              <?php } ?>

            </div>
          </div>
        </div>
    </div>
  </div>
  </main>
  </div>
  </div>

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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

  <!-- Include your custom JavaScript file using url_for -->
  <script src="script2.js"></script>
</body>

</html>