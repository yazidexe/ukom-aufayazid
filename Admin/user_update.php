<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit;
}

require 'config/database.php';

$id       = $_POST['id'];
$username = $_POST['username'];
$email    = $_POST['email'];
$password = $_POST['password'];

if (!empty($password)) {
    $query = "UPDATE officers 
              SET username='$username', email='$email', password='$password'
              WHERE id='$id'";
} else {
    $query = "UPDATE officers 
              SET username='$username', email='$email'
              WHERE id='$id'";
}

mysqli_query($conn, $query);

header("Location: users.php");
exit;
