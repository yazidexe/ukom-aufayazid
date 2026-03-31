<?php
include 'auth.php';
require '../Admin/config/database.php';

if(isset($_POST['delete'])){
    $id = $_POST['id'];

    $q = mysqli_query($conn, "SELECT picture FROM products WHERE id='$id'");
    $p = mysqli_fetch_assoc($q);

    if($p['picture'] && file_exists("../Admin/uploads/products/".$p['picture'])){
    unlink("../Admin/uploads/products/".$p['picture']);
    }

    mysqli_query($conn, "DELETE FROM products WHERE id='$id'");
    header("Location: products.php");
}