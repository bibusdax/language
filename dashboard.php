<?php
session_start();

require_once 'vendor/autoload.php';
use App\Controllers\LanguageController;

// If user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$languageController = new LanguageController();

// Get list of available language files
$languages = $languageController->getLanguages();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <h2>Select a language to learn:</h2>
        <ul>
            <?php foreach ($languages as $language): ?>
                <li><a href="train.php?file=<?php echo urlencode($language); ?>"><?php echo $language; ?></a></li>
            <?php endforeach; ?>
        </ul>
        <a href="edit.php">Edit language file</a>
    </div>
</body>
</html>
