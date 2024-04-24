<?php
require 'function.php';
require_once 'phpqrcode/qrlib.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
if (!empty($_SESSION["id"])) {
    header("Location: index.php");
    exit;
}
$register = new Register();
if (isset($_POST["generate"])) {
    // Perform registration
    if (strlen($_POST['password']) >= 8) {
        $result = $register->registration($_POST["name"], $_POST["username"], $_POST["email"], $_POST["password"], $_POST["confirmpassword"]);
        if ($result == 1) {
            // Registration successful
            echo "<script> alert('Registration Successful & QR code has been sent to your email.'); </script>";
            $name = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $data = array(
                'name' => $name,
                'password' => $password
            );
            $jsonData = json_encode($data);
            $tempDir = 'temp/';
            if (!file_exists($tempDir)) {
                mkdir($tempDir);
            }
            $filename = $tempDir . 'qr_code.png';
            QRcode::png($jsonData, $filename, 'L', 4, 2);
            // Send email with QR code image as attachment
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Your SMTP host
                $mail->SMTPAuth = true;
                $mail->Username = 'your_email@gmail.com'; // Your SMTP username
                $mail->Password = 'your_password'; // Your SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;
                $mail->setFrom($_POST["email"], 'Your Name'); // Sender's email address and name
                $mail->addAddress($_POST["email"]); // Recipient's email address
                $mail->isHTML(true);
                $mail->Subject = 'QR Code Image';
                $mail->Body = 'Please find the QR code image attached.';
                $mail->addAttachment($filename, 'qr_code.png');
                $mail->send();
            } catch (Exception $e) {
                // Log the error message and provide a user-friendly error message
                echo "<script> alert('Error: Email could not be sent.'); </script>";
            }
        } elseif ($result == 10) {
            // Username or email already taken
            echo "<script> alert('Username or Email Has Already Been Taken'); </script>";
        } elseif ($result == 100) {
            // Passwords do not match
            echo "<script> alert('Passwords Do Not Match'); </script>";
        }
    } else {
        // Password must have 8 or more characters
        echo "<script> alert('Password must have 8 or more characters.'); </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Registration</title>
    <link rel="stylesheet" href="css/register.css">

    <style>
        .img-qrcode {
            position: absolute;
            right: 25%;
            top: 40%;
        }

        .download-btn {
            position: absolute;
            right: 24%;
            top: 59%;
            background-color: #4caf50;
            color: #fff;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            margin-top: 15px;
        }

        .download-btn:hover {
            background-color: #45a049;
        }
    </style>
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
        <button class="button" type="submit" name="generate">Register</button>
    </form>
    <?php
    if (isset($filename)) {
        echo "<div class='img-qrcode'><img src='$filename'></div>";
        echo "<a href='$filename' download='qr_code.png' class='download-btn'>Download QR Code </a>";
    }
    ?>
    <br>
    <a id="login" href="login.php">Login</a>
</body>
</html>