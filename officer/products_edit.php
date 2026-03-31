<?php
include 'auth.php';
require '../Admin/config/database.php';

if(isset($_POST['update'])){
    $id    = $_POST['id'];
    $name  = $_POST['name'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $old   = $_POST['old_picture'];

    $img = $_FILES['picture']['name'];
    $tmp = $_FILES['picture']['tmp_name'];

    if($img){
        $newName = time().'_'.$img;
        move_uploaded_file($tmp, "../Admin/uploads/products/".$newName);

        if($old && file_exists("../Admin/uploads/products/".$old)){
            unlink("../Admin/uploads/products/".$old);
        }
    } else {
        $newName = $old;
    }

    mysqli_query($conn, "UPDATE products SET
        name='$name',
        stock='$stock',
        price='$price',
        picture='$newName'
        WHERE id='$id'
    ");

    header("Location: products.php");
}