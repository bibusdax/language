<?php

session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
	header("location: login.php");
	exit;
}

// Include language functions
require_once "functions.php";

// Get filename from URL parameter
$filename = $_GET['filename'];

// Check if file exists
if (!file_exists("lang/$filename")) {
	header("location: manage_files.php?error=File not found.");
	exit;
}

// Load file contents into array
$file_contents = file("lang/$filename", FILE_IGNORE_NEW_LINES);

// Check if form submitted to add new line to file
if (isset($_POST['add_line'])) {
	// Get original and foreign language from form data
	$original = $_POST['original'];
	$foreign = $_POST['foreign'];

	// Add line to file contents array
	$file_contents[] = "$original;$foreign";

	// Redirect to page with success message
	header("location: edit_file.php?filename=$filename&message=Line added successfully.");
	exit;
}
// Check if form submitted to remove line from file
if (isset($_POST['remove_line'])) {
	// Get index of line to remove from form data
	$line_index = $_POST['line_index'];

	// Remove line from file contents array
	unset($file_contents[$line_index]);

	// Redirect to page with success message
	header("location: edit_file.php?filename=$filename&message=Line removed successfully.");
	exit;
}

// Calculate pagination values
$num_lines = count($file_contents);
$lines_per_page = 10;
$num_pages = ceil($num_lines / $lines_per_page);
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$page_start = ($current_page - 1) * $lines_per_page;
$page_end = $page_start + $lines_per_page;
$page_lines = array_slice($file_contents, $page_start, $lines_per_page);

// Check if form submitted to search for a term
if (isset($_POST['search_term'])) {
	// Get search term from form data
	$search_term = $_POST['search_term'];

	// Filter file contents array by search term
	$file_contents = array_filter($file_contents, function($line) use ($search_term) {
		list($original, $foreign) = explode(";", $line);
		return strpos(strtolower($original), strtolower($search_term)) !== false
			|| strpos(strtolower($foreign), strtolower($search_term)) !== false;
	});

	// Reset pagination values
	$num_lines = count($file_contents);
	$num_pages = ceil($num_lines / $lines_per_page);
	$current_page = 1;
	$page_start = 0;
	$page_end = $lines_per_page;
	$page_lines = array_slice($file_contents, $page_start, $lines_per_page);
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Edit Language File - <?php echo $filename; ?></title>
	<link rel="stylesheet" href="style.css">
	<script src="script.js"></script>
</head>
<body>
	<?php include "navbar.php"; ?>

	<div class="container">
		<h1>Edit Language File - <?php echo $filename; ?></h1>
		<?php if (isset($_GET['message'])): ?>
			<div class="alert success"><?php echo $_GET['message']; ?></div>
		<?php endif; ?>

		<form method="post" class="search-form">
			<input type="text" name="search_term" placeholder="Search...">
			<button type="submit">Search</button>
		</form>

		<table>
			<thead>
				<tr>
					<th>Original Language</th>
					<th>Foreign Language</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($page_lines as $index => $line): ?>
					<?php list($original, $foreign) = explode(";", $line); ?>
					<tr>
						<td><?php echo $original; ?></td>
						<td><?php echo $foreign; ?></td>
						<td class="actions">
							<form method="post">
								<input type="hidden" name="line_index" value="<?php echo $page_start + $index; ?>">
								<button type="submit" name="remove_line" class="remove-button" onclick="return confirm('Are you sure you want to remove this line?')">Remove</button>
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<div class="pagination">
			<?php if ($current_page > 1): ?>
				<a href="?filename=<?php echo $filename; ?>&page=<?php echo $current_page - 1; ?>">&laquo; Previous</a>
			<?php endif; ?>

			<?php for ($i = 1; $i <= $num_pages; $i++): ?>
				<a href="?filename=<?php echo $filename; ?>&page=<?php echo $i; ?>" <?php if ($i === $current_page) echo 'class="active"'; ?>><?php echo $i; ?></a>
			<?php endfor; ?>

			<?php if ($current_page < $num_pages): ?>
				<a href="?filename=<?php echo $filename; ?>&page=<?php echo $current_page + 1; ?>">Next &raquo;</a>
			<?php endif; ?>
		</div>

		<form method="post" class="add-form">
			<h2>Add Line</h2>
			<label for="original">Original Language:</label>
			<input type="text" name="original" required>
			<label for="foreign">Foreign Language:</label>
			<input type="text" name="foreign" required>
			<button type="submit" name="add_line">Add Line</button>
		</form>

		<form method="post" class="edit-form">
			<h2>Edit File</h2>
			<textarea name="file_contents"><?php echo implode("\n", $file_contents); ?></textarea>
			<button type="submit" name="save_file">Save File</button>
		</form>
	</div
	<?php if (isset($_GET['edit_line'])): ?>
		<form method="post" class="edit-line-form">
			<h2>Edit Line</h2>
			<input type="hidden" name="line_index" value="<?php echo $_GET['edit_line']; ?>">
			<label for="original">Original Language:</label>
			<input type="text" name="original" value="<?php echo $originals[$_GET['edit_line']]; ?>" required>
			<label for="foreign">Foreign Language:</label>
			<input type="text" name="foreign" value="<?php echo $foreigns[$_GET['edit_line']]; ?>" required>
			<button type="submit" name="edit_line">Save Changes</button>
		</form>
	<?php endif; ?>

	<script>
		// Add event listener to edit buttons to redirect to edit page
		const editButtons = document.querySelectorAll(".edit-button");
		for (const editButton of editButtons) {
			editButton.addEventListener("click", function() {
				const lineIndex = editButton.dataset.lineIndex;
				window.location.href = `edit_file.php?filename=<?php echo $filename; ?>&edit_line=${lineIndex}`;
			});
		}
	</script>
</body>
</html>

