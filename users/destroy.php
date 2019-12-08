<?php

include_once(dirname(__DIR__) . '/_config.php');
if (session_status() === PHP_SESSION_NONE) session_start();

// permissions logic, .check who you are
if (!AUTH) redirect(base_path);
if ($_SESSION['user']['id'] !== $_GET['id'] && !ADMIN) redirect(base_path);

// admins are not allowed to delete themselves
if (ADMIN && $_SESSION['user']['id'] == $_GET['id']) {
    $_SESSION['flash']['danger'][] = "No KAMAKAZE!!! YOU can not terminate your existence";
    redirect(base_path . "/users");
}

// Get the user if admin and they've passed a get request id
include_once(ROOT . "/includes/_connect.php");
$conn = connect();
$sql = "SELECT * FROM users WHERE id=:id"; // sql string
$stmt = $conn->prepare($sql); // prepare the sql and return the prepared statement
$stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$stmt->execute(); // execute the statement
$user = $stmt->fetch();
$stmt->closeCursor();


//Destroy user
//make the connect with the Mysql
include_once(ROOT . "/includes/_connect.php");
$conn = connect();

//Destroy user
$sql = "DELETE FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$stmt->execute();

// Log them out and send them home if they destroyed themselves
if ($user['id'] === $_SESSION['user']['id']) {
    unset($_SESSION['user']);
    redirect_with_success(base_path . "/", "You have successfully deleted yourself");
}

// If the administrator deleted a user let them know they were deleted successfully
redirect_with_success(base_path . "/users", "You have successfully deleted " . $user['first_name'] . " " . $user['last_name']);
