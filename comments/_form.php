<!-- Step 1: Include our config file -->
<?php include_once(dirname(__DIR__) . '/_config.php') ?>

<!-- Step 10: Redirect if not authenticated -->
<?php if (!AUTH) redirect(base_path . "/posts") ?>

<!-- Step 11: Redirect if the $post['id'] is missing -->
<?php if (!isset($post['id'])) redirect("/posts") ?>

<!-- Step 2: Assign our form_data if it exists -->
<?php $form_data = $form_data ?? null ?>



<!-- form -->
<div class="row mb-5">
  <div class="col-sm-3">
    <!-- Step 4: Set the image source to the user avatar from the session -->
    <img class="img-fluid img-thumbnail mr-2" src="<?= $_SESSION['user']['avatar'] ?>" alt="avatar">
  </div>
  <div class="col-sm-9">
    <!-- Step 6: Set the action and method -->
    <form action= "<?= base_path . "/comments/create.php" ?>" method="post">
      <!-- Step 7: Set the post ID -->
      <input type="hidden" name="post_id" value="<?= $post['id']?>">
      
      <div class="form-group">
        <label for="title">Title:</label>
        <!-- Step 8: Set the value if the form_data exists for this field -->
        <input type="text" class="form-control" name="title" placeholder="Enter Title" value="<?= $form_data['title'] ?? null ?>">
      </div>

      <div class="form-group">
        <label for="comment">Comment:</label>
        <!-- Step 9: Set the value if the form_data exists for this field -->
        <textarea type="text" class="form-control" name="comment" rows="5"><?= $form_data['comment'] ?? null ?></textarea>
      </div>

      <div class="form-group clearfix">
        <button class="btn btn-primary pull-right" type="submit">Submit</button>
      </div>
    </form>
  </div>
</div>