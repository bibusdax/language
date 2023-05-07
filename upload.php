<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Check if file was uploaded successfully
	if (isset($_FILES["file"]) && $_FILES["file"]["error"] == UPLOAD_ERR_OK) {
		// Get uploaded file name and extension
		$filename = $_FILES["file"]["name"];
		$extension = pathinfo($filename, PATHINFO_EXTENSION);
		
		// Check if file extension is valid
		if ($extension == "csv") {
			// Move uploaded file to lang folder
			move_uploaded_file($_FILES["file"]["tmp_name"], "lang/" . $filename);
			
			// Redirect to manage_files.php with success message
			header("Location: manage_files.php?message=success");
			exit();
		} else {
			// Invalid file extension, redirect to manage_files.php with error message
			header("Location: manage_files.php?message=invalid_extension");
			exit();
		}
	} else {
		// File upload failed, redirect to manage_files.php with error message
		header("Location: manage_files.php?message=upload_failed");
		exit();
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Upload Language File</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Import CSS styles -->
</head>
<body>
	<div class="container">
		<h1>Upload Language File</h1>
		<!-- Display file upload form -->
		<form action="upload.php" method="post" enctype="multipart/form-data">
			<label for="file">Select file to upload (CSV format):</label>
			<input type="file" name="file" id="file" accept=".csv" required>
			<button type="submit">Upload</button>
		</form>
	</div>
</body>
</html>

