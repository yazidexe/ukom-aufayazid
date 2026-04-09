<?php
session_start();
include "Admin/config/database.php";

$email = $_POST['email'];
$password = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
$user = mysqli_fetch_assoc($query);

if($user && password_verify($password, $user['password'])){

    // 🔥 CLEAR CUSTOMER SESSION ONLY
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['avatar']);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['avatar'] = $user['avatar'] ?? '';

    // 🔥 RESET CART
    $_SESSION['cart'] = [];

    $user_id = $user['id'];

    $q = mysqli_query($conn, "
        SELECT * FROM cart WHERE user_id='$user_id'
    ");

    while($row = mysqli_fetch_assoc($q)){
        $_SESSION['cart'][$row['product_id']] = $row['qty'];
    }

    header("Location: index.php");
    exit;

}else{
    echo "Email atau password salah!";
}