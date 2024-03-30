<?php
require 'function.php';

if (!empty($_SESSION["id"])) {
  header("Location: index.php");
  exit;
}
$register = new Register();

if (isset($_POST["generate"])) {
  // Perform registration
  $result = $register->registration($_POST["name"], $_POST["username"], $_POST["email"], $_POST["password"], $_POST["confirmpassword"]);

  if ($result == 1) {
    // Registration successful
    echo "<script> alert('Registration Successful'); </script>";
  } elseif ($result == 10) {
    // Username or email already taken
    echo "<script> alert('Username or Email Has Already Taken'); </script>";
  } elseif ($result == 100) {
    // Passwords do not match
    echo "<script> alert('Password Does Not Match'); </script>";
  }
  if ($result == 1) {
    $name = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $data = array(
      'name' => $name,
      'password' => $password
    );
    $jsonData = json_encode($data);
    $urlEncodedData = urlencode($jsonData);
    $qrCodeURL = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$urlEncodedData&choe=UTF-8";
    echo "<img style='position:absolute' src='$qrCodeURL'>";
  }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>Registration</title>
  <link rel="stylesheet" href="css/register.css">
</head>

<body>
  <form action="" method="post">
    <h2>Registration</h2>
    <label for="name">Name</label>
    <input type="text" name="name">
    <label for="username">Username</label>
    <input type="text" name="username" required> <br>
    <label for="email">Email</label>
    <input type="email" name="email" required> <br>
    <label for="password">Password</label>
    <input type="password" name="password" required> <br>
    <label for="confirmpassword">Confirm Password</label>
    <input type="password" name="confirmpassword" required> <br>
    <button type="submit" name="generate">Register</button>
  </form>
  <br>
  <a href="login.php">Login</a>
</body>

</html>