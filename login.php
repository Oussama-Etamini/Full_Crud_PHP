<?php
require 'function.php';

if (!empty($_SESSION["id"])) {
  header("Location: index.php");
  exit;
}
$login = new Login();

if (isset($_POST["submit"])) {
  $result = $login->login($_POST["username"], $_POST["password"]);
  if ($result == 1) {
    // $_SESSION["login"] = true;
    $_SESSION["id"] = $login->idUser();
    header("Location: index.php");
    exit;
  } elseif ($result == 10) {
    echo "<script> alert('Wrong Password'); </script>";
  } elseif ($result == 100) {
    echo "<script> alert('User Not Registered'); </script>";
  }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link rel="stylesheet" href="css/login.css">
</head>

<body>
  <form action="" method="post" autocomplete="off">
    <h2>Login</h2>
    <label for="usernameemail">Username</label>
    <input type="text" name="username" required> <br>
    <label for="password">Password</label>
    <input type="password" name="password" required> <br>
    <button type="submit" name="submit">Login</button>
  </form>
  <br>
  <a href="registration.php">Registration</a>
  <div style="width: 300px; height: 250px; margin-left:10px;" id="reader"></div>
  <script src="QRcode/html5-qrcode.min.js"></script>
  <script>
    // JavaScript QR code reader functionality
    // Define a function to be executed upon successfully scanning a QR code
    function onScanSuccess(decodedResult) {
      // Handle on success condition with the decoded text or result.
      // Send the decoded result to the server for login
      // Parse the decoded QR code result assuming it's in JSON format
      var decodedData = JSON.parse(decodedResult);
      // Prepare data to send to the server, assuming it includes a username and password
      var dataToSend = {
        username: decodedData.name, // Assuming "name" is the username
        password: decodedData.password // Assuming "password" is the password
      };
      // Convert the data to JSON string
      var jsonData = JSON.stringify(dataToSend);
      // Make an AJAX request to send the JSON data to a PHP script on the server
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'QRcode.php', true); // Send a POST request to 'QRcode.php'
      xhr.setRequestHeader('Content-Type', 'application/json'); // Set request header
      xhr.onreadystatechange = function() { // Define a callback function to handle state changes
        if (xhr.readyState === 4 && xhr.status === 200) { // Check if request is complete and successful
          window.location.href = 'index.php'; // Redirect to 'index.php' upon successful response
        }
      };
      xhr.send(jsonData); // Send JSON data to the server
    }
    // Initialize an instance of Html5QrcodeScanner with specified parameters
    var html5QrcodeScanner = new Html5QrcodeScanner(
      "reader", { // Target the HTML element with id 'reader'
        fps: 10, // Set the frames per second for scanning
        qrbox: 180 // Set the size of the QR code scanning box
      });
    // Render the HTML5 QR code scanner and bind the onScanSuccess function to handle scan results
    html5QrcodeScanner.render(onScanSuccess);
  </script>
</body>

</html>