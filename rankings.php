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
              <img src="logo.png" width="40" /><span
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
                    <img
                      src="logo.png"
                      width="40"
                    />
                    Rankings
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
                  <h5 class="card-title">Your Performance Metrics</h5>
                  <div class="metrics">
                    <!-- <div class="metric">
                      <span class="metric-title">Overall Rank:</span>
                      <span class="metric-value">#5</span>
                    </div> -->
                    <div class="metric">                      
                      <?php $avgScore = calculateAverageScore($user_id); ?>
                      <span class="metric-title">Score Average:</span>
                      <span class="metric-value"><?php echo $avgScore; ?>%</span>
                    </div>
                    <div class="metric">
                      <span class="metric-title">Tests Taken:</span>
                      <?php $tests = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM exam_registration WHERE user_id = '$user_id'")); ?>
                      <span class="metric-value"><?php echo $tests; ?></span>
                    </div>
                    <div class="metric">
                      <span class="metric-title">Correct Answers:</span>
                      <?php $answers = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM question_answers WHERE user_id = '$user_id' AND pass='yes'")); ?>
                      <span class="metric-value"><?php echo $answers; ?></span>
                    </div>
                    <?php
                    $initialScoreAverage = 1; // 85%
                    $initialCorrectAnswers = 1;
                    $currentScoreAverage = $avgScore; // 92%
                    $currentCorrectAnswers = $answers;
                    
                    $improvementRates = calculateImprovementRate($initialScoreAverage, $initialCorrectAnswers, $currentScoreAverage, $currentCorrectAnswers);
                    
                    $score_improvements = $improvementRates["scoreImprovement"];
                    $ans_improvement = $improvementRates["answersImprovement"];
                    
                    ?>
                    <div class="metric">
                      <span class="metric-title">Score Improvement:</span>
                      <span class="metric-value">+<?php echo number_format($score_improvements); ?>%</span>
                    </div>
                    <div class="metric">
                      <span class="metric-title">Correct Answers Improvement:</span>
                      <span class="metric-value">+<?php echo number_format($ans_improvement); ?>%</span>
                    </div>
                    <div class="metric">
                      <span class="metric-title">Improvement Rate:</span>
                      <span class="metric-value">+<?php echo number_format(($ans_improvement+$score_improvements)/2); ?>%</span>
                    </div>
                  </div>
                </div>
              </div>
              <style>
              
                .metrics {
                  display: flex;
                  flex-direction: column;
                  align-items: start;
                  padding-left: 1rem;
                }
              
                .metric {
                  margin-bottom: 0.5rem;
                }
              
                .metric-title {
                  font-weight: 600;
                  color: #333;
                  margin-right: 0.5rem;
                }
              
                .metric-value {
                  font-weight: bold;
                  color: #007bff;
                }
              </style>
              

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
