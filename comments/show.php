<?php include_once(dirname(__DIR__) . '/_config.php') ?>

<?php
	// Get the posts (but we'll also need the author)
	include_once(ROOT . "/includes/_connect.php");
	$conn = connect();

	$sql = "SELECT *, comments.id as id FROM comments
					JOIN users ON comments.user_id = users.id
					WHERE comments.id = :id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
	$stmt->execute();
	$comment = $stmt->fetch();
?>

<?php include_once(ROOT . '/partials/_header.php') ?>
<article class="container">
	<div>
		<header class="mt-5">
			<h1>
				<?= $comment['title'] ?>
				<br>
				<small>~<?= $comment['first_name'] ?> <?= $comment['last_name'] ?></small>
			</h1>
		</header>

		<hr class="m-5">

		<div class="row">
			<div class="col-4">
				<img src="<?= $comment['avatar'] ?>" alt="adf" class="img-fluid">
			</div>

			<div class="col-8">
				<section>
					<p><?= $comment['comment'] ?></p>
				</section>

				<p>
					<a href="<?= base_path ?>/posts/show.php?id=<?= $comment['post_id'] ?>#comments">Return to blog...</a>
				</p>
			</div>
		</div>

		<hr class="m-5">
	</div>
</article>

<?php include_once(ROOT . '/partials/_footer.php') ?>