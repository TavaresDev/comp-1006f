<?php include_once(dirname(__DIR__) . '/_config.php') ?>
<?php not_admin_redirect(base_path . '/posts') ?>

<?php
  // Get the post
  include_once(ROOT . "/includes/_connect.php");
  $conn = connect();
  $sql = "SELECT * FROM posts WHERE id=:id"; // sql string
  $stmt = $conn->prepare($sql); // prepare the sql and return the prepared statement
  $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
  $stmt->execute(); // execute the statement
  $post = $stmt->fetch();

  $_SESSION['form_data'] = $post;


  // Admins can only edit their own posts
  if ($_SESSION['user']['id'] !== $post['user_id']) {
      $_SESSION['flash']['danger'][] = "You may only edit posts you own.";
      redirect(base_path . "/posts");
      }

  ?>

  <?php
  $_title = "Edit Blog";
  $_active = "posts";
  $_action = base_path . "/posts/update.php";
  ?>

<?php include_once(ROOT . '/partials/_header.php') ?>

<div class="container">
  <header class="mt-5">
    <h1>
      <?= $_title ?>
    </h1>
  </header>

  <?php include_once(ROOT . '/posts/_form.php') ?>

    <hr>
      <p>
        <a href="<?= base_path ?>/posts"><i class="fa fa-chevron-left"></i>&nbsp;Back to archives...</a>
      </p>

  <?php include_once(ROOT . '/partials/_footer.php') ?>

     
