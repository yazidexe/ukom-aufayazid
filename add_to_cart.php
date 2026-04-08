<?php
session_start();
include "Admin/config/database.php";

if(!isset($_POST['product_id'])){
    die("Produk tidak ditemukan");
}

$id = $_POST['product_id'];
$user_id = $_SESSION['user_id'] ?? null;

// 🔥 SESSION
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

if(isset($_SESSION['cart'][$id])){
    $_SESSION['cart'][$id]++;
} else {
    $_SESSION['cart'][$id] = 1;
}

// 🔥 DATABASE (INI YANG LU KURANG)
if($user_id){

    $check = mysqli_query($conn, "
        SELECT * FROM cart 
        WHERE user_id='$user_id' AND product_id='$id'
    ");

    if(mysqli_num_rows($check) > 0){
        mysqli_query($conn, "
            UPDATE cart 
            SET qty = qty + 1 
            WHERE user_id='$user_id' AND product_id='$id'
        ");
    } else {
        mysqli_query($conn, "
            INSERT INTO cart (user_id, product_id, qty)
            VALUES ('$user_id','$id','1')
        ");
    }
}

// notif
$_SESSION['success'] = "product has been added to cart";

header("Location: index.php");
exit;