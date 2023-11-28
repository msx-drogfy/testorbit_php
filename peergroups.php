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
                  Peer groups
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
                      <span class="h3 font-bold mb-0">Join a peer group using group key
                      </span>
                      <form action="group_joiner.php" method="post">

                        <label for="query">Enter group key</label>
                        <input type="text" name="group_key" id="nums" placeholder="AAA-BBBB-CCCC">
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
                            <i class="bi bi-people"></i>
                          </span>Join Group</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Peer Groups Widget -->
              <?php
              $ix = mysqli_query($conn, "SELECT * FROM peer_group_members WHERE user_id = '$user_id'");
              while ($group = mysqli_fetch_array($ix)) {
                $group_info = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM peergroups WHERE group_key='$group[group_key]'")); ?>
                <div class="col-12 mb-3">
                  <div class="card shadow border-0">
                    <div class="card-body">
                      <h4 class="font-semibold mb-3"><?php echo $group_info['group_name']; ?> group</h4>
                      <p>
                        <?php $mmn = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM exam_registration WHERE group_key='$group_info[group_key]' AND user_id='$user_id'")); ?>
                        Tests taken: <?php echo $mmn; ?> <br>
                        Your Average Score: <?php echo calculateGroupAverageScore($user_id, $group_info['group_key']); ?>%
                        <hr>
                        <?php
                        $sql = "SELECT COUNT(e.exam_key) AS exam_count 
                        FROM exams e
                        LEFT JOIN exam_registration er ON e.exam_key = er.exam_key AND er.user_id = '$user_id'
                        WHERE e.group_key = '$group_info[group_key]' AND er.exam_key IS NULL";
                        $ge = mysqli_fetch_array(mysqli_query($conn, $sql));
                        $qqs = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM peer_group_members WHERE group_key='$group_info[group_key]'")); ?>
                        Group Members: <?php echo $qqs; ?> <br>
                        Tests Available: <?php echo $ge['exam_count']; ?> <br>
                      </p>
                      <br>
                      <?php
                      $pgi = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM peer_group_members WHERE group_key='$group_info[group_key]' AND user_id='$user_id'"));
                      if ($pgi['statusx'] == 'joined') { ?>
                        <a href="grouppage.php?key=<?php echo $group_info['group_key']; ?>">
                          <button class="btn btn-sm btn-success">Enter group Dashboard</button>
                        </a><?php
                      }elseif ($pgi['statusx'] == 'waiting') { ?>
                        <strong>Please await to be admitted to the group by your TeachOp</strong><?php
                      }else{
                        ?>
                        <span class="badge badge-danger">
                          You have been declined to join this group
                        </span>
                        <?php
                      } ?>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
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