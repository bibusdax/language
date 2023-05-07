<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
$filename = $_SESSION["filename"];
$file = file("lang/$filename", FILE_IGNORE_NEW_LINES);
$numlines = count($file);
if (isset($_SESSION["original_to_foreign"])) {
    $otf = $_SESSION["original_to_foreign"];
} else {
    $_SESSION["original_to_foreign"] = $otf = true;
}
if (isset($_POST["otf"])) {
    $_SESSION["original_to_foreign"] = $otf = ($_POST["otf"] === "true");
}
if (isset($_POST["learned"])) {
    $learned = intval($_POST["learned"]);
    if ($learned >= 0 && $learned < $numlines) {
        unset($file[$learned]);
        file_put_contents("lang/$filename", implode("\n", $file));
    }
}
if ($numlines > 0) {
    $line = $file[rand(0, $numlines - 1)];
    list($original, $foreign) = explode(";", $line);
    if ($otf) {
        $question = $original;
        $answer = $foreign;
    } else {
        $question = $foreign;
        $answer = $original;
    }
} else {
    $question = "No words to train!";
    $answer = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Language Training</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php include "navbar.php" ?>
<h2>Training</h2>
<form method="post" action="train.php">
    <p>Translate <b><?php echo $question ?></b> to:</p>
    <p>
        <input type="radio" id="otf1" name="otf" value="true"<?php if ($otf) echo " checked" ?>>
        <label for="otf1">Foreign language</label>
        <input type="radio" id="otf2" name="otf" value="false"<?php if (!$otf) echo " checked" ?>>
        <label for="otf2">Original language</label>
    </p>
    <p><input type="text" id="answer" name="answer" autofocus></p>
    <p><button type="submit">Check answer</button></p>
    <input type="hidden" name="question" value="<?php echo $question ?>">
    <input type="hidden" name="answer" value="<?php echo $answer ?>">
</form>
<script src="script.js"></script>
</body>
</html>

