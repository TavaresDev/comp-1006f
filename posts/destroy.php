<?php

  // Step 1: Include our config
  include_once(dirname(__DIR__) . '/_config.php');
  
  // Step 8: Only admins can delete a post
  not_admin_redirect(base_path . '/posts');

  // Step 2: Include our connection and call our defined function
  include_once(ROOT . "/includes/_connect.php");
  $conn = connect();

  // Step 3: Get the post using the id and user id as our clause
  $sql = "SELECT * FROM posts WHERE id = :id AND user_id = {$_SESSION['user']['id']}";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
  $stmt->execute();
  $post = $stmt->fetch();


  // Step 4: Verify we have a post
  if (!$post) {
    $_SESSION['flash']['danger'][] = "Please provide a valid post you own.";
    // Send them to posts because they're not editing a valid post they own
    redirect(base_path . "/posts");
  }

  
   // Step 5: Delete the post
  
  $sql = "DELETE FROM posts WHERE id = :id";

  // Step 6: Prepare, bind and execute our SQL
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
  $stmt->execute();

  // Step 7: Send back a success message
    redirect_with_success(base_path . "/posts", "You have successfully delete the post.");

?>