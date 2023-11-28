<!DOCTYPE html>
<html lang="en">

<head>
  <?php require "userinfo.php"; ?>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>
    TestOrbit - Exam test platform
  </title>
  <link rel="stylesheet" href="style2.css" />
  <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap4-theme/1.5.2/select2-bootstrap4.min.css" rel="stylesheet" />
  <!-- Additional CSS for custom checkbox styling -->
  <style>
    .select2-container--bootstrap4 .select2-results__option[aria-selected=true] {
      background-color: #fff !important;
    }

    .select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected] {
      background-color: #e9ecef !important;
      color: #495057;
    }
  </style>
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
        <?php include "menu.php"; ?>
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
                  AWS Test Questions
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
            <div class="col-12">

              <?php display_alert(); ?>

              <div class="card shadow border-0 mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <span class="h3 font-bold mb-0">Take questions test
                      </span>
                      <p>Practice on test questions one at a time</p>
                      <a href="sorter.php" class="btn d-inline-flex btn-sm btn-primary mx-1">
                        <span class="pe-2">
                          <i class="bi bi-plus"></i>
                        </span>
                        <span>Start</span>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card shadow border-0 mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <form action="exam_creator.php" method="post">

                        <span class="h3 font-bold mb-0">Create & Take test exam
                        </span>
                        <label for="query"><b>Enter the number of questions to be tested</b></label>
                        <select name="number_of_questions" id="numQuestions">
                          <option value="5">Beginner's Brief - 5 Questions</option>
                          <option value="10">Novice's Nudge - 10 Questions</option>
                          <option value="20">Intermediate Inquiry - 20 Questions</option>
                          <option value="30">Advanced Assessment - 30 Questions</option>
                          <option value="50">Expert Examination - 50 Questions</option>
                          <option value="65">Master's Challenge - 65 Questions</option>
                        </select>
                        <br>
                        <label for="query"><b>Select the topics to be tested on</b></label>
                        <?php
                        $qs = mysqli_query($conn, "SELECT DISTINCT topic FROM exam_questions");
                        while ($topic = mysqli_fetch_array($qs)) {
                          $nnn = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM exam_questions WHERE topic='$topic[topic]'"));
                          if ($nnn > 15) {
                        ?><br>
                            <label for="<?php echo $topic['topic']; ?>" class="ans">
                              <input type="checkbox" name="topics[]" id="<?php echo $topic['topic']; ?>" value="<?php echo $topic['topic']; ?>" style="margin-right: 5px;"><?php echo $topic['topic'] . "($nnn)"; ?>
                            </label><?php }
                                } ?>
                        <br>
                        <label for="query"><b>Do you want to show ranking of the participants</b></label>
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
                        <input type="submit" class="btn d-inline-flex btn-sm btn-warning text-white mx-1" value="Create">
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card shadow border-0 mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <span class="h3 font-bold mb-0">Join & Take test exam using key
                      </span>
                      <form action="examiner2.php" method="post">

                        <label for="query">Enter exam key</label>
                        <input type="text" name="exam_key" id="nums" placeholder="AAA-BBBB">
                        <style>
                          #nums {
                            border: 1px solid grey;
                            padding: 5px;
                            padding-left: 10px;
                            border-radius: 15px;
                            width: 100%;
                            margin: 5px;
                          }
                        </style>

                        <span></span>
                        </a>
                        <button class="btn d-inline-flex btn-sm btn-primary mx-1"><span class="pe-2">
                            <i class="bi bi-plus"></i>
                          </span>Start Test</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card shadow border-0 mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <span class="h3 font-bold mb-0">Previous Answered Questions (Revision)
                      </span>
                      <br>

                      <?php
                      $c = 0;
                      $exam_questions = mysqli_query($conn, "SELECT * FROM question_answers WHERE user_id = '$user_id' AND isexam != 'yes' ORDER BY id DESC LIMIT 20 ");
                      while ($test = mysqli_fetch_array($exam_questions)) {
                        $c++;
                        $qid = $test['question_id'];
                        $question = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM exam_questions WHERE id='$qid'"));
                        $usx = $test;
                        if ($question['correct_ans'] === $usx['chosen_answer']) {
                          $col = "success";
                        } else {
                          $col = "danger";
                        }
                      ?>
                        <div class="card shadow border-0 mb-4">
                          <div class="card-body">
                            <div class="row">
                              <div class="col">
                                <span class="h3 font-bold mb-0  text-<?php echo $col; ?>"><?php echo $question['question']; ?></span>
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

                                    <div class="alert alert-<?php echo $col; ?> alert-solid mb-3" role="alert" style="border-radius: 10px;">
                                      <div class="d-flex align-items-center justify-content-start">
                                        <span>Your choices: Option <?php echo leterfy($usx['chosen_answer']); ?></span>
                                      </div><!-- d-flex -->
                                    </div>
                                    <div class="alert alert-info alert-solid mb-3" role="alert" style="border-radius: 10px;">
                                      <div class="d-flex align-items-center justify-content-start">
                                        <span>Correct answer: Option <?php echo leterfy($question['correct_ans']); ?></span>
                                      </div><!-- d-flex -->
                                    </div>
                                  </div>
                                  <div class="col-4">
                                    <button type="button" class="btn btn-small btn-warning mx-1" onclick="showExplanation(<?php echo $question['id']; ?>, 'option')" style="width: fit-content;">Explain</button>

                                  </div>
                                </div>
                                <div id="option_<?php echo $question['id']; ?>_explanation" class="hidden mt-4"><?php echo $question['explanation']; ?></div>
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

                                    <div class="alert alert-<?php echo $col; ?> alert-solid mb-3" role="alert" style="border-radius: 10px;">
                                      <div class="d-flex align-items-center justify-content-start">
                                        <span>Your choices: Option <?php echo leterfy($usx['chosen_answer']); ?></span>
                                      </div><!-- d-flex -->
                                    </div>
                                    <div class="alert alert-info alert-solid mb-3" role="alert" style="border-radius: 10px;">
                                      <div class="d-flex align-items-center justify-content-start">
                                        <span>Correct answer: Option <?php echo leterfy($question['correct_ans']); ?></span>
                                      </div><!-- d-flex -->
                                    </div>
                                  </div>
                                  <div class="col-4">
                                    <button type="button" class="btn btn-small btn-warning mx-1" onclick="showExplanation(<?php echo $question['id']; ?>, 'option')" style="width: fit-content;">Explain</button>

                                  </div>
                                </div>
                                <div id="option_<?php echo $question['id']; ?>_explanation" class="hidden mt-4">
                                  <style>
                                    p {
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
                      <?php } ?>

                    </div>
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