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
          <?php require "menu.php"; ?>
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
                    Start Exam
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
                
                      
    
                    <div class="card shadow border-0 mb-4">
                        <div class="card-body">
                            <?php display_alert();
                            $exam_key = $_GET['exam_key']; ?>
                                <p>Your exam key is</p>
                                <h3><span class="h3 font-bold mb-0" id="examkey"><?php echo $exam_key; ?></span></h3>
                                <br>
                                <?php $exam_info = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) AS questions FROM exam_sets WHERE exam_key='$exam_key'")); ?>
                                <p>Questions <b><?php echo $exam_info['questions']; ?></b></p>
                                <p>Time <b><?php echo $exam_info['questions']*2; ?> minutes</b></p>
                                <br>
                                <a href="examiner.php?key=<?php echo $exam_key; ?>" class="btn btn-sm btn-success mx-1" style="width: fit-content;">Start Exam</a>
                                <button class="btn btn-sm btn-danger mx-1" style="width: fit-content;" onclick="copyToClipboard('examkey')">Copy Exam Key</button>
                          <script>
                              function copyToClipboard(elementId) {
                                const element = document.getElementById(elementId);
                                const textToCopy = element.innerText || element.textContent;
                            
                                navigator.clipboard.writeText(textToCopy).then(() => {
                                  alert('Exam key copied');
                                }).catch(err => {
                                  console.error('Failed to copy Exam key : ', err);
                                });
                              }
                            
                          </script>
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
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
      crossorigin="anonymous"
    ></script>

    <!-- Include your custom JavaScript file using url_for -->
    <script src="script2.js"></script>
  </body>
</html>
