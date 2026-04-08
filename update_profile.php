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
$address = $_POST['address']; // ambil alamat

mysqli_query($conn, "
    UPDATE users 
    SET name='$name', email='$email', address='$address'
    WHERE id='$id'
");

// update session juga
$_SESSION['user_name'] = $name;
$_SESSION['user_address'] = $address; // tambahan alamat

// flash message sukses
$_SESSION['success'] = "Profile berhasil diupdate!";

header("Location: profile.php");
exit;