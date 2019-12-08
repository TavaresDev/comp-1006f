<?php
//   login is simple, when we login the variable ($_SESSION['user'] is created, 
// so if this variable exists, the user is loged in

  if (session_status() === PHP_SESSION_NONE) session_start();

  function is_auth () {
    return isset($_SESSION['user']); // isset( check and return a boolean)
  }

  function is_admin () {
    return is_auth() && $_SESSION['user']['role'] === 'admin';
  }

  // functions to redirect the user
  function not_admin_redirect ($path) {
    if (!is_auth() || (is_auth() && !is_admin())) {
      header("Location: {$path}");
      exit;
    }
  }
  function not_auth_redirect ($path) {
    if (!is_auth()) {
      header("Location: {$path}");
      exit;
    }
  }