<?php
  // Include our common config file
  include_once(dirname(__DIR__) . "/_config.php");

  // Step 1: Start the session -> session_start();
  if (session_status() === PHP_SESSION_NONE) session_start();

  // Step 2: Assign the session variables
  // flash is the key, link to the value 
  $flash_data = $_SESSION['flash'] ?? null; // terciary if  
  $form_data = $_SESSION['form_data'] ?? null;
  // Step 3: Clear the session variables so it's blank the next time
  unset($_SESSION['flash']);  
  unset($_SESSION['form_data']);

?>

<!DOCTYPE html>
<html>
  <head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.css" rel="stylesheet">
    <title><?= $_title ?? "Andre's Portfolio" ?></title>
  </head>

  <body>
    
    <?php include(ROOT . '/partials/_main-nav.php') ?>
    <!-- Step 4: Include the _flash.php notification component -->
    <?php include(ROOT . '/partials/_flash.php') ?>