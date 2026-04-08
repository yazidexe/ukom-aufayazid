<?php
session_start();
include "Admin/config/database.php"; // 🔥 WAJIB ADA

$id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

// ❌ kalau ga ada id, langsung balik
if(!$id){
    header("Location: cart.php");
    exit;
}

// 🔥 HAPUS DARI SESSION
if(isset($_SESSION['cart'][$id])){
    unset($_SESSION['cart'][$id]);
}

// 🔥 HAPUS DARI DATABASE (HARUS ADA USER_ID)
if($user_id){
    mysqli_query($conn, "
        DELETE FROM cart 
        WHERE user_id='$user_id' AND product_id='$id'
    ");
}

header("Location: cart.php");
exit;