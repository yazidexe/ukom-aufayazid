<?php
include "auth.php";
include "../Admin/config/database.php";

if(!isset($_GET['id'])){
    die("ID tidak ditemukan");
}

$id = intval($_GET['id']);

// 🔥 ambil data order + user
$orderQuery = mysqli_query($conn, "
    SELECT 
        orders.*,
        users.name,
        users.email
    FROM orders
    JOIN users ON orders.user_id = users.id
    WHERE orders.id = '$id'
");

$order = mysqli_fetch_assoc($orderQuery);

if(!$order){
    die("Order tidak ditemukan");
}

// 🔥 ambil item
$itemQuery = mysqli_query($conn, "
    SELECT 
        products.name,
        order_items.qty,
        order_items.price
    FROM order_items
    JOIN products ON order_items.product_id = products.id
    WHERE order_items.order_id = '$id'
");

$subtotal_produk = 0;
// Menghitung subtotal dari items doang
// $order['total'] adalah grand total
$shipping_cost = $order['shipping_cost'] ?? 0;
$handling = 0; // Hapus handling fee palsu
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Receipt - Azula</title>

<script src="https://cdn.tailwindcss.com"></script>

<style>
@media print {
    button {
        display: none;
    }
    body {
        background: white;
    }
}
</style>

</head>

<body class="bg-gray-100 p-10">

<div class="max-w-[650px] mx-auto bg-white p-8 shadow-xl rounded-2xl">

    <!-- HEADER -->
    <div class="text-center border-b pb-4 mb-6">
        <h1 class="text-3xl font-bold text-[#0B5C4A]">Azula Store</h1>
        <p class="text-sm text-gray-400 mt-1">Official Receipt</p>
    </div>

    <!-- INFO -->
    <div class="grid grid-cols-2 gap-4 text-sm mb-6">

        <div>
            <p class="text-gray-500">Order ID</p>
            <p class="font-semibold">#<?= $order['id']; ?></p>
        </div>

        <div>
            <p class="text-gray-500">Tanggal</p>
            <p class="font-semibold"><?= $order['created_at']; ?></p>
        </div>

        <div>
            <p class="text-gray-500">Pelanggan</p>
            <p class="font-semibold"><?= $order['name']; ?></p>
        </div>

        <div>
            <p class="text-gray-500">Email</p>
            <p class="font-semibold"><?= $order['email']; ?></p>
        </div>

        <div class="col-span-2">
            <p class="text-gray-500">Alamat</p>
            <p class="font-semibold"><?= $order['address']; ?></p>
        </div>

        <div>
            <p class="text-gray-500">Payment</p>
            <p class="font-semibold uppercase"><?= $order['payment_method']; ?></p>
        </div>

        <div>
            <p class="text-gray-500">Status</p>
            <p class="font-semibold text-green-600">
                <?= ucfirst($order['status'] ?? 'pending'); ?>
            </p>
        </div>

        <div>
            <p class="text-gray-500">Ekspedisi</p>
            <p class="font-semibold text-[#0B5C4A]">
                <?= $order['expedition_name'] ? $order['expedition_name'] . ' (' . $order['shipping_type'] . ')' : '-'; ?>
            </p>
        </div>

    </div>

    <!-- TABLE -->
    <table class="w-full text-sm border-t">
        <thead>
            <tr class="border-b text-gray-600">
                <th class="py-2 text-left">Product</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>

        <?php while($item = mysqli_fetch_assoc($itemQuery)): 
            $subtotal = $item['qty'] * $item['price'];
            $subtotal_produk += $subtotal;
        ?>
            <tr class="border-b">
                <td class="py-3"><?= $item['name']; ?></td>
                <td class="text-center"><?= $item['qty']; ?></td>
                <td class="text-center">Rp<?= number_format($item['price'],0,',','.'); ?></td>
                <td class="text-center font-medium">
                    Rp<?= number_format($subtotal,0,',','.'); ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <!-- TOTAL -->
    <div class="text-right mt-6 space-y-1">

        <p class="text-sm">
            Subtotal Produk: Rp<?= number_format($subtotal_produk,0,',','.'); ?>
        </p>

        <p class="text-sm font-medium <?= $shipping_cost == 0 ? 'text-green-600' : 'text-gray-700' ?>">
            Ongkos Kirim: <?= $shipping_cost == 0 ? 'Gratis 🚚' : 'Rp' . number_format($shipping_cost,0,',','.'); ?>
        </p>

        <hr class="my-2">

        <p class="text-xl font-bold text-[#0B5C4A]">
            Total Pembayaran: Rp<?= number_format($order['total'],0,',','.'); ?>
        </p>

    </div>

    <!-- BUTTON -->
    <div class="text-center mt-8">
        <button onclick="window.print()" 
            class="bg-[#0B5C4A] text-white px-8 py-3 rounded-lg hover:opacity-90 transition">
            Cetak Struk
        </button>
    </div>

</div>

</body>
</html>