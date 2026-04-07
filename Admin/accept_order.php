<?php
session_start();
include "../Admin/config/database.php";

require '../vendor/PHPMailer/src/PHPMailer.php';
require '../vendor/PHPMailer/src/SMTP.php';
require '../vendor/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ✅ VALIDASI ID
if(!isset($_GET['id'])){
    die("ID tidak ditemukan");
}

$id = intval($_GET['id']);

// 🔥 AMBIL DATA LENGKAP (TAMBAH QTY & PRICE)
$query = mysqli_query($conn, "
    SELECT 
        users.email,
        users.name,
        orders.address,
        orders.total,
        products.name AS product_name,
        order_items.qty,
        order_items.price
    FROM orders
    JOIN users ON orders.user_id = users.id
    JOIN order_items ON order_items.order_id = orders.id
    JOIN products ON order_items.product_id = products.id
    WHERE orders.id = '$id'
");

if(!$query){
    die("Query error: " . mysqli_error($conn));
}

$products = [];
$name = '';
$email = '';
$address = '';
$total = 0;

// 🔥 LOOP DATA
while($row = mysqli_fetch_assoc($query)){
    $products[] = $row['product_name'] . " (x" . $row['qty'] . ")";
    $name = $row['name'];
    $email = $row['email'];
    $address = $row['address'];
    $total = $row['total'];
}

// ❌ kalau kosong
if(empty($products)){
    die("Order tidak ditemukan / belum ada item");
}

$productList = implode('<br>', $products);

// 🔥 UPDATE STATUS
mysqli_query($conn, "UPDATE orders SET status='accepted' WHERE id='$id'");

// 🔥 KIRIM EMAIL
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'upaanpipi@gmail.com';
    $mail->Password = 'idgnxbfcewqprzuw';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('upaanpipi@gmail.com', 'Azula Store');
    $mail->addAddress($email, $name);

    $mail->isHTML(true);
    $mail->Subject = 'Pesanan Kamu Sudah Dikirim 🎉';

    $mail->Body = "
        <h2>Aloha {$name} 👋</h2>

        <p>Your order has been sent to your addres</b> 🚚</p>

        <p><b>Detail Produk:</b><br>{$productList}</p>

        <p><b>Total:</b><br>Rp " . number_format($total,0,',','.') . "</p>

        <p><b>Alamat:</b><br>{$address}</p>

        <br>
        <p>If there are any problems, please contact the admin. <br>
        Thanks {$name} for shopping at <b>Azula</b> ✨</p>
    ";

    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    $mail->send();

} catch (Exception $e) {
    echo "EMAIL GAGAL: " . $mail->ErrorInfo;
    exit;
}

header("Location: reports.php");
exit;