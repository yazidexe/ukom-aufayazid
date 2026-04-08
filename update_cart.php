<?php
session_start();
include "Admin/config/database.php";
$user_id = $_SESSION['user_id'] ?? null;

if(!isset($_GET['id']) || !isset($_GET['action'])){
    header("Location: cart.php");
    exit;
}

$id = $_GET['id'];
$action = $_GET['action'];

// pastiin cart ada
if(!isset($_SESSION['cart'][$id])){
    header("Location: cart.php");
    exit;
}

// 🔥 LOGIC
if($action == "plus"){
    $_SESSION['cart'][$id]++;
}

if($user_id){
    if(isset($_SESSION['cart'][$id])){
        $qty = $_SESSION['cart'][$id];

        mysqli_query($conn, "
            UPDATE cart 
            SET qty='$qty'
            WHERE user_id='$user_id' AND product_id='$id'
        ");
    } else {
        mysqli_query($conn, "
            DELETE FROM cart 
            WHERE user_id='$user_id' AND product_id='$id'
        ");
    }
}

if($action == "minus"){
    $_SESSION['cart'][$id]--;

    // kalau qty 0 → hapus item
    if($_SESSION['cart'][$id] <= 0){
        unset($_SESSION['cart'][$id]);
    }
}

header("Location: cart.php");
exit;