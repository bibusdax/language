<?php
// Start session to access session data
session_start();

// Check if user is logged in
if (!isset($_SESSION["username"])) {
	// Redirect to login page
	header("Location: index.php");
	exit();
}

// Check if language file is specified
if (!isset($_GET["file"])) {
	// Redirect to manage files page
	header("Location: manage_files.php");
	exit();
}

// Delete the specified language file
$filename = "lang/" . $_GET["file"];
if (unlink($filename)) {
	// Display success message and redirect to manage files page
	echo "Language file deleted successfully.";
	header("Location: manage_files.php");
	exit();
} else {
	// Display error message and redirect to manage files page
	echo "Error deleting language file.";
	header("Location: manage_files.php");
	exit();
}
?>

