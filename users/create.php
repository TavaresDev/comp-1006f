<!-- fille responsible for creating new user, actually adding it to database -->
<?php
  //config file 
  include_once(dirname(__DIR__) . '/_config.php');

  // If the user is attempting to create a new user while logged in
  // and they are not an administrator then we'll redirect them
  if (AUTH && !ADMIN) {
    redirect(base_path . '/users/show.php?id=' . $_SESSION['user']['id']);
  }
  // Add the connection script
  include_once(ROOT . "/includes/_connect.php");
  $conn = connect(); //call connect function

    // connect to database
  if (session_status() === PHP_SESSION_NONE) { //start session
    session_start();
  }
  // Step 1: Create a message system
  $_SESSION['flash'] = []; //key flash = empty array
  /*
  Validation - will ensure the user enters in our required
  fields and in the required format
  */
  // Step 2: Add an $errors[] array to store the error messages
    $errors = [];

    // Variable variables allow us to use strings to access a variable name
    // Verify the fields aren't empty using a foreach loop
    $required = ['first_name', 'last_name', 'email', 'password', 'password_confirmation'];
    foreach ($required as $field) {
      if (empty($_POST[$field])) { // Variable variables allow us to use strings to access a variable name
        $formatted = ucfirst(str_replace("_", " ", $field)); // Format it into human readable
        $errors[] = "{$formatted} cannot be empty."; // Add a new error to the array
      }
    }
  // Step 3: Verify that the email is in the correct format
  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "The email provided is not in a valid format."; //add error to array
  }
  // Step 4: Verify that the password and password_confirmation match
  if ($_POST['password'] !== $_POST['password_confirmation']) {
    $errors[] = "The password and password confirmation need to match";
  }
  // Step 5: Return to the form if there are errors (we do this here because we don't want to run malicious code against our database)
  // count the $errors array
  if (count($errors) > 0) {
    $_SESSION['flash']['danger'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header('Location: ' . base_path . '/users/new.php'); // redirect to form
    exit;   // we must exit or the script will continue to run
  }

    /*
      Sanitization - will prevent data that isn't permitted
      from being entered into our database
    */
  // Step 6: Sanitize the email
  $_POST['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

  // Step 7: Sanitize the other two fields
  foreach (['first_name', 'last_name'] as $field) {
    $_POST[$field] = filter_var($_POST[$field], FILTER_SANITIZE_STRING);
  }
  /* End of sanitization */


  // Step 8: Users need to be unique, so check if the email already exists
  // close the connection cursor so it can await a new statement
  $sql = "SELECT email FROM users WHERE email = :email";  //$sql is a string containing our SQL
  $stmt = $conn->prepare($sql);  // prepare the statement to avoid SQL injection attacks
  $stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);  // bind the parameter (enforce it's a string)
  $stmt->execute();  // execute our statement
  $exists = $stmt->fetch();  // fetch the results

  // Step 9: Check if the user exists and call an error if it does
  if ($exists) $errors[] = "This user already exists.";

  // Step 10: Return errors
  // count the array //can become a function on helpers
  if (count($errors) > 0) {
    $_SESSION['flash']['danger'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header('Location: ' . base_path . '/users/new.php');  // redirect back to the form
    exit; // we must exit or the script will continue to run
  }

  // Step 11: Attempt to write the user to the database
  $sql = "INSERT INTO users (first_name, last_name, email, password, avatar) VALUES (:first_name, :last_name, :email, :password, :avatar)";
  // a string containing our SQL

  // Get avatar
  $avatar = "http://api.adorable.io/avatars/300/{$_POST['email']}";

  // Step 12: Hash password
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  // Step 13: Bind Parameters
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':first_name', $_POST['first_name'], PDO::PARAM_STR);
  $stmt->bindParam(':last_name', $_POST['last_name'], PDO::PARAM_STR);
  $stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
  $stmt->bindParam(':avatar', $avatar, PDO::PARAM_STR);
  $stmt->bindParam(':password', $password, PDO::PARAM_STR);
  $stmt->execute();

  // Close our connection
  $conn = null;

  // Step 14: Send back our success message
  $_SESSION['flash']['success'][] = "You registered successfully";
  header('Location: ' . base_path . './index.php');
  exit;