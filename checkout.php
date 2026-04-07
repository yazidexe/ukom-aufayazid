<?php
session_start();
include "Admin/config/database.php";

// 🔒 biar ga bisa akses langsung
if(!isset($_POST['selected'])){
    header("Location: cart.php");
    exit;
}

$selected = $_POST['selected'];
$cart = $_SESSION['cart'] ?? [];

$ids = implode(',', $selected);

$query = mysqli_query($conn, "SELECT * FROM products WHERE id IN ($ids)");

$products = [];
$total = 0;

while($row = mysqli_fetch_assoc($query)){
    $qty = $cart[$row['id']];
    $row['qty'] = $qty;

    $total += $row['price'] * $qty;

    $products[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Checkout - Azula</title>

<script src="https://cdn.tailwindcss.com"></script>

<script src="https://unpkg.com/lucide@latest"></script>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
body { font-family: 'Poppins', sans-serif; }
</style>
</head>

<body class="bg-gray-100">

<?php include "includes/cart_header.php"; ?>

<!-- LIST PRODUK -->
<div class="max-w-[1000px] mx-auto mt-10 space-y-6">

<?php foreach($products as $product): ?>

<div class="bg-white p-6 rounded-xl shadow flex gap-6 items-center">

    <!-- IMAGE -->
    <img src="admin/uploads/products/<?= $product['picture']; ?>" 
         class="w-28 h-28 object-cover rounded-lg">

    <!-- INFO -->
    <div class="flex-1">
        <h2 class="text-lg font-semibold text-gray-800">
            <?= htmlspecialchars($product['name']); ?>
        </h2>

        <p class="text-sm text-gray-500 mt-2">
            <?= htmlspecialchars($product['category']); ?>
        </p>

        <p class="text-sm text-gray-600 mt-1">
            Qty: <?= $product['qty']; ?>
        </p>
    </div>

    <!-- PRICE -->
    <div class="text-xl font-semibold text-[#0B5C4A]">
        Rp <?= number_format($product['price'],0,',','.'); ?>
    </div>

</div>

<?php endforeach; ?>

</div>

<!-- PAYMENT SECTION -->
<div class="fixed bottom-0 left-0 w-full">

    <!-- TOGGLE (DI ATAS BAR) -->
    <div class="flex ">

        <button onclick="setPayment('transfer')" id="btnTransfer"
            class="flex items-center gap-2 px-12 py-2 rounded-tr-xl bg-[#0B5C4A] text-white transition-all duration-300">
            <i data-lucide="credit-card" class="w-4 h-4"></i>
            Transfer
        </button>

        <button onclick="setPayment('cod')" id="btnCOD"
            class="flex items-center gap-2 px-12 py-2 rounded-t-xl bg-[#0B5C4A] text-white transition-all duration-300 opacity-70">
            <i data-lucide="banknote" class="w-4 h-4"></i>
            COD
        </button>

    </div>

    <!-- BAR -->
    <form action="process_order.php" method="POST" enctype="multipart/form-data"
         class="bg-[#0B5C4A] text-white px-6 py-4 overflow-hidden relative">

        <?php foreach($products as $product): ?>
            <input type="hidden" name="products[]" value="<?= $product['id']; ?>">
        <?php endforeach; ?>

        <!-- MODE -->
        <input type="hidden" name="payment_method" id="payment_method" value="transfer">

        <!-- TRANSFER -->
        <div id="transferForm"
            class="flex items-center justify-between gap-6 transition-all duration-500 opacity-100 translate-y-0">

            <!-- LEFT -->
            <div class="flex flex-col gap-2 w-1/3">
                <p class="text-sm opacity-80">Transfer here: 08984759873</p>

                <input type="text" id="address_transfer" name="address_transfer"
                    placeholder="Input Address"
                    class="bg-transparent border-b border-white/50 focus:outline-none text-sm py-1">
            </div>

            <!-- MIDDLE -->
            <div class="w-1/3">

                <input type="file" name="proof" id="fileInput" class="hidden">

                <label for="fileInput"
                    class="bg-[#199276] px-2 py-2 rounded-lg text-sm text-[#0B5C4A] cursor-pointer block text-center hover:text-white/70 hover:opacity-90">
                    Choose File
                </label>

                <p id="fileName" class="text-xs mt-2 text-white/20 truncate">
                    No file chosen
                </p>

            </div>

            <!-- RIGHT -->
            <div class="text-right w-1/3">
                <p class="text-sm pr-10"><span class="font-thin">Total:</span> <b class="">Rp <?= number_format($total,0,',','.'); ?></b></p>

                <button type="submit"
                    onclick="return validateOrder()"
                    class="mt-2 bg-[#199276] px-14 py-2 rounded-lg hover:opacity-90">
                    Order
                </button>
            </div>

        </div>

        <!-- COD -->
        <div id="codForm"
            class="absolute top-4 left-0 w-full px-6 flex items-center justify-between gap-6
            opacity-0 translate-y-5 pointer-events-none transition-all duration-500">       

            <!-- LEFT -->
            <div class="flex flex-col gap-2 w-1/3">

                <input type="text" id="address_cod" name="address_cod"
                    placeholder="Input Address"
                    class="bg-transparent border-b border-white/50 focus:outline-none text-sm py-1">
            </div>

            <!-- RIGHT -->
            <div class="text-right w-1/3">
                <p class="text-sm opacity-80">Total: Rp <?= number_format($total,0,',','.'); ?></p>

                <button type="submit"
                    onclick="return validateOrder()"
                    class="mt-2 bg-[#199276] px-14 py-2 rounded-lg hover:opacity-90">
                    Order
                </button>
            </div>

        </div>

    </form>
</div>

<script>
    lucide.createIcons();


    //PAYMENT TOGGLE
    function setPayment(method){
        const transfer = document.getElementById('transferForm');
        const cod = document.getElementById('codForm');

        const btnTransfer = document.getElementById('btnTransfer');
        const btnCOD = document.getElementById('btnCOD');

        document.getElementById('payment_method').value = method;

        if(method === 'transfer'){
            // animasi keluar COD
            cod.classList.add('opacity-0','translate-y-5','pointer-events-none');
            cod.classList.remove('opacity-100','translate-y-0');

            // animasi masuk TRANSFER
            transfer.classList.remove('opacity-0','translate-y-5','pointer-events-none');
            transfer.classList.add('opacity-100','translate-y-0');

            // style toggle
            btnTransfer.classList.remove('opacity-70');
            btnCOD.classList.add('opacity-70');

        } else {
            // animasi keluar TRANSFER
            transfer.classList.add('opacity-0','translate-y-5','pointer-events-none');
            transfer.classList.remove('opacity-100','translate-y-0');

            // animasi masuk COD
            cod.classList.remove('opacity-0','translate-y-5','pointer-events-none');
            cod.classList.add('opacity-100','translate-y-0');

            // style toggle
            btnCOD.classList.remove('opacity-70');
            btnTransfer.classList.add('opacity-70');
        }
    }

    //SHOW FILE NAME IF CHOOSEN
    const fileInput = document.getElementById('fileInput');
    const fileName = document.getElementById('fileName');

    fileInput.addEventListener('change', function(){
        if(this.files.length > 0){
            fileName.textContent = this.files[0].name;
        } else {
            fileName.textContent = "No file chosen";
        }
    });

    //VALIDASI
        function validateOrder(){
        const method = document.getElementById('payment_method').value;

        let address = '';

        if(method === 'transfer'){
            address = document.getElementById('address_transfer').value.trim();
        } else {
            address = document.getElementById('address_cod').value.trim();
        }

        if(address === ''){
            showToast("", "assets/alert-alamat.png");
            return false;
        }

        if(method === 'transfer'){
            const file = document.getElementById('fileInput');

            if(!file || file.files.length === 0){
                showToast("", "assets/alert-transfer.png");
                return false;
            }
        }

        return true;
    }

    //popup alert
    function showToast(message, image = ''){
        const wrapper = document.getElementById('toastWrapper');
        const toast = document.getElementById('toast');
        const text = document.getElementById('toastText');
        const img = document.getElementById('toastImage');

        text.innerText = message;

        if(image){
            img.src = image;
            img.style.display = 'block';
        } else {
            img.style.display = 'none';
        }

        // masuk (fade + scale)
        wrapper.classList.remove('opacity-0','pointer-events-none');
        wrapper.classList.add('opacity-100');

        toast.classList.remove('scale-90','opacity-0');
        toast.classList.add('scale-100','opacity-100');

        // keluar
        setTimeout(() => {
            toast.classList.remove('scale-100','opacity-100');
            toast.classList.add('scale-90','opacity-0');

            wrapper.classList.remove('opacity-100');
            wrapper.classList.add('opacity-0','pointer-events-none');
        }, 2000);
    }

</script>


<div id="toastWrapper" 
     class="fixed inset-0 flex items-center justify-center bg-black/40 opacity-0 pointer-events-none transition-all duration-300 z-50">

    <div id="toast"
         class="bg-white text-center px-8 py-6 rounded-xl shadow-xl scale-90 opacity-0 transition-all duration-300">

        <!-- IMAGE -->
        <img id="toastImage" src="" class="w-[400px] h-[350px] object-contain mx-auto mb-4">

        <!-- TEXT -->
        <p id="toastText" class="text-gray-700 text-sm"></p>

    </div>

</div>

</body>
</html>