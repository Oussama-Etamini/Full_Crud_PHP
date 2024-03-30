<?php
require 'function.php';
// Check if the scanned result is sent via POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the raw POST data
    $json = file_get_contents('php://input');
    
    // Decode the JSON string to get an associative array
    $data = json_decode($json, true);

    // Check if both "username" and "password" keys are present in the decoded data
    if ( isset($data['username']) && isset($data['password'])) {
        $username = $data['username'];
        $password = $data['password'];
        
        // Call the function to check the scanned result
        $login = new Login();
        $result = $login->login($username, $password); // Assuming username and password are checked against the database

        if ($result == 1) {
            // session_start(); // Start session only when needed
            $_SESSION["login"] = true;
            $_SESSION["id"] = $login->idUser();
            // header("Location: index.php");
            exit(); // Make sure to exit after sending response
        } elseif ($result == 10) {
            echo "Wrong Password"; // You can modify this response as needed
        } elseif ($result == 100) {
            echo "User Not Registered"; // You can modify this response as needed
        }
    } else {
        echo "Invalid scanned data"; // Handle the case where scanned data is invalid
    }
} else {
    echo "Scanned result not found"; // Handle the case where scanned result is not sent
}
?>
