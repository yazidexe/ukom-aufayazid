<?php
session_start();
include "Admin/config/database.php";

$email = $_POST['email'];
$password = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
$user = mysqli_fetch_assoc($query);

if($user && password_verify($password, $user['password'])){
    
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];

    header("Location: index.php");
    exit;

}else{
    echo "Email atau password salah!";
}