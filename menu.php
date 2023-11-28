<div class="collapse navbar-collapse" id="sidebarCollapse">
  <!-- Navigation -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" href="index.php">
        <i class="bi bi-house"></i> Dashboard
      </a>
    </li> 
    <li class="nav-item">
      <a class="nav-link" href="test.php">
        <i class="bi bi-bar-chart"></i> Test Questions
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="peergroups.php">
        <i class="bi bi-people"></i> Peer groups
      </a>
    </li>
    <?php $pgm = mysqli_query($conn, "SELECT * FROM peer_group_members WHERE user_id='$user_id' AND statusx='joined'"); 
    while ($my_groups = mysqli_fetch_array($pgm)) {
      $gi = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM peergroups WHERE group_key='$my_groups[group_key]'")); ?>
      <li class="nav-item">
        <a class="nav-link" href="grouppage.php?key=<?php echo $my_groups['group_key']; ?>">
          <i class="bi bi-people"></i> <?php echo $gi['group_name']; ?>
          <?php
                                                    $sql3 = "SELECT COUNT(e.exam_key) AS exam_count 
                                                            FROM exams e
                                                            LEFT JOIN exam_registration er ON e.exam_key = er.exam_key AND er.user_id = '$user_id'
                                                            WHERE e.group_key = '$my_groups[group_key]' AND er.exam_key IS NULL";
                                                    $ge2 = mysqli_fetch_array(mysqli_query($conn, $sql3));
                                                    if($ge2['exam_count'] > 0) { ?>
                            <span class="badge bg-soft-primary text-primary rounded-pill d-inline-flex align-items-center ms-auto"><?php echo $ge2['exam_count']; ?></span>
                            <?php } ?>
        </a>
      </li>
      <?php
    }
    ?>
    <!-- <li class="nav-item">
                <a class="nav-link" href="insights.php">
                  <i class="bi bi-bookmarks"></i> Insights
                </a>
              </li> -->
    <li class="nav-item">
      <a class="nav-link" href="rankings.php">
        <i class="bi bi-globe-americas"></i> Ranking
      </a>
    </li>
    <hr>
  </ul>

  <!-- Push content down -->
  <div class="mt-auto"></div>
  <!-- User (md) -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" href="account.php">
        <i class="bi bi-person-square"></i> Account
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="feedback.php">
        <i class="bi bi-layout-text-sidebar-reverse"></i> Feedback
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="logout.php" onclick="return confirm('Are you sure you want to logout?')">
        <i class="bi bi-box-arrow-left"></i> Logout
      </a>
    </li>
  </ul>
</div>