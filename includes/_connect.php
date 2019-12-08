<?php

  function connect () {
    /*
        A simple reusable connection script
        https://www.php.net/manual/en/pdo.construct.php
    */

    // Our connection details
    $host = "localhost";
    $dbname = "comp_1006";
    $username = "root";
    $password = "";


    
    // Creates(Instantiate) the connection
    try {
      // Instantiate PDO object, the PDO tranlate0 mysql comands
      $conn = new PDO("mysql:host={$host};dbname={$dbname}", $username, $password);
      // Sets error handling settings
      // ->call the method set attribute of the conn object
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e) {
      // Will output the error message and exit the application
      echo 'Connection failed: ' . $e->getMessage();
      exit; // can also use die();
    }

    return $conn;
  }

  /**
   * Connecting to MySQL with PDO.
   * PDO is a DSL (Distributive Service Layer) that acts as an operator to databases. 
   * In simple terms, it allows you to connect/prepare/bind/execute/query/fetch, using the same commands, to any of the supported databases. 
   * The benefits of this is you can change your preferred database at a later point with only needing to change your SQL statements. 
   * Combine this with an ORM (Object Relational Mapper) and you won't have to change anything. Just the database you're connecting to.
   * 
   * 
   */