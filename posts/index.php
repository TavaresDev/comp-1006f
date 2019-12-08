<?php include_once(dirname(__DIR__) . '/_config.php') ?>

<?php
// get the posts and the user (author)
include_once(ROOT . "/includes/_connect.php");
$conn = connect();

$current_page = $_GET['page'] ?? 1;
$num_of_posts = 10;
$offset = $num_of_posts * ($current_page - 1);

// my sql fetch without using user date is simpler
// and limiting the size for pagination
$sql = "SELECT *, posts.id as post_id FROM posts JOIN users ON posts.user_id = users.id LIMIT {$offset}, {$num_of_posts}";
$posts = $conn->query($sql)->fetchAll();

$post_count = $conn->query("SELECT * FROM posts")->rowCount(); // how many rows do we have?
$num_of_pages = round($post_count / $num_of_posts); // how many pages will we need?


?>

<?php include_once(ROOT . "/partials/_header.php") ?>

<div class="container">
	<header class="mt-5">
		<h1>
			My Blogs (Archives)
			<br>
			<small>the day in a life...</small>
		</h1>
	</header>
</div>
<hr class="m-5">

<div class="container">
	<div class="archives">
		<?php foreach ($posts as $post) : ?>
      <div class="card mb-2">
        <div class="card-body d-flex flex-row justify-content-between align-items-center">
          <div class="mr-4">
          <div class="row">
            <div class="col-3">
              <img src="https://via.placeholder.com/350x300" alt="asdf" class="img-fluid img-thumbnail">
            </div>
            <div class="col-9">
              <div style="width: 100%;" class="clearfix"><small class="pull-right"><?= $post['updated_at'] ?></small></div>
              <div class="card-title mt-3">
                <h3>
                  <a href="<?= base_path ?>/posts/show.php?id=<?= $post['post_id'] ?>"><?= $post['title'] ?></a>
                </h3>
                <hr>
                <small>Author: <?= $post['first_name'] ?> <?= $post['last_name'] ?></small>
              </div>
              <p class="card-text">
                <?= substr($post['content'], 0, 400) ?>... <a href="<?= base_path ?>/posts/show.php?id=<?= $post['post_id'] ?>">read more <i class="fa fa-hand-o-right"></i></a>
              </p>

              <?php if (ADMIN && $_SESSION['user']['id'] === $post['user_id']): ?>
                <div>
                  <a href="<?= base_path ?>/posts/edit.php?id=<?= $post['id'] ?>">
                    <i class="fa fa-pencil"></i>
                    edit
                  </a>
                  |
                  <a href="<?= base_path ?>/posts/destroy.php?id=<?= $post['id'] ?>" onclick="return confirm('Are you sure you want to delete your own profile?')">
                    <i class="fa fa-trash"></i>
                    delete
                  </a>
                </div>
              <?php endif ?>
            </div>
          </div>
        </div>
      </div>
		<?php endforeach ?>
	</div>
</div>
<hr>
<!-- pagination -->
<nav class="mt-5" aria-label="Page navigation">
  <ul class="pagination justify-content-center">
  <li class="page-item <?= $current_page == 1 ? 'disabled' : null ?>">
      <a href="?page=<?= $current_page - 1 ?>" class="page-link">Previous</a>
    </li>

    <?php for ($i = 1; $i <= $num_of_pages; $i++): ?>
      <li class="page-item <?= $i == $current_page ? 'active' : null ?>">
        <a href="?page=<?= $i ?>" class="page-link"><?= $i ?></a>
      </li>
    <?php endfor ?>
    <li class="page-item <?= $current_page == $num_of_pages ? 'disabled' : null ?>">
      <a href="?page=<?= $current_page + 1 ?>" class="page-link">Next</a>
    </li>

  </ul>
</nav>

<?php include_once(ROOT . "/partials/_footer.php") ?>