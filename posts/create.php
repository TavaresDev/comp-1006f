<?php
  // Step 1: Include our config
  include_once("../_config.php");

  // Step 11: Only admins can create a post
  if(!ADMIN) redirect(base_path . '/posts');

  // Step 2: Handle our errors
  $errors = [];

  /*
    Step 3:
    Validate you have all the required fields:
    title and status (content can be blank)
  */

  foreach(['title', 'status'] as $field){
    if(empty($_POST[$field])){
      $formatted = str_replace('_', ' ', $field);
      $formatted = ucwords($_POST[$field]);
      $errors[] = "{$formatted} required a value";
    }
  }

  // Step 4: If there are errors, let the user know
  if(count($errors) > 0) {
    $_SESSION['form_data'] = $_post;
    $_SESSION['flash']['danger'] = $errors;
    redirect(base_path . "/posts/new.php");

  }

  /*
    Step 5:
    Sanitize our data before validating against
    the database
  */
  $_POST['title'] = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
  $_POST['status'] = filter_var($_POST['status'], FILTER_SANITIZE_STRING);

  /*
    Step 6:
    Sanitizing the HTML is a little trickier. We can't use
    filter_var() because it will strip out ALL tags
    including HTML tags. Instead we'll use preg_replace
    which will allow us to strip out only the script tags.
  */
  $_POST['content'] = preg_replace('#<script(.?)>(.*?)</script>#is', '', $_POST['content']);
  // var_dump($_POST);
  // exit;

  // upload our file
  // var_dump($_FILES);
  // exit;

  // move file
  // move_uploaded_file(
  //   $_FILES['image']['tmp_name'],
  //   ROOT . '/uploads/' . $_FILES['image']['name']
  // );


  /*
    Create the post using the session user id as the user
  */
  // Step 7:

$sql = "INSERT INTO posts (
  title,
  status,
  content,  
  user_id
) VALUES (
  :title,
  :status,
  :content,
  {$_SESSION['user']['id']}
)";

  // Step 8: Include our connection and call our defined function
  include_once(ROOT . "/includes/_connect.php");
  $conn = connect();

  // Step 9: Prepare, bind and execute our SQL
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
  $stmt->bindParam(':status', $_POST['status'], PDO::PARAM_STR);
  $stmt->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
  $stmt->execute();
  // get lest id posted
  $post_id = $conn->lastInsertId();

  // Step 10: Send back a success message
  $_SESSION['flash']['success'][] = "You have successfully created a new post";
  redirect(base_path . "/posts/show.php?id={$post_id}");
