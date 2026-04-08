<?php
session_start();
include "Admin/config/database.php";

$cart = $_SESSION['cart'] ?? [];

$products = [];

if(!empty($cart)){
    $ids = implode(',', array_keys($cart));
    $query = mysqli_query($conn, "SELECT * FROM products WHERE id IN ($ids)");
    
    while($row = mysqli_fetch_assoc($query)){
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cart - Azula</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<script src="https://unpkg.com/lucide@latest"></script>

<style>
body { font-family: 'Poppins', sans-serif; }
.brand { font-family: 'Playfair Display', serif; }
</style>
</head>

<body class="bg-gray-100">

<?php include "includes/cart_header.php"; ?>

<!-- FORM UTAMA -->
<form action="checkout.php" method="POST" onsubmit="return validateCheckout()">

<div class="max-w-[900px] mx-auto mt-10 space-y-4 px-4 pb-20">

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-semibold text-gray-800">Keranjang Belanja</h1>
    </div>

<?php if(empty($products)): ?>
    <div class="bg-white p-12 rounded-md shadow-sm border border-gray-200 text-center">
        <i data-lucide="shopping-cart" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
        <p class="text-gray-500 mb-6 font-medium">Keranjang Anda masih kosong.</p>
        <a href="index.php" class="inline-block bg-[#0B5C4A] text-white px-8 py-3 rounded-md hover:bg-[#084b3c] transition-colors">Mulai Belanja</a>
    </div>
<?php endif; ?>

<?php foreach($products as $product): 
    $qty = $cart[$product['id']];
?>

<div class="bg-white p-5 rounded-md shadow-sm border border-gray-200 flex flex-col md:flex-row items-center gap-6">

    <!-- CHECKBOX -->
    <div class="shrink-0 flex items-center hidden md:flex">
        <input type="checkbox" name="selected[]" value="<?= $product['id']; ?>" class="w-5 h-5 rounded border-gray-300 accent-[#0B5C4A]">
    </div>

    <!-- IMAGE -->
    <img src="admin/uploads/products/<?= $product['picture']; ?>" class="w-24 h-24 object-cover rounded-md border border-gray-100 shrink-0">

    <!-- INFO -->
    <div class="flex-1 text-center md:text-left w-full">
        <h2 class="font-semibold text-gray-800 text-lg">
            <?= htmlspecialchars($product['name']); ?>
        </h2>
        <p class="text-sm font-medium text-[#199276] mb-2">
            <?= htmlspecialchars($product['category']); ?>
        </p>
        <p class="font-bold text-[#0B5C4A]">
            Rp <?= number_format($product['price'],0,',','.'); ?>
        </p>
    </div>

    <!-- CONTROLS -->
    <div class="flex items-center justify-between md:justify-end gap-6 w-full md:w-auto mt-4 md:mt-0 pt-4 md:pt-0 border-t border-gray-100 md:border-none">
        
        <div class="md:hidden shrink-0 flex items-center">
            <input type="checkbox" name="selected[]" value="<?= $product['id']; ?>" class="w-5 h-5 rounded border-gray-300 accent-[#0B5C4A]">
        </div>

        <div class="flex items-center gap-6">
            <!-- DELETE -->
            <a href="remove_cart.php?id=<?= $product['id']; ?>" class="text-gray-400 hover:text-red-500 transition-colors" title="Hapus">
                <i data-lucide="trash-2" class="w-5 h-5"></i>
            </a>

            <!-- QTY -->
            <div class="flex items-center border border-gray-300 rounded-md overflow-hidden bg-white h-10">
                <a href="update_cart.php?id=<?= $product['id']; ?>&action=minus" class="px-4 h-full flex items-center justify-center hover:bg-gray-100 text-gray-600 transition-colors">-</a>
                <span class="px-4 h-full flex items-center justify-center font-medium bg-gray-50 border-x border-gray-300 text-gray-800"><?= $qty; ?></span>
                <a href="update_cart.php?id=<?= $product['id']; ?>&action=plus" class="px-4 h-full flex items-center justify-center hover:bg-gray-100 text-gray-600 transition-colors">+</a>
            </div>
        </div>
        
    </div>

</div>

<?php endforeach; ?>

</div>

<!-- BUTTON -->
<?php if(!empty($products)): ?>
<div class="max-w-[900px] mx-auto mt-6 flex justify-end px-4">
    <button type="submit" class="bg-[#0B5C4A] text-white font-medium px-12 py-3 rounded-md hover:bg-[#084b3c] shadow-sm transition-colors">
        Checkout Pilihan
    </button>
</div>
<?php endif; ?>

</form>

<!-- CUSTOM ALERT POPUP -->
<div id="customAlert" class="fixed inset-0 flex items-center justify-center z-50 bg-black/40 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
    <div class="bg-white rounded-md shadow-xl px-10 py-8 text-center transform scale-95 transition-transform duration-300" id="alertBox">
        <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4 border border-red-100">
            <i data-lucide="alert-triangle" class="w-8 h-8 text-red-500"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Tunggu Sebentar!</h3>
        <p class="text-gray-500 font-medium mb-8">Produk belum dipilih.</p>
        <button type="button" onclick="closeAlert()" class="bg-[#0B5C4A] text-white px-8 py-2.5 rounded-md font-medium hover:bg-[#084b3c] shadow-sm transition-colors w-full">
            Mengerti
        </button>
    </div>
</div>

<script>
lucide.createIcons();

function validateCheckout() {
    const selected = document.querySelectorAll('input[name="selected[]"]:checked');
    
    if (selected.length === 0) {
        showCustomAlert();
        return false;
    }
    
    return true;
}

function showCustomAlert() {
    const customAlert = document.getElementById('customAlert');
    const alertBox = document.getElementById('alertBox');
    
    customAlert.classList.remove('opacity-0', 'pointer-events-none');
    customAlert.classList.add('opacity-100');
    setTimeout(() => {
        alertBox.classList.remove('scale-95');
        alertBox.classList.add('scale-100');
    }, 10);
}

function closeAlert() {
    const customAlert = document.getElementById('customAlert');
    const alertBox = document.getElementById('alertBox');
    
    alertBox.classList.remove('scale-100');
    alertBox.classList.add('scale-95');
    setTimeout(() => {
        customAlert.classList.remove('opacity-100');
        customAlert.classList.add('opacity-0', 'pointer-events-none');
    }, 200);
}
</script>

</body>
</html>