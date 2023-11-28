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
                                            <h3><span class="h3 font-bold mb-2"><?php echo $group_info['group_name']; ?> group</span></h3>

                                            <h4>Mean Score : <?php echo calculateGroupMeanAverage($key); ?>%</h4>
                                            <br>
                                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Home</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Exams</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Create exam</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="pills-table-tab" data-bs-toggle="pill" data-bs-target="#pills-table" type="button" role="tab" aria-controls="pills-table" aria-selected="false">Table</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="pills-settings-tab" data-bs-toggle="pill" data-bs-target="#pills-settings" type="button" role="tab" aria-controls="pills-settings" aria-selected="false">Settings</button>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="pills-tabContent">
                                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                                    <label for="query">Group Key</label>
                                                    <h3><span class="h3 font-bold mb-2" id="groupkey"><?php echo $key; ?></span></h3>
                                                    <span></span>
                                                    </a>
                                                    <button class="btn d-inline-flex btn-sm btn-primary mx-1" onclick="copyToClipboard('groupkey')"><span class="pe-2">
                                                            <i class="bi bi-people"></i>
                                                        </span>Copy Group Key</button>
                                                    <br>
                                                    <hr>
                                                    <h5>Group Participants</h5>
                                                    <div id="feedback-list" class="mt-4">
                                                        <?php $ps = mysqli_query($conn, "SELECT * FROM peer_group_members WHERE group_key='$key' AND statusx='joined'");
                                                        while ($member = mysqli_fetch_array($ps)) {
                                                            $uid = $member['user_id'];
                                                        ?>
                                                            <div class="feedback-item">
                                                                Name:<?php echo get_user_info($uid, "first_name"); ?> <?php echo get_user_info($uid, "last_name"); ?> <br>
                                                                <p>Username: <strong><?php echo get_user_info($uid, "username"); ?> </strong><br>
                                                                    Average score: <?php echo calculateGroupAverageScore($uid, $key) ?>% <br>
                                                                    <?php $mmn = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM exam_registration WHERE group_key='$key' AND user_id='$uid'")); ?>
                                                                    Tests taken: <?php echo $mmn; ?> <br>
                                                                    <hr>
                                                                    <small><u>
                                                                            <p>Previous exams</p>
                                                                        </u></small>
                                                                <table style="width: 100%;">
                                                                    <?php
                                                                    $mcx = mysqli_query($conn, "SELECT * FROM exam_registration WHERE group_key='$key' AND user_id='$uid' ORDER BY ID DESC LIMIT 5");
                                                                    while ($cmx = mysqli_fetch_array($mcx)) { ?>
                                                                        <tr>
                                                                            <td><?php echo get_exam_info($cmx['exam_key'], "exam_name"); ?></td>
                                                                            <td><?php echo calculate_percentage_Score($cmx['exam_key'], $uid); ?></td>
                                                                        </tr>
                                                                    <?php
                                                                    } ?>
                                                                </table>
                                                                </p>
                                                            </div>
                                                        <?php
                                                        } ?>
                                                    </div>
                                                    <hr>
                                                    <h5>New Participants to admit</h5>
                                                    <div id="feedback-list" class="mt-4">
                                                        <?php $ps = mysqli_query($conn, "SELECT * FROM peer_group_members WHERE group_key='$key' AND statusx='waiting'");
                                                        while ($member = mysqli_fetch_array($ps)) {
                                                            $uid = $member['user_id'];
                                                        ?>
                                                            <div class="feedback-item">
                                                                Name: <?php echo get_user_info($uid, "first_name"); ?> <?php echo get_user_info($uid, "last_name"); ?> <br>
                                                                <p>Username: <strong><?php echo get_user_info($uid, "username"); ?> </strong><br>
                                                                <p>Email: <?php echo get_user_info($uid, "email"); ?><br>
                                                                    <a href="admit_user.php?user_id=<?php echo $uid; ?>&group_key=<?php echo $key; ?>"><button class="btn-sm btn-success m-2">Admit</button></a>
                                                                    <a href="decline_user.php?user_id=<?php echo $uid; ?>&group_key=<?php echo $key; ?>"><button class="btn-sm btn-danger m-2">Decline</button></a>
                                                                </p>
                                                            </div>
                                                        <?php
                                                        } ?>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                                    <?php
                                                    $ge = mysqli_query($conn, "SELECT * FROM exams WHERE group_key = '$key' ORDER BY ID DESC");
                                                    while ($exam = mysqli_fetch_array($ge)) {
                                                    ?>
                                                        <div class="card mb-2">
                                                            <div class="card-body">
                                                                <p>
                                                                    <b>Exam name: <?php echo $exam['exam_name']; ?></b><br>
                                                                    <b>Key: <?php echo $exam['exam_key']; ?></b><br>
                                                                    <?php
                                                                    $stmt = $conn->prepare("SELECT * FROM exam_sets WHERE exam_key = ?");
                                                                    $stmt->bind_param("s", $exam['exam_key']);
                                                                    $stmt->execute();
                                                                    $result = $stmt->get_result();
                                                                    $qst = mysqli_num_rows($result);

                                                                    echo "Time to finish: <b>" . $exam['duration'] . " Mins</b> <br>";
                                                                    if ($exam['scheduled'] == "yes") {
                                                                        // Prepare statement to prevent SQL injection

                                                                        echo "Scheduled to start on " . date("D d-M-Y h:i A", $exam['starttime']);
                                                                        // Display exam schedule
                                                                        // Determine exam status
                                                                        if ($time < $exam['starttime']) {
                                                                            echo '<span class="badge badge-success"><b>Scheduled</b></span>';
                                                                        } elseif ($exam['starttime'] < $time && $time < $exam['closingtime']) {
                                                                            echo '<span class="badge badge-primary"><b>On Going</b></span>';
                                                                        } else {
                                                                            echo '<span class="badge badge-info"><b>Past Due</b></span>';
                                                                        }
                                                                    }
                                                                    ?>

                                                                    <hr>
                                                                    Exam attendees: <?php echo $atds = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM exam_registration WHERE exam_key='$exam[exam_key]'")); ?> <br>
                                                                    Average Score: <?php echo calculateExamAverageScore($exam['exam_key']); ?>%
                                                                    <hr>
                                                                    <small>
                                                                        <?php $xr = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM exam_sets WHERE exam_key = '$exam[exam_key]'")); ?>
                                                                        Questions: <?php echo $xr; ?> <br>
                                                                        Topics tested: <?php echo $exam['exam_topics']; ?>
                                                                    </small>
                                                                    <br>
                                                                    <a href="teacher_page.php?exam_key=<?php echo $exam['exam_key']; ?>&group_key=<?php echo $key; ?>">
                                                                        <button class="btn btn-success btn-sm">View Exam</button>
                                                                    </a>
                                                                    <?php if ($atds < 1) { ?>
                                                                        <a href="delete_page.php?exam_key=<?php echo $exam['exam_key']; ?>&group_key=<?php echo $key; ?>">
                                                                            <button class="btn btn-danger btn-sm">Delete Exam</button>
                                                                        </a>
                                                                    <?php } ?>
                                                                    <button class="btn btn-warning btn-sm" onclick="showExplanation(<?php echo $exam['id']; ?>, 'option')"><i class="bi bi-gear"></i></button>

                                                                <div id="option_<?php echo $exam['id']; ?>_explanation" class="hidden mt-4">
                                                                    <style>
                                                                        p {
                                                                            margin-bottom: 10px;
                                                                        }
                                                                    </style>
                                                                    <p>

                                                                        This exam will be closed at: <b><?php echo date("D d-M-Y h:i A", $exam['closingtime']); ?></b> <br>
                                                                    </p>

                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <small><b><u>Move forward the closing time</u></b></small>
                                                                            <br>
                                                                            <form action="editexam.php" method="post">
                                                                                <label for="numQuestions">Enter the number of minutes you want to add to the closing time</label>
                                                                                <input type="text" name="add_time" id="numQuestions" placeholder="100">
                                                                                <input type="hidden" name="exam_key" value="<?php echo $exam['exam_key']; ?>">
                                                                                <input type="hidden" name="group_key" value="<?php echo $key; ?>">
                                                                                <br>
                                                                                <button class="btn d-inline-flex btn-sm btn-primary mx-1" type="submit"><span class="pe-2"></span>Update Exam</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">

                                                    <form action="group_exam_creator.php" method="post">

                                                        <span class="h3 font-bold mb-0">Create Group Exam
                                                        </span>
                                                        <br>
                                                        <label for="query1"><b>Enter name for exam</b> (optional)</label>
                                                        <input type="text" name="exam_name" id="numQuestions" placeholder="Revision 1" value="Test Exam <?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM exams WHERE group_key='$key'")) + 1; ?>">
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
                                                        <label for="query1"><b>Enter time(minutes) that the exam should take</b></label>
                                                        <small>Default: exam time = exam questions * 2 minutes</small>
                                                        <input type="text" name="exam_time" id="numQuestions" placeholder="15 mins">
                                                        <br>
                                                        <label for="query"><b>Do you want to schedule this exam</b></label>
                                                        <label for="showParticipants">
                                                            <li class="ans">
                                                                <input type="radio" id="showParticipants" name="schedule" value="yes">
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
                                                                <input type="radio" id="rank2" name="schedule" value="no">
                                                                No
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
                                                        <input type="hidden" name="group_key" value="<?php echo $key; ?>">
                                                        <br>
                                                        <label for="query"><b>Enter date for exam to be done</b></label>
                                                        <input type="date" name="dates" id="numQuestions">
                                                        <br>
                                                        <label for="query"><b>Enter time for exam to be done</b></label>
                                                        <input type="time" name="times" id="numQuestions">

                                                        <br>
                                                        <label for="query">
                                                            <b>Do you want to show ranking of the participants</b></label>
                                                        <label for="showParticipantsx">
                                                            <li class="ans">
                                                                <input type="radio" id="showParticipantsx" name="ranks" value="yes">
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
                                                        <label for="rank2x">
                                                            <li class="ans ans2">
                                                                <input type="radio" id="rank2x" name="ranks" value="no">
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
                                                <div class="tab-pane fade" id="pills-table" role="tabpanel" aria-labelledby="pills-table-tab">

                                                    <div id="feedback-list" class="mt-4">
                                                        <?php $ps = mysqli_query($conn, "SELECT * FROM peer_group_members WHERE group_key='$key' AND statusx='joined'");
                                                        while ($member = mysqli_fetch_array($ps)) {
                                                            $uid = $member['user_id'];
                                                        } ?>
                                                    </div>

                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-nowrap">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th scope="col">##</th>
                                                                    <th scope="col">Name</th>
                                                                    <?php
                                                                    $ls = mysqli_query($conn, "SELECT * FROM exams WHERE group_key = '$key' LIMIT 25");
                                                                    while ($ms = mysqli_fetch_array($ls)) { ?>
                                                                        <th scope="col"><?php echo $ms['exam_name']; ?></th>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $c = 0;
                                                                $ps = mysqli_query($conn, "SELECT * FROM peer_group_members WHERE group_key='$key' AND statusx='joined'");
                                                                while ($member = mysqli_fetch_array($ps)) {
                                                                    $uid = $member['user_id'];
                                                                    $c++;
                                                                ?>
                                                                    <tr>
                                                                        <td><?php echo $c; ?></td>
                                                                        <td><?php echo get_user_info($uid, "first_name"); ?> <?php echo get_user_info($uid, "last_name"); ?> <br>
                                                                        Username: <?php echo get_user_info($uid, "username"); ?>
                                                                    </td>
                                                                        <?php
                                                                        $ls = mysqli_query($conn, "SELECT * FROM exams WHERE group_key = '$key' LIMIT 25");
                                                                        while ($ms = mysqli_fetch_array($ls)) { ?>
                                                                            <th scope="col"><?php echo calculate_percentage_Score($ms['exam_key'], $uid); ?></th>
                                                                        <?php
                                                                        } ?>
                                                                    </tr>
                                                                <?php } ?>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="pills-settings" role="tabpanel" aria-labelledby="pills-settings-tab">
                                                    <p>Edit group settings</p>
                                                    <div class="card mb-2">
                                                        <div class="card-body">
                                                            <small><b><u>Peer group admins</u></b></small>
                                                            <br>
                                                            <?php
                                                            $esx = mysqli_query($conn, "SELECT * FROM peer_group_admin WHERE group_key = '$key'");
                                                            while ($use = mysqli_fetch_array($esx)) {
                                                                $user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tutors WHERE id = '$use[user_id]'"));
                                                            ?>
                                                                <div class="row mb-1">
                                                                    <div class="col-9">
                                                                        <?php echo $user['first_name']; ?> <?php echo $user['last_name']; ?> <br> <?php echo $user['email']; ?>
                                                                    </div>
                                                                    <div class="col-3">
                                                                        <a href="removeadmin.php?group_key=<?php echo $key; ?>&admin=<?php echo $use['ID']; ?>">
                                                                            <button class="btn d-inline-flex btn-sm btn-warning mx-1"><span class="pe-2"></span>Remove</button>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            <?php  } ?>
                                                        </div>
                                                    </div>
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <small><b><u>Add peer group admins</u></b></small>
                                                            <br>
                                                            <form action="addadmin.php" method="post">
                                                                <label for="numQuestions">Choose new admins for your group</label>
                                                                <input type="text" name="admin_id" id="numQuestions" list="admins">
                                                                <input type="hidden" name="group_key" value="<?php echo $key; ?>">
                                                                <datalist id="admins">
                                                                    <?php
                                                                    $esx = mysqli_query($conn, "SELECT * FROM tutors");
                                                                    while ($user = mysqli_fetch_array($esx)) {
                                                                    ?><option value="<?php echo $user['id']; ?>"> <?php echo $user['first_name']; ?> <?php echo $user['last_name']; ?> <?php echo $user['email']; ?></option><?php  } ?>
                                                                </datalist>
                                                                <br>
                                                                <button class="btn d-inline-flex btn-sm btn-primary mx-1" type="submit"><span class="pe-2"></span>Add as admin</button>
                                                            </form>
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
                </div>
            </main>
        </div>
    </div>
    <script>
        function showExplanation(id, option) {
            // Get the explanation element and toggle its visibility
            var explanation = document.getElementById(`${option}_${id}_explanation`);
            if (explanation.classList.contains("hidden")) {
                explanation.classList.remove("hidden");
            } else {
                explanation.classList.add("hidden");
            }
        }

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