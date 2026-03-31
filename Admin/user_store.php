<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit;
}

require 'config/database.php';

$username = $_POST['username'];
$email    = $_POST['email'];
$password = $_POST['password']; // sementara plain dulu

$query = "INSERT INTO officers (username, email, password)
          VALUES ('$username', '$email', '$password')";

mysqli_query($conn, $query);

header("Location: users.php");
exit;
