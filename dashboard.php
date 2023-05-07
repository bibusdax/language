<?php
session_start();
if(!isset($_SESSION['user'])){
    header('location:index.php');
}

$dir = "lang/";
$files = scandir($dir);
$files = array_diff($files, array('.', '..'));

if(isset($_POST['upload'])){
    $filename = $_FILES['file']['name'];
    move_uploaded_file($_FILES['file']['tmp_name'], $dir.$filename);
    header('location:dashboard.php');
}

if(isset($_GET['delete'])){
    unlink($dir.$_GET['delete']);
    header('location:dashboard.php');
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Language Training - Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'navbar.php' ?>

    <div class="container">
        <h2>Language Files</h2>
        <table>
            <thead>
                <tr>
                    <th>Filename</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($files as $file): ?>
                    <tr>
                        <td><?php echo $file ?></td>
                        <td>
                            <a href="manage_files.php?file=<?php echo $file ?>">Edit</a>
                            <a href="train.php?file=<?php echo $file ?>">Train</a>
                            <a href="dashboard.php?delete=<?php echo $file ?>" onclick="return confirm('Are you sure you want to delete this file?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h2>Upload File</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="file" required>
            <br>
            <button type="submit" name="upload">Upload</button>
        </form>
    </div>
</body>

</html>

