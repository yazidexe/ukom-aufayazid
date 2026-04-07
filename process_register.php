<?php
include "Admin/config/database.php";

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// cek email udah ada belum
$check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

if(mysqli_num_rows($check) > 0){
    die("Email sudah digunakan!");
}

mysqli_query($conn, "
    INSERT INTO users (name, email, password, created_at)
    VALUES ('$name','$email','$password', NOW())
");

header("Location: login.php");