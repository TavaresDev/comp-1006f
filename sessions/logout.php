<?php

  include_once(dirname(__DIR__) . '/_config.php');

  // Step 1: start/resume the session if it doesn't exist
  if (session_status() === PHP_SESSION_NONE) session_start();

  // Step 2: Unset the session variable we're using to check if the user is logged in
  unset($_SESSION['user']);

  // Step 3: Return the user to the home page of the site
  $_SESSION['flash'] = [];
  $_SESSION['flash']['success'][] = "You've logged out successfully";
  header('Location: ' . base_path . '/index.php');
  exit;