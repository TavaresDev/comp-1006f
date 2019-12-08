<?php include_once(dirname(__DIR__) . '/_config.php') ?>

<?php
// User's can't view their profile unless logged in
// User's can't view other users profiles unless they're administrators
if (!AUTH || (AUTH && $_GET['id'] !== $_SESSION['user']['id'] && !ADMIN)) {
  redirect(base_path);
}
?>

<?php
//make sure the session started
if (session_status() === PHP_SESSION_NONE) session_start();

//Include and connect to database
include_once(ROOT . "/includes/_connect.php"); //connect to database 
$conn = connect();

// Step 1: Get the user by the ID 
$sql = "SELECT * FROM users WHERE id = :id"; //sql string
$stmt = $conn->prepare($sql); //prepare the sql and return the prepared statement
// request id, validate that is int, and bind to :id
$stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT); //Bind and validation
$stmt->execute(); //execute the stmt (query)
$user = $stmt->fetch(); //fetch a single record(row)
?>


<?php
$_title = $user['first_name'] . " " . $user['last_name'] . " " . "Profile";
$_active = "profile";
?>

<?php include(ROOT . '/partials/_header.php') ?>

<div class="container">
  <header class="mt-5">
    <h1>
      User - <?= $user['first_name'] ?> <?= $user['last_name'] ?>
    </h1>
    <hr>

    <!-- Step 2: Check if the user is an admin role  -->
    <!-- Only show the back link if the user is an administrator -->
    <?php if (ADMIN) : ?>
      <small>
        <a href="./"><i class="fa fa-chevron-left"></i>&nbsp;Back to users...</a>
      </small>
      <!-- Step 3: end if  -->
    <?php endif ?>
  </header>

  <div class="row">
    <div class="col-4">
      <img src="<?= $user['avatar'] ?>">
    </div>

    <div class="col-4">
      <table class="table table-striped">
        <tbody>
          <tr>
            <th>Name:</th>
            <td><?= $user['first_name'] ?> <?= $user['last_name'] ?></td>
          </tr>
          <tr>
            <th>Email:</th>
            <td><?= $user['email'] ?></td>
          </tr>
          <tr>
            <th>Created On:</th>
            <td>
              <?= date("d/m/Y", strtotime($user['created_at'])) ?>
              <br>
              <?= date("g:i a", strtotime($user['created_at'])) ?>
            </td>
          </tr>
          <?php if (ADMIN) : ?>
            <tr>
              <th>Role: </th>
              <td>
                <?= $user['role'] ?>
              </td>
            <tr>
            <?php endif ?>
        </tbody>
      </table>
      <div>

        <small>
          <a href="<?= base_path ?>/users/edit.php?id=<?= ADMIN ? $_GET['id'] : $_SESSION['user']['id'] ?>">
            <i class="fa fa-pencil">&nbsp;</i>
            Edit your profile...
          </a>
          |
          <a href="<?= base_path ?>/users/destroy.php?id=<?= ADMIN ? $_GET['id'] : $_SESSION['user']['id'] ?>" onclick="return confirm('Are you sure you want to delete your own profile?')">
            <i class="fa fa-remove">&nbsp;</i>
            Delete your profile...
          </a>
        </small>
      </div>
    </div>
  </div>
</div>

<?php include(ROOT . '/partials/_footer.php') ?>