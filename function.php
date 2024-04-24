<?php
session_start();

class Connection
{
  public $host = "localhost";
  public $user = "root";
  public $password = "";
  public $db_name = "login-system";
  public $conn;

  public function __construct()
  {
    $this->conn = mysqli_connect($this->host, $this->user, $this->password, $this->db_name);
  }
}

class Register extends Connection
{
    public function registration($name, $username, $email, $password, $confirmpassword)
    {
        $duplicate = mysqli_query($this->conn, "SELECT * FROM tb_user WHERE username = '$username' OR email = '$email'");
        if (mysqli_num_rows($duplicate) > 0) {
            return 10; // Username or email has already been taken
        } else {
            if ($password == $confirmpassword) {
                // Insert the hashed password into the database
                $query = "INSERT INTO tb_user (name, username, email, password) VALUES ('$name', '$username', '$email', '$password')";
                mysqli_query($this->conn, $query);
                return 1; // Registration successful
            } else {
                return 100; // Passwords do not match
            }
        }
    }
}
class Login extends Connection
{
  public $id;
  public function login($username, $password)
  {
    $result = mysqli_query($this->conn, "SELECT * FROM tb_user WHERE username = '$username'");
    $row = mysqli_fetch_assoc($result);
    if (mysqli_num_rows($result) > 0) {
      if (($password == $row["password"])) {
        $this->id = $row["id"];
        return 1;
        // Login successful
      } else {
        return 10;
        // Wrong password
      }
    } else {
      return 100;
      // User not registered
    }
  }
  public function idUser()
  {
    return $this->id;
  }
}
class Select extends Connection
{
  public function selectUserById($id)
  {
    $result = mysqli_query($this->conn, "SELECT * FROM tb_user WHERE id = $id");
    return mysqli_fetch_assoc($result);
  }
}