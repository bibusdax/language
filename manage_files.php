<?php
// Start session to access session data
session_start();

// Check if user is logged in
if (!isset($_SESSION["username"])) {
	header("Location: index.php");
	exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Get action from form data
	$action = $_POST["action"];
	$filename = $_POST["filename"];

	// Perform action based on user input
	if ($action == "create") {
		// Create a new language file
		$filedata = "Original Language;Foreign Language\n";
		file_put_contents("lang/$filename.csv", $filedata);
	} elseif ($action == "modify") {
		// Modify an existing language file
		// Redirect to edit_file.php with filename as parameter
		header("Location: edit_file.php?filename=$filename");
		exit();
	} elseif ($action == "copy") {
		// Copy an existing language file to a new file
		// Redirect to edit_file.php with new filename as parameter
		$new_filename = $filename . "_copy";
		copy("lang/$filename.csv", "lang/$new_filename.csv");
		header("Location: edit_file.php?filename=$new_filename");
		exit();
	} elseif ($action == "delete") {
		// Delete an existing language file
		unlink("lang/$filename.csv");
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Manage Language Files</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Import CSS styles -->
</head>
<body>
	<div class="container">
		<h1>Manage Language Files</h1>
		<!-- Display list of language files -->
		<table>
			<thead>
				<tr>
					<th>Filename</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				// Get list of language files
				$lang_files = glob("lang/*.csv");

				foreach ($lang_files as $lang_file) {
					$filename = basename($lang_file, ".csv");
					echo "<tr>";
					echo "<td>$filename</td>";
					echo "<td>";
					echo "<form action=\"manage_files.php\" method=\"post\">";
					echo "<input type=\"hidden\" name=\"filename\" value=\"$filename\">";
					echo "<select name=\"action\">";
					echo "<option value=\"modify\">Modify</option>";
					echo "<option value=\"copy\">Copy</option>";
					echo "<option value=\"delete\">Delete</option>";
					echo "</select>";
					echo "<button type=\"submit\">Submit</button>";
					echo "</form>";
					echo "</td>";
					echo "</tr>";
				}
				?>
			</tbody>
		</table>
		<!-- Display form to create new language file -->
		<h2>Create New Language File</h2>
		<form action="manage_files.php" method="post">
			<label for="filename">Filename:</label>
			<input type="text" name="filename" id="filename" required>
			<input type="hidden" name="action" value="create">
			<button type="submit">Create</button>
		</form>
	</div>
</body>
</html>

