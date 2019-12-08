<?php include_once(dirname(__DIR__) . '/_config.php') ?>
<?php
  // If the user is attempting to edit and their not authenticated
  // or they're attempting to edit another user and they're not an admin
  if (isset($_action) && (!AUTH || ($_GET['id'] !== $_SESSION['user']['id'] && !ADMIN))) {
    redirect(base_path);
  } else if (!isset($_action) && AUTH && !ADMIN) { // If the user is attempting to create
    // Only admins can create new users while logged in
    redirect(base_path);
  }
  ?>

<!-- redirect block direct access to the form , not necessary but gp -->
<?php
  // This will redirect if user accesses the form directly
  $path = $_SERVER['REQUEST_URI']; // get's request path
  $path_parts = explode("/", $path); // splits path into parts array
  $file_name = end($path_parts); // grabs last element in the array (the filename)
  if ($file_name === "_form.php") redirect(base_path); // redirects if filename is the same as the form
?>

<?php 
  $form_data = $form_data ?? null;
  // define $_action to change the path when editing the user _form,
  $_action = $_action ?? base_path . "/users/create.php";
?>

<!-- Step 1: Add the action and HTTP method to the form attributes -->
<!-- the name on the input should be the same as the collum in the database  -->
<form action="<?= $_action ?? base_path . "/users/create.php" ?>" method="post">
    <div class="row">
      <!-- xxx chek this if an value in form -->
  <?php if (isset($_action)): ?>
    <!-- hidden input to pass the user id -->
    <input type="hidden" name="id" value="<?= $form_data['id'] ?>"> 
  <?php endif ?>

    <div class="form-group col">
      <label for="first_name">First Name:</label>
      <!-- Step 2: Add the name attribute to the input element -->
      <!-- Step 3: Add the default value if it exists -->
      <!-- Step 4: Add the client-side validation option -->
      <input type="text" class="form-control" id="first_name" name="first_name" required placeholder="Enter First Name" value="<?= $form_data['first_name'] ?? null ?>">
    </div>

    <div class="form-group col">
      <label for="last_name">Last Name:</label>
      <!-- Step 2: Add the name attribute to the input element -->
      <!-- Step 3: Add the default value if it exists -->
      <!-- Step 4: Add the client-side validation option -->
      <input type="text" class="form-control" id="last_name" name="last_name" required placeholder="Enter Last Name" value="<?= $form_data['last_name'] ?? null ?>">
    </div>
  </div>

  <div class="form-group">
    <label for="email">Email:</label>
    <!-- Step 2: Add the name attribute to the input element -->
    <!-- Step 3: Add the default value if it exists -->
    <!-- Step 4: Add the client-side validation option -->
    <input type="text" class="form-control" id="email" name="email" required placeholder="Enter Email" value="<?= $form_data['email'] ?? null ?>">
  </div>

  <div class="form-group">
    <label for="password">Password:</label>
    <!-- Step 2: Add the name attribute to the input element -->
    <!-- Step 4: Add the client-side validation option -->
    <input type="password" class="form-control" id="password" name="password">
  </div>

  <div class="form-group">
  <!-- Step 2: Add the name attribute to the input element -->
  <!-- Step 4: Add the client-side validation option -->
    <label for="password_confirmation">Password Confirmation:</label>
    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
  </div>

  <button class="btn btn-primary" type="submit">Submit</button>
</form>

<!-- Below is a script that will grab a new avatar and load it when you change your email address -->
<script>
  const emailField = document.querySelector('[name="email"]');
  const avatar = document.querySelector('#avatar');
  email.addEventListener('change', function () {
    const email = emailField.value;
    avatar.src = `http://api.adorable.io/avatars/300/${email}`;
    avatar.classList.remove('invisible');
  });
</script>