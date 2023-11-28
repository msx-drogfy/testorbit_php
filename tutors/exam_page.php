<!DOCTYPE html>
<html lang="en">

<head>
  <?php require "userinfo.php";
  $exam_key = $_GET['key']; ?>
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
        <?php include("menu.php"); ?>
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
                <?php
                $exam_info = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) AS questions FROM exam_sets WHERE exam_key='$exam_key'"));
                $duration = $exam_info['questions'] * 2;
                $exam_info = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM exams WHERE exam_key = '$exam_key'"));
                $user_registration_info = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM exam_registration WHERE user_id = '$user_id' AND exam_key='$exam_key'"));
                $time_taken = round(($time - $user_registration_info['timestart']) / 60);
                $duration = $duration - $time_taken;
                ?>
                <div class="row">
                  <div class="col-sm-6 col-md-6 col-lg-6 col-6">
                    <h5>Exam key: <b><?php echo $exam_key; ?></b></h5>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-6 col-6 text-end">
                    <h5>Duration: <b><?php echo $duration; ?> Minutes</b></h5>
                  </div>
                </div>
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
              <form action="submit_exam.php" method="post">
                <input type="hidden" name="exam_key" value="<?php echo $exam_key; ?>">
                <?php
                $c = 0;
                $exam_questions = mysqli_query($conn, "SELECT * FROM exam_sets WHERE exam_key = '$exam_key'");
                while ($test = mysqli_fetch_array($exam_questions)) {
                  $c++;
                  $qid = $test['exam_question_key'];
                  $question = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM exam_questions WHERE id='$qid'")); ?>
                  <div class="card shadow border-0 mb-4">
                    <div class="card-body">
                      <div class="row">
                        <div class="col">
                          <span class="h3 font-bold mb-0"><?php echo $question['question']; ?></span>
                        </div>
                        <div class=" col-auto">
                          <div class="icon icon-shape bg-info text-white text-lg rounded-circle">
                            <b><small><?php echo $c; ?></small></b>
                          </div>
                        </div>
                        <?php if ($question['check_radio'] == 'radio') { ?>
                          <ul>
                            <label for="option_<?php echo $qid; ?>_1">
                              <li class="ans" style="<?php if (isset($_GET['check'])) {
                                                        if ($_GET['select'] == 1) {
                                                          if ($_GET['check'] == "pass") {
                                                            echo "background-color: orangered;";
                                                          } else {
                                                            echo "background-color: lime-green;";
                                                          }
                                                        }
                                                      } ?>">
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
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                <?php } ?>

                <button type="submit" class="btn btn-success mx-1" style="width: fit-content;">Submit Exam Test</button>
              </form>

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