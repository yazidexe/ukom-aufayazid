<?php
session_start();

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

if($action == "minus"){
    $_SESSION['cart'][$id]--;

    // kalau qty 0 → hapus item
    if($_SESSION['cart'][$id] <= 0){
        unset($_SESSION['cart'][$id]);
    }
}

header("Location: cart.php");
exit;