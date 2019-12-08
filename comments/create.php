<?php

  // Step 1: Include our config
  include_once(dirname(__DIR__) . '/_config.php');
  
  // Step 2: Only authenticated users can add posts
  if(!AUTH)redirect (base_path . '/posts');

   /*
    Step 3:
    Validate you have all the required fields:
    title and comment (content can be blank)
  */
  $errors = [];
  foreach(['title', 'comment'] as $field) {
    if (empty($_POST[$field])) {
      $formatted = str_replace("_", " ", $field);
      $formatted = ucwords($formatted);
      $errors[] = "{$formatted} is a required field.";
    }
  }

  // Step 4: If there are errors, let the user know
  if (count($errors) > 0) {
    $_SESSION['flash']['danger'] = $errors;
    $_SESSION['form_data'] = $_POST;
    redirect(base_path . "/posts/show.php?id={$_POST['post_id']}");
  }
  /*
    Step 5:
    Sanitize our data before validating against
    the database
  */
  $_POST['title'] = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
  $_POST['comment'] = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
  /*
    Sanitizing the HTML is a little trickier. We can't use
    filter_var() because it will strip out ALL tags
    including HTML tags. Instead we'll use preg_replace
    which will allow us to strip out only the script tags.
  */
  $_POST['comment'] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $_POST['comment']);

  
  // Step 6: Include our connection and call our defined function
  include_once(ROOT . "/includes/_connect.php");
  $conn = connect();

  // Step 7: Write, prepare, and bind our SQL to get the post using the id and user id as our clause
  $sql = "SELECT * FROM posts WHERE id = :id AND user_id = {$_SESSION['user']['id']}";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':id', $_POST['post_id'], PDO::PARAM_INT);
  $stmt->execute();

  
  // Step 8: fetch the post
  $post = $stmt->fetch();

  
  // Step 9: Verify we have a post
  // if(!$post) {
  //   $_SESSION['flash']['danger'][] = "Please Select a post";
  //   redirect(base_path . '/posts');
  // }
  
  /*
  Step 10:
  Write the SQL to create the comment
  */
  $sql = "INSERT INTO comments (title, comment, user_id, post_id) VALUES (:title, :comment, {$_SESSION['user']['id']}, {$_POST['post_id']})";
  var_dump($sql);
  var_dump($_POST);
  
  // Step 11: Prepare, bind and execute our SQL
  $stmt = $conn->prepare($sql);
  var_dump($stmt);
  $stmt->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
  $stmt->bindParam(':comment', $_POST['comment'], PDO::PARAM_STR);
  var_dump($stmt);
  $stmt->execute();


  // Step 12: Send back a success message
  $_SESSION['flash']['success'][] = "You have successfully created a new post.";
  redirect(base_path . "/posts/show.php?id={$post['id']}");