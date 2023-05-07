<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Language Training App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Language Training App</h1>
        </div>
        <div class="content">
            <h2>Login</h2>
            <form method="post" action="process.php">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
                <br><br>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
                <br><br>
                <button type="submit" name="submit">Login</button>
            </form>
            <?php if(isset($_SESSION['message'])): ?>
                <p class="error"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

