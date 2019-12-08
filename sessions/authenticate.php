<?php

  // include('../_config.php');
  include_once(dirname(__DIR__) . '/_config.php');
  include(ROOT . '/includes/_connect.php');
  $conn = connect();

  // Step 1: Create a message system
  $errors = [];
  if (session_status() === PHP_SESSION_NONE) session_start();
  $_SESSION['flash'] = [];

  // Step 2: Validate the user supplied email
  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "This email is not in a valid format";
  }

  if (count($errors) > 0) {
    $_SESSION['flash']['danger'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header('Location: ' . base_path . '/sessions/login.php');
    exit;
  }

  // Step 3: Sanitize the user supplied email
  $_POST['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

  /*
    You'll notice we're not passing the password to our SQL query.
    This is intentional. The password can contain illegal characters
    in order to make it more secure. Because of this, we can't sanitize
    it. Because we can't sanitize it we don't want to pass it to the database.
    Instead we'll just evaluate the returned user against the password they're
    attempting.
  */
  // Step 4: Check if the user exists in the database
  // using variables to properly bind
  $sql = "SELECT * FROM users WHERE email = :email";
  // $conn = connect();
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
  $stmt->execute();
  $user = $stmt->fetch();

  // Step 5: Check the user exists and the password is correct
  // password_verify will evalute the password against the hash and see if they match
  if (!$user || !password_verify($_POST['password'], $user['password'])) {
    $_SESSION['flash']['danger'][] = "There was an error in validating your email and password combination";
    $_SESSION['form_data'] = $_POST;
    header('Location: ' . base_path . '/sessions/login.php');
    exit;
  }

  // Step 6: Set a session variable to verify the user is authenticated
  unset($user['password']);
  $_SESSION['user'] = $user;

  // Step 7: Return the user to their profile page
  $_SESSION['flash']['success'][] = "You have successfully logged in.";
  header('Location: ' . base_path . '/users/show.php?id=' . $user['id']);
  exit;