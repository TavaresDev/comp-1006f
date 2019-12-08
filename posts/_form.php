<!-- Step 1: Add our config file -->
<?php include_once(dirname(__DIR__) . '/_config.php') ?>

<!-- Step 5: Only admins can create a post  -->
<?php if (!ADMIN) redirect(base_path) ?>

<!-- if there is no id redirect/ why this here? -->
<?php 
// if (!isset($post['id'])) redirect("/posts") 
?>

<!-- Step 2: Assign any existing form data or null -->
<?php $form_data = $form_data ?? null ?>

<!-- Step 3: Assign the action -->
<?php $_action = $_action ?? base_path . "/posts/create.php" ?>

<form action="<?= $_action ?>" method="post" enctype="multipart/form-data">
  <!-- hidden field for  -->
  <?php if (isset($_GET['id'])): ?>
    <input type="hidden" name="id" value="<?= $form_data['id'] ?>">
  <?php endif ?>

  <div class="form-group col">
    <label for="title">Title:</label>
    <!-- Step 4: Add value if there's a value in form_data for this field -->
    <input type="text" class="form-control" name="title" placeholder="Enter Post Title" value="<?= $form_data['title'] ?? null ?>">
  </div>

  <div class="form-group col">
    <label for="title">Status:</label>
    <!-- Step 5: Select value if the option matches the form_data for this field -->
    <select name="status" class="form-control">
      <option value="draft" <?= (isset($form_data['status']) && $form_data['status'] === "draft") ? 'selected' : null ?>>Draft</option>
      <option value="published" <?= (isset($form_data['status']) && $form_data['status'] === "published") ? 'selected' : null ?>>Published</option>
    </select>
  </div>

  <div class="form-group col">
    <label for="title">Content:</label>
    <!-- Step 6: Prepopulate the value if there's a value in form_data for this textarea -->
    <textarea name="content" class="summernote">
      <?= $form_data['content'] ?? null ?>
    </textarea>
  </div>
  <!-- fileeeeeeeeeeeee -->
  <!-- <div class="custom-file">
    <input id="image" name="image" type="file" class="custom-file-input">
    <label for="image" class="custom-file-label">Chose File</label>
  </div> -->

  <button class="btn btn-primary" type="submit">Submit</button>
</form>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    $(".summernote").summernote({
      toolbar: [
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough', 'superscript', 'subscript', 'fontname', 'fontsize']],
        ['color', ['color']],
        ['para', ['style', 'ul', 'ol', 'paragraph']],
        ['misc', ['fullscreen', 'codeview', 'undo', 'redo']]
      ],
      height: 300
    });
  });
</script>