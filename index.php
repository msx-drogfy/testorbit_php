<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require "userinfo.php"; ?>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
      TestOrbit - Exam test platform
    </title>
    <link
      rel="stylesheet"
      href="style2.css"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css"
      rel="stylesheet"
    />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
  </head>
  <!-- bytewebster.com -->
  <!-- bytewebster.com -->
  <!-- bytewebster.com -->
  <body>
    <!-- Dashboard -->
    <div class="d-flex flex-column flex-lg-row h-lg-full bg-surface-secondary">
      <!-- Vertical Navbar -->
      <nav
        class="navbar show navbar-vertical h-lg-screen navbar-expand-lg px-0 py-3 navbar-light bg-white border-bottom border-bottom-lg-0 border-end-lg"
        id="navbarVertical"
      >
        <div class="container-fluid">
          <!-- Toggler -->
          <button
            class="navbar-toggler ms-n2"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#sidebarCollapse"
            aria-controls="sidebarCollapse"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <!-- Brand -->
          <a class="navbar-brand py-lg-2 mb-lg-5 px-lg-6 me-0" href="#">
            <h3 class="text-success">
              <img src="logo.png" width="30" style="margin-right: 10px;" /><span
                class="text-info"
                >TEST</span
              >ORBIT
            </h3>
          </a>
          <!-- User menu (mobile) -->
          <div class="navbar-user d-lg-none">
            <!-- Dropdown -->
            <div class="dropdown">
              <!-- Toggle -->
              <a
                href="#"
                id="sidebarAvatar"
                role="button"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <div class="avatar-parent-child">
                  <img
                    alt="Image Placeholder"
                    src="https://images.unsplash.com/photo-1548142813-c348350df52b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=3&w=256&h=256&q=80"
                    class="avatar avatar- rounded-circle"
                  />
                  <span class="avatar-child avatar-badge bg-success"></span>
                </div>
              </a>
              <!-- Menu -->
              <div
                class="dropdown-menu dropdown-menu-end"
                aria-labelledby="sidebarAvatar"
              >
                <a href="accounts.php" class="dropdown-item">Profile</a>
                <a href="#" class="dropdown-item">Settings</a>
                <hr class="dropdown-divider" />
                <a href="logout.php" class="dropdown-item">Logout</a>
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
                  <h1 class="h2 mb-0 ls-tight" id="greeting"><small>Loading greeting...</small></h1>

                    <script>
                        function updateGreeting() {
                            var now = new Date();
                            var hour = now.getHours();
                            var greeting;
                
                            if (hour >= 5 && hour < 12) {
                                greeting = "Good morning";
                            } else if (hour >= 12 && hour < 18) {
                                greeting = "Good afternoon";
                            } else if (hour >= 18 && hour < 22) {
                                greeting = "Good evening";
                            } else {
                                greeting = "Good evening";
                            }
                
                            // Assuming you have the username available
                            var username = "<?php echo $username; ?>";
                            document.getElementById('greeting').textContent = greeting + ', ' + username + '!';
                        }
                
                        // Update the greeting as soon as the page loads
                        updateGreeting();
                    </script>
                  </h1>
                </div>
                <div class="col-sm-6 col-12 text-sm-end mb-4">
                  <div class="mx-n1">
                    <a href="./account.php" class="btn d-inline-flex btn-sm btn-neutral border-base mx-1" title="Edit your personal profile">
                      <span class="pe-2">
                        <i class="bi bi-person"></i>
                      </span>
                      <span>Edit Profile</span>
                    </a>
                    <div class="btn-group mx-1">
                      <button type="button" class="btn d-inline-flex btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="pe-2">
                          <i class="bi bi-plus-circle"></i>
                        </span>
                        <span>New...</span>
                      </button>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="sorter.php">New Test Session</a></li>
                        <li><a class="dropdown-item" href="test.php">New Test Exams</a></li>
                      </ul>
                    </div>
                  </div>
                </div>

              </div>
              <!-- Nav -->
              <?php display_alert(); ?>
              
            </div>
          </div>
        </header>
        <!-- Main -->
        <main class="py-6 bg-surface-secondary">
          <div class="container-fluid">
            <!-- Card stats -->
            <?php
            // Assuming $mysqli is your MySQLi connection object and $userId is the user ID
            $userId = $user_id;/* User ID goes here */
            
            // Fetch the two most recent exams taken by the user
            $query = "SELECT * FROM exam_registration WHERE user_id = ? ORDER BY ID DESC LIMIT 2";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $exams = $result->fetch_all(MYSQLI_ASSOC);
            $exams = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM exam_registration WHERE user_id = '$user_id' ORDER BY ID DESC LIMIT 2"));
            // Initialize variables for the last and previous exams
            if (isset($exams)) {
              $lastExam = $exams[0];
              $previousExam = $exams[1];
            }else{
              $lastExam = 0;
              $previousExam = 0;
            }
            
            // Initialize scores
            $lastExamScore = 0;
            $previousExamScore = 0;
            
            // Calculate the score for the last exam
            if (isset($lastExam[3]) && isset($lastExam[4])) {
              $lastExamScore = intval(($lastExam[3] / $lastExam[4]) * 100);
            }
            
            // Calculate the score for the previous exam
            if (isset($previousExam[3]) && isset($previousExam[4])) {
              if($previousExam['4'] < 1){
                $st = 1;
              }else{
                $st = $previousExam['4'];
              }
              if($previousExam['3'] < 1){
                $se = 1;
              }else{
                $se = $previousExam['4'];
              }
              $previousExamScore = intval(($se / $st) * 100);
            }
            
            // Calculate the score difference
            $scoreDifference = $lastExamScore - $previousExamScore;
            $diffColor = ($scoreDifference > -1) ? 'success' : 'danger';
            $direction = ($scoreDifference > -1) ? 'up' : 'down';
            if ($previousExamScore < 1) {
              $previousExamScore = 1;
            }
            $score_rise = ($scoreDifference/$previousExamScore)*100;
            // Get the total number of exams taken by the user
            $totalExamsQuery = "SELECT COUNT(*) AS total_exams FROM exam_registration WHERE user_id = ?";
            $totalExamsStmt = $mysqli->prepare($totalExamsQuery);
            $totalExamsStmt->bind_param("i", $userId);
            $totalExamsStmt->execute();
            $totalExamsResult = $totalExamsStmt->get_result()->fetch_assoc();
            $totalExams = $totalExamsResult['total_exams'];
            
            // Get all scores for the average calculation
            // Assume calculateAverageScore is a function you have already defined in PHP
            $avgScore = calculateAverageScore($userId);
            
            // Calculate total exam time
            $totalTimeQuery = "SELECT SUM(duration) AS total_duration FROM exam_registration WHERE user_id = ?";
            $totalTimeStmt = $mysqli->prepare($totalTimeQuery);
            $totalTimeStmt->bind_param("i", $userId);
            $totalTimeStmt->execute();
            $totalTimeResult = $totalTimeStmt->get_result()->fetch_assoc();
            $totalTime = isset($totalTimeResult['total_duration']) ? round($totalTimeResult['total_duration'] / 60, 1) : 0.0;
            
            if ($previousExamScore != 0) {
                $scoreDifference = round(($scoreDifference / $previousExamScore) * 100);
            } else {
                $scoreDifference = 0; // or any other value that makes sense in your context
            }
            
            // Now you can use $lastExamScore, $previousExamScore, $scoreDifference, $diffColor, and $direction in your further logic
            ?>
            
            <div class="row g-6 mb-6">
              <div class="col-xl-3 col-sm-6 col-12">
                <div class="card shadow border-0">
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <span class="h6 font-semibold text-muted text-sm d-block mb-2">Recent Exam Score</span>
                        <?php  ?>
                        <span class="h3 font-bold mb-0"><?php echo $lastExamScore; ?>%</span>
                        
                      </div>
                      <div class="col-auto">
                        <div class="icon icon-shape bg-success text-white text-lg rounded-circle">
                          <i class="bi bi-file-earmark-text"></i>
                        </div>
                      </div>
                    </div>
                    
                    <div class="mt-2 mb-0 text-sm">
                      <span class="badge badge-pill bg-soft-success text-<?php echo $diffColor; ?> me-2">
                        <i class="bi bi-arrow-<?php echo $direction; ?> me-1"></i><?php echo $scoreDifference; ?>%
                      </span>
                      <span class="text-nowrap text-xs text-muted">Since last test</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 col-12">
                <div class="card shadow border-0">
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <span class="h6 font-semibold text-muted text-sm d-block mb-2">Tests Taken</span>
                        <span class="h3 font-bold mb-0"><?php echo $totalExams; ?></span>
                      </div>
                      <div class="col-auto">
                        <div class="icon icon-shape bg-primary text-white text-lg rounded-circle">
                          <i class="bi bi-file-earmark-text"></i>
                        </div>
                      </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                      <span class="text-nowrap text-xs text-muted">Total completed tests</span>
                    </div>
                  </div>
                </div>
              </div>
               <!-- Tests Taken -->
              <!-- Average Score -->
              <div class="col-xl-3 col-sm-6 col-12">
                <div class="card shadow border-0">
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <span class="h6 font-semibold text-muted text-sm d-block mb-2">Average Score</span>
                        <span class="h3 font-bold mb-0"><?php echo $avgScore; ?>%</span>
                      </div>
                      <div class="col-auto">
                        <div class="icon icon-shape bg-danger text-white text-lg rounded-circle">
                          <i class="bi bi-bar-chart-line"></i>
                        </div>
                      </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                      <span class="badge badge-pill bg-soft-success text-success me-2">
                        <i class="bi bi-arrow-up me-1"></i>5%
                      </span>
                      <span class="text-nowrap text-xs text-muted">Since last month</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Time Spent -->
              <div class="col-xl-3 col-sm-6 col-12">
                <div class="card shadow border-0">
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <span class="h6 font-semibold text-muted text-sm d-block mb-2">Total study time</span>
                        <span class="h3 font-bold mb-0"><?php echo $totalTime; ?> Hours</span>
                      </div>
                      <div class="col-auto">
                        <div class="icon icon-shape bg-info text-white text-lg rounded-circle">
                          <i class="bi bi-hourglass-split"></i>
                        </div>
                      </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                      <span class="badge badge-pill bg-soft-success text-success me-2">
                        <i class="bi bi-arrow-up me-1"></i>20%
                      </span>
                      <span class="text-nowrap text-xs text-muted">Since last week</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Tests Available -->
              <div class="col-xl-3 col-sm-6 col-12">
                <div class="card shadow border-0">
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <span class="h6 font-semibold text-muted text-sm d-block mb-2">Tests Available</span>
                        <span class="h3 font-bold mb-0">50</span>
                      </div>
                      <div class="col-auto">
                        <div class="icon icon-shape bg-danger text-white text-lg rounded-circle">
                          <i class="bi bi-journal-check"></i>
                        </div>
                      </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                      <span class="text-nowrap text-xs text-muted">Ready to take</span>
                    </div>
                  </div>
                </div>
              </div>
            


            
              <!-- Study Time -->
            </div>

            
            <div class="card shadow border-0 mb-7">
              <div class="card-header">
                <h5 class="mb-0">Tests Taken</h5>
              </div>
              <div class="table-responsive">
                <table class="table table-hover table-nowrap">
                  <thead class="thead-light">
                    <tr>
                      <th scope="col">##</th>
                      <th scope="col">Exam ID</th>
                      <th scope="col">Questions</th>
                      <th scope="col">Outcome</th>
                      <th scope="col">Duration</th>
                      <th scope="col">action</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $c=0;
                    $test = mysqli_query($conn, "SELECT * FROM exam_registration WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 20");
                    while ($exam = mysqli_fetch_array($test)) {
                      $c++;
                    ?>
                    <tr>
                        <td><?php echo $c; ?></td>
                        <td><?php echo $exam['exam_key']; ?></td>
                        <td><?php echo $exam['score']; ?> / <?php echo $exam['questions']; ?> </td>
                        <td><?php echo round(($exam['score']/$exam['questions'])*100); ?>%</td>
                        <td><?php echo $exam['duration']; ?> minutes</td>
                        <td >
                          <a href="results_page.php?exam_key=<?php echo $exam['exam_key']; ?>" class="btn btn-sm btn-neutral">View</a>
                          <?php if ($exam['group_key'] == "") { ?>
                          <a href="results_page.php?exam_key=&user_id="><button
                            type="button"
                            class="btn btn-sm btn-square btn-neutral text-danger-hover"
                          >
                            <i class="bi bi-trash"></i>
                          </button></a>
                          <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                   
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
      crossorigin="anonymous"
    ></script>

    <!-- Include your custom JavaScript file using url_for -->
    <script src="script2.js"></script>
  </body>
</html>
