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
                    Feedback
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
                      <span class="h3 font-bold mb-0">User Feedback</span>
                      <p>Share your thoughts and read what others have said</p>
                      <!-- Feedback Form -->
                      <form action="submit_feedback.php" method="post">
                        <textarea name="feedback" id="feedback" rows="4" name="feedback" placeholder="Your feedback here..." class="form-control mb-2"></textarea>
                        <input type="submit" class="btn btn-sm btn-success mx-1" value="Submit Feedback">
                      </form>
                      <!-- Feedback Display -->
                      <div id="feedback-list" class="mt-4">
                        <!-- Dynamically load feedback here -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card shadow border-0 mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <span class="h3 font-bold mb-0">User Feedback</span>
                      <p>Read what others have to say</p>
                      <!-- Feedback Display Section -->
                      <div id="feedback-list" class="mt-4">
                        <?php
                        $rs =mysqli_query($conn, "SELECT * FROM feedback ORDER BY ID DESC LIMIT 20");
                        while ($feedback = mysqli_fetch_array($rs)) { ?>
                        <!-- Example Feedback -->
                        <div class="feedback-item">
                          <p><strong><?php echo $feedback['username']; ?>: </strong><?php echo $feedback['feedback']; ?></p>
                        </div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              

              <style>
                #feedback {
                    border: 1px solid grey;
                    padding: 5px;
                    padding-left: 10px;
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
              <script>
                // Example: Fetch feedback from a server and display it
fetch('/get_feedback')
.then(response => response.json())
.then(feedbacks => {
  const feedbackList = document.getElementById('feedback-list');
  feedbacks.forEach(feedback => {
    const div = document.createElement('div');
    div.classList.add('feedback-item');
    div.textContent = feedback.message; // Assuming 'message' is a property of feedback
    feedbackList.appendChild(div);
  });
});

              </script>
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
