<?php
include __DIR__ . '/config/database.php';

if(isset($_POST['save'])){

    $name  = $_POST['name'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $description = $_POST['description']; 
    $date  = date('Y-m-d');

    $imgName = $_FILES['picture']['name'];
    $tmp     = $_FILES['picture']['tmp_name'];

    if($imgName){
        $newName = time().'_'.$imgName;
        move_uploaded_file($tmp, "uploads/products/".$newName);
    } else {
        $newName = null;
    }

    mysqli_query($conn, "INSERT INTO products 
    (name, category, stock, price, picture, description, created_at) 
    VALUES 
    ('$name','$category','$stock','$price','$newName','$description','$date')");

    header("Location: products.php");
}