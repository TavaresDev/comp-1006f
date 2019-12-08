<?php

  // GET values
  var_dump($_GET);
  // echo "Hello {$_GET['name']}. You are {$_GET['age']} years old. Your favourite colour is {$_GET['colour']}.";

  // POST values
  var_dump($_POST);

  // SESSION values
  session_start();
  $_SESSION['my_value'] = "YOWZERS";
  var_dump($_SESSION);
  unset($_SESSION['my_value']);
  var_dump($_SESSION);
  $_SESSION['my_value'] = "BOORAKACHA";
  session_unset();
  var_dump($_SESSION);

?>

<form method="post">
  <label>First Name:</label>
  <input name="first_name">
  <br>
  <label>Last Name:</label>
  <input name="last_name">
  <br>
  <button type="submit">Button</button>
</form>