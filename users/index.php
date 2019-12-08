<!-- page for admin,. that shows all he users -->
<?php include_once(dirname(__DIR__) . '/_config.php') ?>

<?php
// Check if the user is even authenticated first. If not, move them along
if (session_status() === PHP_SESSION_NONE) session_start();
not_admin_redirect(base_path);
?>
<?php
// Get the users
include_once(ROOT . '/includes/_connect.php');
$conn = connect();
//$users = $conn->query($sql); which will prepare, execute and fetchAll in one command
$sql = "SELECT * FROM users ORDER BY created_at DESC"; // sql string
//define $user with all fetched users database
$users = $conn->query($sql)->fetchAll(); // fetch all the records returned
?>

<?php
// Step 2: Define the $_title and $_active link
$_title = "List All Users";
$_active = "users";
?>

<!-- Step 3: Include the _header.php file -->
<?php include_once(ROOT . "/partials/_header.php") ?>

<div class="container">
  <header class="mt-5">
    <h1>
      <?= $_title ?>
    </h1>
    <hr>
    <small>
      <a href="<?= base_path ?>/users/new.php">
        <i class="fa fa-plus"></i>&nbsp;New user...
      </a>
    </small>
  </header>

  <?php if (count($users) > 0) :  ?>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Created On</th>
        </tr>
      </thead>

      <tbody>
        <!-- Iterate over the users and display their details -->
        <?php foreach ($users as $user) : ?>
          <tr>
            <td>
              <a href="<?= base_path ?>/users/show.php?id=<?= $user['id'] ?>">
                <?= $user['first_name'] ?> <?= $user['last_name'] ?>
              </a>
            </td>
            <td>
              <?= $user['email'] ?>
            </td>
            <td>
              <?= date("d/m/Y", strtotime($user['created_at'])) ?>
              <br>
              <?= date("g:i a", strtotime($user['created_at'])) ?>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  <?php else : ?>
    <div class="alert alert-warning">
      <h4 class="alert-heading">
        NO USERS!
      </h4>
      <hr>
      <p>Perhaps you should create a new one...</p>
    </div>
  <?php endif ?>
</div>

<?php include_once(ROOT . "/partials/_footer.php") ?>