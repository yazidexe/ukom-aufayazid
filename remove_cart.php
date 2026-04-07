<?php
session_start();
include "Admin/config/database.php";

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];

mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id' AND product_id='$product_id'");

header("Location: cart.php");
exit;