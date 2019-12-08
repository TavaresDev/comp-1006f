<?php

  include_once(dirname(__DIR__) . '/_config.php');

  // Start session for form data, error handling, and success handling
  if (session_status() === PHP_SESSION_NONE) session_start();
    // User's can't update their profile unless logged in
  // User's can't update other users profiles unless they're administrators
  if (!AUTH || (AUTH && $_POST['id'] !== $_SESSION['user']['id'] && !ADMIN)) {
    redirect(base_path);
  }

  include_once(ROOT . "/includes/_connect.php");
  $conn = connect();
  // Error array container
  $errors = [];

  // Verify that our required fields aren't empty
  $required = ['first_name', 'last_name', 'email'];
  foreach($required as $field) {
    if (empty($_POST[$field])) {
      $formatted = str_replace("_", " ", $field); //make human readable
      $formatted = ucwords($formatted);
      $errors[] = "{$formatted} cannot be empty!";
    }
  }

  // Verify the email address is in the correct format
  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errors[] = "Your email is not in a valid format.";

 // Return to the form if there are errors (we do this here because we don't want to run malicious code against our database)
  if (count($errors) > 0) {
    $_SESSION['form_data'] = $_POST;
    redirect_with_errors(base_path . "/users/edit.php?id=" . $_POST['id'], $errors, 'danger');
  }

  // Sanitize our data
  $_POST['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $_POST['id'] = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
  foreach(['first_name', 'last_name'] as $field)
    $_POST[$field] = filter_var($_POST[$field], FILTER_SANITIZE_STRING);

  // Get requested user the one we're editing
 
  $sql = "SELECT * FROM users WHERE id = :id";  
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
  $stmt->execute();
  $user = $stmt->fetch(); // fetches our user

  // Validation with our retrieved user
  if (!$user) $errors[] = "This user could not be found.";

  // If we're attempting to change the email, we need to verify it doesn't already exist
  if ($_POST['email'] !== $user['email'] && !ADMIN) {
    $sql = "SELECT email FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
    $stmt->execute();
    if (!empty($stmt->fetch())) $errors[] = "This email is unavailable";
  }
  // xx from hero to the bottom no idea
  $stmt->closeCursor(); // close the connection cursor so it can await a new statement
  
  // Check for errors
  if (count($errors) > 0) { // count the array
    $_SESSION['form_data'] = $_POST;
    redirect_with_errors(base_path . "/users/edit?id=" . $_POST['id'], $errors);
  }

  // Attempt to update the user
  $avatar = "http://api.adorable.io/avatars/300/{$_POST['email']}";

  // if there's a password...
  if (!empty($_POST['password'])) {
    if ($_POST['password'] !== $_POST['password_confirmation']) {
      $_SESSION['flash']['danger'][] = "Your password must match the password confirmation.";
      $_SESSION['form_data'] = $_POST;
      redirect(base_path . "/users/edit.php?id={$_POST['id']}");
    }
    $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
  }

  // update user sql
  $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, avatar = :avatar";

  if (!empty($_POST['password']))
    $sql .= ", password = :password";

  $sql .= " WHERE id = :id";

  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':first_name', $_POST['first_name'], PDO::PARAM_STR);
  $stmt->bindParam(':last_name', $_POST['last_name'], PDO::PARAM_STR);
  $stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
  $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
  $stmt->bindParam(':avatar', $avatar, PDO::PARAM_STR);

  if (!empty($_POST['password']))
    $stmt->bindParam(':password', $_POST['password'], PDO::PARAM_STR);
  $stmt->execute();

  // Fetch updated user
  $user = $conn->query("SELECT * FROM users WHERE id = {$user['id']}")->fetch();
  unset($user['password']);

  if ($_SESSION['user']['id'] === $user['id'])
    $_SESSION['user'] = $user;

  $_SESSION['flash']['success'][] = "You have successfully updated this profile.";
  redirect(base_path . "/users/show.php?id={$_POST['id']}");