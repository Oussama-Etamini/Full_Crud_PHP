<?php
require 'function.php';

$select = new Select();

if (!empty($_SESSION["id"])) {
    $user = $select->selectUserById($_SESSION["id"]);
} else {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CRUD Application</title>
    <link rel="stylesheet" href="css/email.css">
</head>
<body>
    <form action="mailer.php" method="post">
        <h2>Send Email</h2>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="message">Message:</label>
        <textarea name="message" id="message" required rows="" cols=""></textarea>

        <button type="submit">Send</button>
        <a href="index.php">cancel</a>
    </form>
</body>
</html>