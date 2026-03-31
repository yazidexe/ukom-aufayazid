<?php
session_start();
include '../Admin/config/database.php';

$email = $_POST['email'];
$password = $_POST['password'];

$query = $conn->prepare("SELECT * FROM officers WHERE email = ? AND status = 'active'");
$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();


if($result->num_rows > 0){
    $officer = $result->fetch_assoc();

   if($password === $officer['password']){
    $_SESSION['officer_id'] = $officer['id'];

    $_SESSION['officer_name'] = 
        !empty($officer['name']) 
        ? $officer['name'] 
        : $officer['username'];

    header("Location: dashboard.php");
    exit();
    } else {
        echo "Password salah!";
    }
} else {
    echo "Akun tidak ditemukan!";
}