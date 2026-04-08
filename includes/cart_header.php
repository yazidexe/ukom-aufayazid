<?php
$current = basename($_SERVER['PHP_SELF']);
?>

<div class="relative px-10 bg-white shadow-sm">

    <!-- BACK (FIX DI ATAS) -->
    <a href="index.php" 
       class="absolute top-4 left-10 text-sm text-gray-600 hover:underline">
        ← Kembali
    </a>

    <!-- MENU -->
    <div class="flex justify-center gap-10 pt-12 pb-12 text-sm">

        <a href="cart.php" 
           class="<?= $current == 'cart.php' ? 'text-[#0B5C4A] font-semibold' : 'text-gray-400'; ?>">
            Keranjang
        </a>

        <a href="checkout.php" 
           class="<?= $current == 'checkout.php' ? 'text-[#0B5C4A] font-semibold' : 'text-gray-400'; ?>">
            Checkout
        </a>

        <a href="transaction_history.php" 
           class="<?= $current == 'transaction_history.php' ? 'text-[#0B5C4A] font-semibold' : 'text-gray-400'; ?>">
            Riwayat Transaksi
        </a>

    </div>

</div>