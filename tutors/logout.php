<?php
session_start();
// require "userinfo.php";
session_destroy();
session_unset();
header("Location: auth.php");