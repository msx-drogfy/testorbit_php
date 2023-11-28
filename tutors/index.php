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
</head>
<!-- bytewebster.com -->
<!-- bytewebster.com -->
<!-- bytewebster.com -->

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
            <img src="logo.png" width="30" style="margin-right: 10px;" /><span class="text-info">TEST</span>ORBIT
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
              <a href="/accounts" class="dropdown-item">Profile</a>
              <a href="#" class="dropdown-item">Settings</a>
              <a href="#" class="dropdown-item">Billing</a>
              <hr class="dropdown-divider" />
              <a href="/logout" class="dropdown-item">Logout</a>
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
                  <a href="#" class="btn d-inline-flex btn-sm btn-neutral border-base mx-1" title="Edit your personal profile">
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

          $stds = 0;

          $mrt = mysqli_query($conn, "SELECT * FROM peergroups WHERE creator_id = '$userId'");
          while ($abf = mysqli_fetch_array($mrt)) {
            $ky = $abf['group_key'];
            $sgd = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM peer_group_members WHERE group_key = '$ky' AND statusx='joined'"));
            $stds += $sgd;
          }

          // Now you can use $lastExamScore, $previousExamScore, $scoreDifference, $diffColor, and $direction in your further logic
          ?>

          <div class="row g-6 mb-6">
            <div class="col-xl-6 col-sm-6 col-12">
              <div class="card shadow border-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <span class="h6 font-semibold text-muted text-sm d-block mb-2">Peer group students</span>
                      <span class="h3 font-bold mb-0"><?php echo $stds; ?></span>

                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-success text-white text-lg rounded-circle">
                        <i class="bi bi-file-earmark-text"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-sm-6 col-12">
              <div class="card shadow border-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <span class="h6 font-semibold text-muted text-sm d-block mb-2">Tests created</span>
                      <?php $srt = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM exams WHERE creator='$user_id'")); ?>
                      <span class="h3 font-bold mb-0"><?php echo $srt; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-primary text-white text-lg rounded-circle">
                        <i class="bi bi-file-earmark-text"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Tests Taken -->
            <!-- Average Score -->
            <!-- <div class="col-xl-3 col-sm-6 col-12">
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
              </div> -->

            <!-- Time Spent -->
            <!-- <div class="col-xl-3 col-sm-6 col-12">
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
              </div> -->

            <!-- Tests Available -->
            <!-- <div class="col-xl-3 col-sm-6 col-12">
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
              </div>             -->
            <!-- Study Time -->
          </div>
          <hr>

          <div class="row g-6 mb-6">
            <?php
            $rq = mysqli_query($conn, "SELECT * FROM peer_group_admin WHERE user_id = '$userId' ORDER BY ID DESC");
            while ($pg = mysqli_fetch_array($rq)) {
              $group_key = $pg['group_key'];
              $pge = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM peergroups WHERE group_key = '$group_key'"));
            ?>
              <div class="col-xl-6 col-sm-6 col-12 mb-2">
                <div class="card shadow border-0">
                  <div class="card-body">
                    <h5 class="font-semibold mb-3"><?php echo $pge['group_name']; ?></h5>
                    <?php $participants = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM peer_group_members WHERE group_key = '$group_key' AND statusx='joined'")); ?>
                    <p><b>Group members : <?php echo $participants; ?></b><br><b>Mean Score : <?php echo calculateGroupMeanAverage($group_key); ?>%</b><br></p>
                    <span><small>
                        <b>GROUP KEY: </b><?php echo $group_key; ?>
                      </small></span>
                    <br>
                    <?php
                    $ps = mysqli_query($conn, "SELECT * FROM peer_group_members WHERE group_key='$group_key' AND statusx='waiting'");
                    if (mysqli_num_rows($ps) > 0) { ?>
                    <br>
                      <small>
                        <h5 class="text-muted mb-2">Awaiting admission</h5>
                      </small> <?php
                                while ($member = mysqli_fetch_array($ps)) {
                                  $uid = $member['user_id'];
                                ?>
                        <div class="row">
                          <div class="col-8">
                            <small>
                              <p>Name: <?php echo get_user_info($uid, "first_name"); ?> <?php echo get_user_info($uid, "last_name"); ?> <br>
                                Username: <strong><?php echo get_user_info($uid, "username"); ?> </strong></p>
                            </small>
                          </div>
                          <div class="col-4">
                            <a href="admit_user.php?user_id=<?php echo $uid; ?>&group_key=<?php echo $group_key; ?>"><button class="btn-sm btn-success">Admit</button></a>

                          </div>
                        </div>
                        <hr style="margin: 5px;">
                      <?php
                                }
                        } ?>
                    <br>

                    <a href="grouppage.php?key=<?php echo $group_key; ?>">
                      <button class="btn btn-sm btn-success">Enter group Dashboard</button>
                    </a>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
      </main>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

  <!-- Include your custom JavaScript file using url_for -->
  <script src="script2.js"></script>
</body>

</html>