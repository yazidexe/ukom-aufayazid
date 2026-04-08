<?php
session_start();

if(!isset($_POST['product_id'])){
    die("Produk tidak ditemukan");
}

$id = $_POST['product_id'];

// init cart
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

// tambah
if(isset($_SESSION['cart'][$id])){
    $_SESSION['cart'][$id]++;
} else {
    $_SESSION['cart'][$id] = 1;
}

// notif
$_SESSION['success'] = "product has been added to cart";

header("Location: index.php");
exit;