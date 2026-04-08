<?php
include "Admin/config/database.php";

session_start();

$user_id = $_SESSION['user_id'];

$method = $_POST['payment_method'];
$products = $_POST['products'];
$address = $_POST['address'] ?? '';

$expedition_name = $_POST['expedition_name'] ?? 'JNE';
$shipping_type = $_POST['shipping_type'] ?? 'Reguler';

if($shipping_type === 'Ekspres'){
    $shipping_cost = 30000;
} else {
    $shipping_cost = 15000;
}
$cart = $_SESSION['cart'] ?? [];

$total = 0;

// 🔥 HITUNG TOTAL + AMBIL DATA PRODUK
$ids = implode(',', $products);
$query = mysqli_query($conn, "SELECT * FROM products WHERE id IN ($ids)");

$data = [];

while($row = mysqli_fetch_assoc($query)){
    $qty = $cart[$row['id']];
    $subtotal = $row['price'] * $qty;

    $total += $subtotal;

    $data[] = [
        'id' => $row['id'],
        'qty' => $qty,
        'price' => $row['price']
    ];
}

$total += $shipping_cost;

if(!isset($_POST['products'])){
    die("Produk tidak ditemukan");
}

// 🔥 HANDLE UPLOAD
$proofName = null;

if($method == 'transfer' && isset($_FILES['proof'])){
    $file = $_FILES['proof'];

    if($file['name']){

        $allowed = ['jpg','jpeg','png'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if(!in_array($ext, $allowed)){
            die("Format file harus JPG/PNG");
        }

        $proofName = time().'_'.$file['name'];
        $uploadDir = "uploads/proofs/";

        if(!is_dir($uploadDir)){
            mkdir($uploadDir, 0777, true);
        }

        move_uploaded_file($file['tmp_name'], $uploadDir.$proofName);
    }
}

//validasi file

if(empty($address)){
    die("Alamat wajib diisi");
}

if($_POST['payment_method'] == 'transfer' && empty($_FILES['proof']['name'])){
    die("Bukti transfer wajib diupload");
}

// 🔥 INSERT ORDER
$date = date('Y-m-d H:i:s');

mysqli_query($conn, "INSERT INTO orders (user_id, address, payment_method, total, proof, created_at, expedition_name, shipping_type, shipping_cost)
VALUES ('$user_id','$address','$method','$total','$proofName','$date', '$expedition_name', '$shipping_type', '$shipping_cost')");

$order_id = mysqli_insert_id($conn);

// 🔥 INSERT ITEMS
foreach($data as $item){
    mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, qty, price)
    VALUES ('$order_id','".$item['id']."','".$item['qty']."','".$item['price']."')");
}

// 🔥 CLEAR CART
unset($_SESSION['cart']);

header("Location: transaction_history.php");
exit;