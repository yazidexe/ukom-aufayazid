<?php
include 'auth.php';
require '../Admin/config/database.php';

if(isset($_POST['save'])){
    $name  = $_POST['name'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $date  = date('Y-m-d');

    $imgName = $_FILES['picture']['name'];
    $tmp     = $_FILES['picture']['tmp_name'];

    if($imgName){
        $newName = time().'_'.$imgName;
        move_uploaded_file($tmp, "../Admin/uploads/products/".$newName);
    } else {
        $newName = null;
    }

    mysqli_query($conn, "INSERT INTO products 
    (name, category, stock, price, picture, created_at) 
    VALUES 
    ('$name','$category','$stock','$price','$newName','$date')");

    header("Location: products.php");
}

