<?php
session_start();
include "Admin/config/database.php";

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$action = $_POST['action'];

if($action == 'plus'){
    mysqli_query($conn, "UPDATE cart SET qty = qty + 1 WHERE user_id='$user_id' AND product_id='$product_id'");
}

if($action == 'minus'){
    mysqli_query($conn, "UPDATE cart SET qty = qty - 1 WHERE user_id='$user_id' AND product_id='$product_id' AND qty > 1");
}

header("Location: cart.php");