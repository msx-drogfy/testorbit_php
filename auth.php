<!DOCTYPE html>
<html lang="en">
<head>
  <?php
  session_start();
   require "functions.php"; ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Signup TestOrbit</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="style2.css">
</head>
<body>
  <div class="wrapper">
    <div class="title-text">
      <div class="title login">TestOrbit Login</div>
      <div class="title signup">TestOrbit Signup</div>
    </div>
    <div class="form-container">
      <div class="slide-controls">
        <input type="radio" name="slide" id="login" checked>
        <input type="radio" name="slide" id="signup">
        <label for="login" class="slide login">Login</label>
        <label for="signup" class="slide signup">Signup</label>
        <div class="slider-tab"></div>
      </div>
      
      
          <?php display_alert(); ?>
      <div class="form-inner">
        <form action="login.php" method="POST" class="login">

          </pre>
          <div class="field">
            <input type="text" placeholder="Email Address" name="username" required>
          </div>
          <div class="field">
            <input type="password" placeholder="Password" name="password" required>
          </div>
          <div class="pass-link"><a href="#">Forgot password?</a></div>
          <div class="field btn">
            <div class="btn-layer"></div>
            <input type="submit" value="Login">
          </div>
          <div class="signup-link">Create an account <a href="">Signup now</a></div>
        </form>
        <form action="register.php" class="signup" method="post">
          <div class="field">
            <input type="text" placeholder="First Name" name="fname" required>
          </div>
          <div class="field">
            <input type="text" placeholder="Last Name" name="lname" required>
          </div>
          <div class="field">
            <input type="text" placeholder="Username" name="username" required>
          </div>
          <div class="field">
            <input type="text" placeholder="Email Address" name="email" required>
          </div>
          <div class="field">
            <input type="password" placeholder="Password" name="password" required>
          </div>
          <div class="field btn">
            <div class="btn-layer"></div>
            <input type="submit" value="Signup">
          </div>
          <div class="signup-link">Already have an account? <a href="">Login</a></div>
        </form>
      </div>
    </div>
  </div>
  <script src="script.js"></script>
</body>
</html>
