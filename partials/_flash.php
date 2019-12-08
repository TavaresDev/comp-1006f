<?php
  // Below is our flash notification system. It will iterate over the flash data and output the messages

  // Step 1: Check if the variable $flash_data is set and has data
  if (!empty($flash_data)) {
    // Step 2: Interate over the data and assign the key to the $type and the value to the $message variables
    foreach ($flash_data as $type => $messages) {
      // Step 3: Echo the opening and closing div tags
      echo "<div class='alert alert-{$type}'>";
      // Step 4: Iterate over the $messages and output each message
      foreach ($messages as $message) {
        echo "{$message}<br>";
      }
      // Step 5: Close the div
      echo "</div>";
    } // closes foreach
  } // closes if condition

?>