<?php
session_start();

require_once 'vendor/autoload.php';
use App\Controllers\AuthController;

$authController = new AuthController();

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

// If form is submitted, try to log in the user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $authController->login($_POST);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="submit" value="Log in">
        </form>
    </div>
</body>
</html>
