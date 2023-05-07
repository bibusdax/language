<?php

session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

// Check if form is submitted
if (isset($_POST['original']) && isset($_POST['translation'])) {
  $original = trim($_POST['original']);
  $translation = trim($_POST['translation']);
  $langfile = trim($_POST['langfile']);

  // Check if file exists and is writable
  if (is_writable($langfile)) {
    $fp = fopen($langfile, 'a');
    fwrite($fp, $original . ";" . $translation . "\n");
    fclose($fp);
    $_SESSION['success'] = "Word added successfully";
  } else {
    $_SESSION['error'] = "Cannot write to file";
  }
  header("Location: dashboard.php");
  exit();
}

// Check if form is submitted for search
if (isset($_POST['search']) && isset($_POST['langfile'])) {
  $search = trim($_POST['search']);
  $langfile = trim($_POST['langfile']);

  // Check if file exists and is readable
  if (is_readable($langfile)) {
    $fp = fopen($langfile, 'r');
    $found = false;
    while (($line = fgets($fp)) !== false) {
      if (strpos($line, $search) !== false) {
        $_SESSION['result'][] = $line;
        $found = true;
      }
    }
    fclose($fp);
    if (!$found) {
      $_SESSION['error'] = "No matches found";
    }
  } else {
    $_SESSION['error'] = "Cannot read file";
  }
  header("Location: dashboard.php");
  exit();
}

// Check if form is submitted for delete
if (isset($_POST['delete']) && isset($_POST['langfile'])) {
  $index = $_POST['delete'];
  $langfile = trim($_POST['langfile']);

  // Check if file exists and is writable
  if (is_writable($langfile)) {
    $lines = file($langfile);
    unset($lines[$index]);
    $fp = fopen($langfile, 'w');
    fwrite($fp, implode("", $lines));
    fclose($fp);
    $_SESSION['success'] = "Word deleted successfully";
  } else {
    $_SESSION['error'] = "Cannot write to file";
  }
  header("Location: dashboard.php");
  exit();
}

// If no form is submitted, redirect to dashboard
header("Location: dashboard.php");
exit();

?>

