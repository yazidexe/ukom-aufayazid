<?php
session_start();
include "Admin/config/database.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$id = $_SESSION['user_id'];
$name = $_POST['name'];
$email = $_POST['email'];

mysqli_query($conn, "
    UPDATE users 
    SET name='$name', email='$email' 
    WHERE id='$id'
");

// update session juga
$_SESSION['user_name'] = $name;

header("Location: profile.php");
exit;