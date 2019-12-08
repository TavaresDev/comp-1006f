<?php include_once(dirname(__DIR__) . '/_config.php') ?>
<?php not_admin_redirect(base_path . '/posts') ?>

<?php
  $_title = "New Blog";
  $_active = "posts";
?>
<?php include_once(ROOT . '/partials/_header.php') ?>

<div class="container">
  <header class="mt-5">
    <h1>
      Create New Blog
    </h1>
  </header>

  <!-- Step 2: Include our form -->
  <?php include_once(ROOT . "/posts/_form.php") ?>

  <p>
    <a href="<?= base_path ?>/posts">Return to archives...</a>
  </p>
</div>

<hr class="m-5">


<?php include_once(ROOT . '/partials/_footer.php') ?>