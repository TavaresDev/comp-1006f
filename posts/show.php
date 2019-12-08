<?php include_once(dirname(__DIR__) . '/_config.php') ?>

<?php
  // Get the post (but we'll also need the author)
  include_once(ROOT . "/includes/_connect.php");
  $conn = connect();

  // we use alias so dond have a conflict when the tables join
  $sql = "SELECT *, posts.id as id
          FROM posts
          JOIN users ON posts.user_id = users.id
          WHERE posts.id = :id";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);//need to bind because the user chose the post
  $stmt->execute();
  $post = $stmt->fetch();
  // var_dump()
  
// var_dump($post);
  // two separated querys for simplicity
  // Comments
  $sql = "SELECT *
          FROM comments
          JOIN users ON comments.user_id = users.id
          WHERE comments.post_id = {$post['id']}";
  $comments = $conn->query($sql)->fetchAll();
?>

<!--  we have fetched all data into variables just need to display now -->

<?php include_once(ROOT . '/partials/_header.php') ?>

<article class="container">
  <header class="mt-5">
    <h1>
      <?= $post['title'] ?>
      <br>
      <small>~<?= $post['first_name'] ?> <?= $post['last_name'] ?></small>
    </h1>
  </header>

  <hr class="m-5">

  <section class="ml-5 d-flex flex-row justify-content-between align-items-center">
    <img src="https://via.placeholder.com/350x200" alt="asdf" class="mr-4">
    <p><?= $post['content'] ?></p>
  </section>

  <hr class="m-5">

  <p class="ml-5">
  <a href="<?= base_path ?>/posts">Return to archives...</a>

    <?php if (ADMIN && $_SESSION['user']['id'] == $post['user_id']): ?>
      |
      <a href="<?= base_path ?>/posts/edit.php?id=<?= $post['id'] ?>">
        <i class="fa fa-pencil"></i>
        edit
      </a>
      |
      <a href="<?= base_path ?>/posts/destroy.php?id=<?= $post['id'] ?>" onclick="return confirm('Are you sure you want to delete your own post?')">
        <i class="fa fa-trash"></i>
        delete
      </a>
    <?php endif ?>
  </p>
  </p>
</article>

<!-- Displaying the comments, however, will require iteration. A post can potentially have hundreds of comments. -->
<hr class="m-5">

<!-- include the comments form -->
<div class="container">
  <?php
    if (AUTH) {
      include_once(ROOT . "/comments/_form.php");
    }
  ?>
</div>

<?php if (isset($comments) && count($comments) > 0): ?>
  <div class="container" id="comments">
    <h2>Comments</h2>

    <div class="mt-5">
      <ul class="list-group">
        <?php foreach ($comments as $comment): ?>
          <li class="list-group-item">
            <h5 class="mb-4">
              <?= $comment['title'] ?>
              <small>
                &nbsp;&mdash;&nbsp;<?= $comment['first_name'] ?> <?= $comment['last_name'] ?>
              </small>
            </h5>

            <hr>

            <div class="ml-5 d-flex flex-row justify-content-between align-items-center">
              <img src="<?= $comment['avatar'] ?>" alt="" class="img-fluid img-thumbnail mr-2" style="max-width: 150px; border-radius: 150px;">
              <p>
                <?= $comment['comment'] ?>
              </p>
            </div>
          </li>
        <?php endforeach ?>
      </ul>
    </div>
  </div>
<?php endif ?>

<?php include_once(ROOT . '/partials/_footer.php') ?>