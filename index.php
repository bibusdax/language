<?php
session_start();

// Check if the user is already logged in
if(isset($_SESSION['username'])) {
  header('Location: dashboard.php');
  exit();
}

// Check if the login form has been submitted
if(isset($_POST['username']) && isset($_POST['password'])) {
  // Validate the input fields
  if(empty($_POST['username']) || empty($_POST['password'])) {
    $error = "Please enter your username and password.";
  } else {
    // Check if the user exists in the users.csv file
    $users_file = fopen('users.csv', 'r');
    while(($user = fgetcsv($users_file, 1000, ';')) !== FALSE) {
      if($user[0] == $_POST['username']) {
        if(password_verify($_POST['password'], $user[1])) {
          $_SESSION['username'] = $_POST['username'];
          // Check if the "Remember Me" checkbox was checked
          if(isset($_POST['remember_me']) && $_POST['remember_me'] == 'on') {
            setcookie('username', $_POST['username'], time() + (86400 * 30), '/');
          }
          header('Location: dashboard.php');
          exit();
        } else {
          $error = "Invalid password.";
          break;
        }
      }
    }
    fclose($users_file);
    $error = "Invalid username.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Language App</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <?php include 'navbar.php'; ?>

  <div class="container">
    <h1>Login</h1>
    <?php
      if(isset($error)) {
        echo '<div class="error">' . $error . '</div>';
      }
    ?>
    <form method="post" action="">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" placeholder="Enter your username">

      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Enter your password">

      <input type="checkbox" id="remember_me" name="remember_me">
      <label for="remember_me">Remember Me</label>

      <button type="submit">Login</button>
    </form>
  </div>

</body>
</html>

