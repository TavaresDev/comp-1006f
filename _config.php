<!-- file to normalize the file system making all URLs to absolute URLs  -->
<!-- good practice, solve several problems -->
<?php
  // Step 1: Define the Directory Root (To navigate the folders)
  // dirname(__FILE__) return the full path to the folder where __FILE__ is
  define("ROOT", dirname(__FILE__)); //PHP way of declaring constant ROOT

  // to debug check the path
  // echo __FILE__ . "<br>";
  // echo dirname (__FILE__ ) . '<br>';

  // Step 2: Define the HTML Path Root (links, navigate URLs). use to anchor links
  //"comp-10006" is (the directory we're replacing with will be the directory you've put in your www folder)
  define('base_path', str_replace(dirname(__DIR__), "/comp-1006", ROOT));

  // to check the path
  // echo __DIR__ . '<br>';
  // echo dirname(__DIR__) . '<br>';

  //include helpers
  include_once(ROOT . '/includes/_authentication_helpers.php');
  // AUTH and ADMIN constants for authentication checks
  define('AUTH', is_auth());
  define('ADMIN', is_admin());
  // include common helpers
  include_once(ROOT . '/includes/_helpers.php');

?>