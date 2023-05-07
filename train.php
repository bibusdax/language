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

// If file parameter is not set, redirect to dashboard
if (!isset($_GET['file'])) {
    header('Location: dashboard.php');
    exit();
}

$file = $_GET['file'];

// If file does not exist, redirect to dashboard
if (!$languageController->languageFileExists($file)) {
    header('Location: dashboard.php');
    exit();
}

$languageController->loadLanguageFile($file);

// Initialize session variables for this language file
if (!isset($_SESSION[$file])) {
    $_SESSION[$file] = array(
        'words' => $languageController->getWords
