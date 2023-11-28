<!DOCTYPE html>
<html lang="en">

<head>
    <?php require "userinfo.php";
    $key = $_GET['key']; ?>
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

            <style>
                #feedback {
                    border: 1px solid grey;
                    padding: 5px;
                    border-radius: 15px;
                    width: 100%;
                    margin: 5px;
                }

                #feedback-list .feedback-item {
                    background-color: #f8f9fa;
                    border: 1px solid #e0e0e0;
                    border-radius: 10px;
                    padding: 10px;
                    margin-bottom: 10px;
                }

                #feedback-list .feedback-item:hover {
                    background-color: #e9ecef;
                }
            </style>
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
                            <!-- Peer Groups Widget -->
                            <div class="card shadow border-0 mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <?php
                                            $group_info = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM peergroups WHERE group_key='$key'"));

                                            ?>
                                            <h3><span class="h3 font-bold mb-2" id="groupkey"><?php echo $group_info['group_name']; ?> group</span></h3>
                                            <br>
                                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Home</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Exams</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Participants</button>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="pills-tabContent">
                                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                                                    <?php $mmn = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM exam_registration WHERE group_key='$group_info[group_key]' AND user_id='$user_id'")); ?>
                                                    Tests taken: <?php echo $mmn; ?> <br>
                                                    <?php
                                                    $sql = "SELECT COUNT(e.exam_key) AS exam_count 
                                                            FROM exams e
                                                            LEFT JOIN exam_registration er ON e.exam_key = er.exam_key AND er.user_id = '$user_id'
                                                            WHERE e.group_key = '$key' AND er.exam_key IS NULL";
                                                    $ge = mysqli_fetch_array(mysqli_query($conn, $sql));
                                                    ?>
                                                    Tests Available: <?php echo $ge['exam_count']; ?> <br>
                                                    Your Average Score: <?php echo calculateGroupAverageScore($user_id, $group_info['group_key']); ?>%
                                                    <hr>
                                                    <h5>Your previous group exams</h5>
                                                    <?php

                                                    $ge = mysqli_query($conn, "SELECT * FROM exam_registration WHERE group_key = '$key' AND user_id = '$user_id'  ORDER BY ID DESC");
                                                    while ($exam = mysqli_fetch_array($ge)) {
                                                    ?>
                                                        <div class="card mb-2">
                                                            <div class="card-body">
                                                                <p>
                                                                    <b><?php echo get_exam_info($exam['exam_key'], "exam_name"); ?></b> <br>
                                                                    <b><?php echo $exam['exam_key']; ?></b>
                                                                    <hr>
                                                                    <small>
                                                                        <?php $xr = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM exam_sets WHERE exam_key = '$exam[exam_key]'")); ?>
                                                                        Score: <?php echo $nds = calculateScore($exam['exam_key'], $user_id); ?> <br>
                                                                        Percentage: <?php echo round(($nds / $xr) * 100, 2); ?>% <br>
                                                                        Questions: <?php echo $xr; ?> <br>
                                                                        <?php $bvc = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM exams WHERE exam_key='$exam[exam_key]'")); ?>
                                                                        Topics tested: <?php echo $bvc['exam_topics']; ?>
                                                                    </small>
                                                                    <br>
                                                                    <?php
                                                                    if ($exam['timeend'] == NULL) {
                                                                        ?>
                                                                        <a href="exam_start.php?exam_key=<?php echo $exam['exam_key']; ?>&group_key=<?php echo $key; ?>">
                                                                            <button class="btn btn-info btn-sm">Complete Exam</button>
                                                                        </a>
                                                                        <?php
                                                                    }else {
                                                                        ?>
                                                                        <a href="results_page.php?exam_key=<?php echo $exam['exam_key']; ?>&group_key=<?php echo $key; ?>">
                                                                            <button class="btn btn-success btn-sm">View Exam</button>
                                                                        </a>
                                                                        <?php
                                                                    }?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                                    <?php
                                                    $cc = 0;
                                                    $sql = "SELECT e.* 
                                                        FROM exams e
                                                        LEFT JOIN exam_registration er ON e.exam_key = er.exam_key AND er.user_id = '$user_id'
                                                        WHERE e.group_key = '$key' AND er.exam_key IS NULL ORDER BY e.id DESC";
                                                    $ge = mysqli_query($conn, $sql);
                                                    while ($exam = mysqli_fetch_array($ge)) {
                                                        $cc++;
                                                    ?>
                                                        <div class="card mb-2">
                                                            <div class="card-body">
                                                                <p>
                                                                    
                                                                    <b>Name: <?php echo $exam['exam_name']; ?></b> <br>
                                                                    Key: <?php echo $exam['exam_key']; ?> <br>
                                                                    <?php
                                                                        $stmt = $conn->prepare("SELECT * FROM exam_sets WHERE exam_key = ?");
                                                                        $stmt->bind_param("s", $exam['exam_key']);
                                                                        $stmt->execute();
                                                                        $result = $stmt->get_result();
                                                                        $qst = mysqli_num_rows($result);
                                                                        echo "Time to finish: <b>" . $exam['duration'] . " Mins</b>" . "<br>";
                                                                    if ($exam['scheduled'] == "yes") {
                                                                        // Prepare statement to prevent SQL injection
                                                                        
                                                                        // Display exam schedule
                                                                        echo "Scheduled to start on " . date("D d-M-Y h:i A", $exam['starttime']);
                                                                        if ($time < $exam['starttime']) {
                                                                            echo '<span class="badge badge-success"><b>Upcoming</b></span>';
                                                                        } elseif ($exam['starttime'] < $time && $time < $exam['closingtime']) {
                                                                            echo '<span class="badge badge-primary"><b>On Going</b></span>';
                                                                        } else {
                                                                            echo '<span class="badge badge-info"><b>Past Due</b></span>';
                                                                        }

                                                                        // Determine exam status
                                                                    }
                                                                    ?>
                                                                    <hr>
                                                                    Exam attendees: <?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM exam_registration WHERE exam_key='$exam[exam_key]'")); ?>
                                                                    <hr>
                                                                    <small>
                                                                        <?php $xr = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM exam_sets WHERE exam_key = '$exam[exam_key]'")); ?>
                                                                        Questions: <?php echo $xr; ?> <br>
                                                                        Topics tested: <?php echo $exam['exam_topics']; ?>
                                                                    </small>
                                                                    <br>

                                                                    <?php
                                                                    if ($exam['scheduled'] == "yes") {
                                                                        // Prepare statement to prevent SQL injection
                                                                        $stmt = $conn->prepare("SELECT * FROM exam_sets WHERE exam_key = ?");
                                                                        $stmt->bind_param("s", $exam['exam_key']);
                                                                        $stmt->execute();
                                                                        $result = $stmt->get_result();
                                                                        $qst = mysqli_num_rows($result);

                                                                        // Determine exam status
                                                                        if ($time < $exam['starttime']) {
                                                                        } elseif ($exam['starttime'] < $time && $time < $exam['closingtime']) {
                                                                            ?>
                                                                            <a href="exam_start.php?exam_key=<?php echo $exam['exam_key']; ?>&group_key=<?php echo $key; ?>">
                                                                                <button class="btn btn-success btn-sm">Start Exam</button>
                                                                            </a>
                                                                            <?php 
                                                                        } else {
                                                                        }
                                                                    } else { ?>

                                                                        <a href="exam_start.php?exam_key=<?php echo $exam['exam_key']; ?>&group_key=<?php echo $key; ?>">
                                                                            <button class="btn btn-success btn-sm">Start Exam</button>
                                                                        </a>
                                                                    <?php } ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                    if ($cc < 1) {
                                                    ?><div class="col-12" style="text-align: center;">You do not have any pending group exams</div><?php
                                                                                                                                                }
                                                                                                                                                    ?>
                                                </div>
                                                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                                                    <h5>Group Participants</h5>
                                                    <div id="feedback-list" class="mt-4">
                                                        <?php $ps = mysqli_query($conn, "SELECT * FROM peer_group_members WHERE group_key='$key' AND statusx='joined'");
                                                        while ($member = mysqli_fetch_array($ps)) {
                                                            $uid = $member['user_id'];
                                                        ?>
                                                            <div class="feedback-item">
                                                                <p><strong><?php echo get_user_info($uid, "username"); ?> </strong><br>
                                                                    <?php echo get_user_info($uid, "email"); ?></p>
                                                            </div>
                                                        <?php
                                                        } ?>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
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
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            const textToCopy = element.innerText || element.textContent;

            navigator.clipboard.writeText(textToCopy).then(() => {
                alert('Group key copied');
            }).catch(err => {
                console.error('Failed to copy group key : ', err);
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <!-- Include your custom JavaScript file using url_for -->
    <script src="script2.js"></script>
</body>

</html>