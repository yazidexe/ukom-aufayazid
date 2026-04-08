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

$user_id = $_SESSION['user_id'];
$user_query = mysqli_query($conn, "SELECT address FROM users WHERE id='$user_id'");
$user_data = mysqli_fetch_assoc($user_query);
$user_address = $user_data['address'] ?? '';

$query = mysqli_query($conn, "SELECT * FROM products WHERE id IN ($ids)");

$products = [];
$total_barang = 0; // The base total without shipping

while($row = mysqli_fetch_assoc($query)){
    $qty = $cart[$row['id']];
    $row['qty'] = $qty;

    $total_barang += $row['price'] * $qty;

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

<body class="bg-gray-50">

<?php include "includes/cart_header.php"; ?>

<!-- FORM UTAMA -->
<form action="process_order.php" method="POST" enctype="multipart/form-data" id="checkoutForm">

<div class="max-w-[1200px] mx-auto mt-10 mb-20 px-4 flex flex-col lg:flex-row gap-8">
    
    <!-- ================= LEFT SIDE (MAIN INFO) ================= -->
    <div class="w-full lg:w-2/3 space-y-6">
        
        <!-- 1. ALAMAT PENGIRIMAN -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="map-pin" class="w-5 h-5 text-[#0B5C4A]"></i> Alamat Pengiriman
            </h3>
            <textarea name="address" id="main_address" rows="3" placeholder="Masukkan alamat lengkap pengiriman..." class="w-full bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm focus:outline-none focus:border-[#199276] focus:ring-2 focus:ring-[#199276]/20 transition-all font-medium text-gray-700" required><?= htmlspecialchars($user_address); ?></textarea>
        </div>

        <!-- 2. PRODUK -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                <i data-lucide="shopping-bag" class="w-5 h-5 text-[#0B5C4A]"></i> Pesanan Anda
            </h3>
            <div class="space-y-5">
                <?php foreach($products as $product): ?>
                <div class="flex gap-5 border-b border-gray-100 pb-5 last:border-0 last:pb-0 items-center">
                    <img src="admin/uploads/products/<?= $product['picture']; ?>" class="w-20 h-20 object-cover rounded-xl shadow-sm border border-gray-50">
                    <div class="flex-1">
                        <h2 class="font-semibold text-gray-800 text-base"><?= htmlspecialchars($product['name']); ?></h2>
                        <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($product['category']); ?></p>
                        <p class="text-sm font-medium text-gray-700 mt-2 bg-gray-100 w-max px-3 py-1 rounded-md">Qty: <?= $product['qty']; ?></p>
                    </div>
                    <div class="font-bold text-lg text-[#0B5C4A]">
                        Rp <?= number_format($product['price'],0,',','.'); ?>
                    </div>
                    <input type="hidden" name="products[]" value="<?= $product['id']; ?>">
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 3. EKSPEDISI -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="truck" class="w-5 h-5 text-[#0B5C4A]"></i> Pengiriman
            </h3>
            <div class="flex flex-col md:flex-row gap-6">
                <div class="w-full md:w-1/2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Ekspedisi</label>
                    <div class="relative">
                        <select name="expedition_name" id="expedition_name" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3.5 text-sm focus:outline-none focus:border-[#199276] focus:ring-2 focus:ring-[#199276]/20 transition-all font-medium appearance-none">
                            <option value="JNE">JNE</option>
                            <option value="J&T Express">J&T Express</option>
                            <option value="SiCepat">SiCepat</option>
                            <option value="POS Indonesia">POS Indonesia</option>
                        </select>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    </div>
                </div>
                <div class="w-full md:w-1/2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Layanan</label>
                    <div class="relative">
                        <select name="shipping_type" id="shipping_type" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3.5 text-sm focus:outline-none focus:border-[#199276] focus:ring-2 focus:ring-[#199276]/20 transition-all font-medium appearance-none" onchange="updateTotal()">
                            <option value="Reguler" data-cost="15000">Reguler (Rp 15.000)</option>
                            <option value="Ekspres" data-cost="30000">Ekspres (Rp 30.000)</option>
                        </select>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- ================= RIGHT SIDE (PAYMENT PANEL) ================= -->
    <div class="w-full lg:w-1/3">
        <div class="bg-white p-6 rounded-2xl shadow-xl shadow-[#0B5C4A]/5 border border-[#199276]/10 sticky top-24">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i data-lucide="wallet" class="w-6 h-6 text-[#0B5C4A]"></i> Pembayaran
            </h3>
            
            <input type="hidden" name="payment_method" id="payment_method" value="transfer">
            
            <!-- BUTTONS METODE -->
            <div class="flex gap-3 mb-6 bg-gray-50 p-1.5 rounded-xl border border-gray-100">
                <button type="button" onclick="setPayment('transfer')" id="btnTransfer"
                    class="flex-1 py-2.5 bg-white shadow-sm border border-gray-200 text-[#0B5C4A] font-bold rounded-lg flex items-center justify-center gap-2 transition-all">
                    <i data-lucide="credit-card" class="w-4 h-4"></i> Transfer
                </button>
                <button type="button" onclick="setPayment('cod')" id="btnCOD"
                    class="flex-1 py-2.5 text-gray-500 font-medium rounded-lg flex items-center justify-center gap-2 transition-all hover:bg-gray-100 border border-transparent">
                    <i data-lucide="banknote" class="w-4 h-4"></i> COD
                </button>
            </div>

            <!-- TRANSFER CONTAINER -->
            <div id="proofContainer" class="mb-6 block">
                <div class="bg-blue-50 text-blue-800 p-4 rounded-xl text-sm mb-4 border border-blue-100 flex gap-3 items-start">
                    <i data-lucide="info" class="w-5 h-5 flex-shrink-0 text-blue-500"></i>
                    <p>Silakan transfer ke rekening <strong>BCA 08984759873</strong> a.n. Azula Store.</p>
                </div>
                
                <label for="fileInput" class="w-full bg-gray-50 text-gray-600 border-2 border-dashed border-gray-300 hover:border-[#199276] hover:bg-[#199276]/5 rounded-xl flex flex-col items-center justify-center py-6 cursor-pointer transition-all">
                    <div class="bg-white p-3 rounded-full shadow-sm mb-3">
                        <i data-lucide="upload-cloud" class="w-6 h-6 text-[#0B5C4A]"></i>
                    </div>
                    <span class="text-sm font-semibold text-[#0B5C4A]">Pilih Bukti Transfer</span>
                    <span class="text-xs text-gray-400 mt-1">Format JPG, PNG (Maks 2MB)</span>
                </label>
                <input type="file" name="proof" id="fileInput" class="hidden" accept="image/*">
                <div id="fileArea" class="hidden mt-3 bg-[#199276]/10 border border-[#199276]/20 py-2 px-4 rounded-lg flex items-center justify-between">
                    <p id="fileName" class="text-sm font-medium text-[#0B5C4A] truncate max-w-[200px]">nama_file.jpg</p>
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-[#199276]"></i>
                </div>
            </div>

            <!-- COD CONTAINER -->
            <div id="codContainer" class="mb-6 hidden">
                <div class="text-center p-6 bg-yellow-50 rounded-xl border border-yellow-200">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="truck" class="w-6 h-6 text-yellow-600"></i>
                    </div>
                    <p class="text-sm text-yellow-800 font-medium">Pembayaran dilakukan langsung ke kurir saat menerima paket.</p>
                </div>
            </div>

            <!-- SUMMARY -->
            <div class="space-y-3 border-t-2 border-dashed border-gray-200 pt-6">
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Subtotal Produk</span>
                    <span class="font-medium text-gray-800">Rp <span id="subtotalDisplay"><?= number_format($total_barang,0,',','.'); ?></span></span>
                </div>
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Ongkos Kirim</span>
                    <span class="font-medium text-gray-800">Rp <span id="ongkirDisplay">15.000</span></span>
                </div>
                <div class="flex justify-between items-end pt-4 mt-2">
                    <span class="text-base font-semibold text-gray-800">Total Tagihan</span>
                    <span class="text-2xl font-bold text-[#0B5C4A]">Rp <span id="grandTotal" data-base="<?= $total_barang; ?>"><?= number_format($total_barang + 15000,0,',','.'); ?></span></span>
                </div>
            </div>

            <!-- SUBMIT BUTTON -->
            <button type="submit" onclick="return validateOrder()" class="w-full mt-8 bg-[#0B5C4A] text-white py-4 rounded-xl font-bold hover:bg-[#08483A] hover:-translate-y-1 transition-all shadow-lg hover:shadow-xl text-lg flex items-center justify-center gap-2 active:translate-y-0">
                Pesan Sekarang <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </button>
            <p class="text-xs text-center text-gray-400 mt-4"><i data-lucide="lock" class="w-3 h-3 inline pb-0.5"></i> Pembayaran Aman Terenkripsi</p>
        </div>
    </div>

</div>
</form>

<script>
    lucide.createIcons();

    // Kalkulasi Biaya Ongkir Ke UI
    function updateTotal(){
        const select = document.getElementById('shipping_type');
        const cost = parseInt(select.options[select.selectedIndex].getAttribute('data-cost'));
        
        const formatRp = (angka) => {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        };

        document.getElementById('ongkirDisplay').innerText = formatRp(cost);

        const baseTotal = parseInt(document.getElementById('grandTotal').getAttribute('data-base'));
        const grandTotal = baseTotal + cost;

        document.getElementById('grandTotal').innerText = formatRp(grandTotal);
    }

    // Toggle Payment COD / Transfer
    function setPayment(method){
        document.getElementById('payment_method').value = method;
        const btnTransfer = document.getElementById('btnTransfer');
        const btnCOD = document.getElementById('btnCOD');
        const proofCont = document.getElementById('proofContainer');
        const codCont = document.getElementById('codContainer');

        if(method === 'transfer') {
            // Aktifkan Transfer
            btnTransfer.classList.replace('text-gray-500', 'text-[#0B5C4A]');
            btnTransfer.classList.replace('font-medium', 'font-bold');
            btnTransfer.classList.replace('border-transparent', 'border-gray-200');
            btnTransfer.classList.replace('hover:bg-gray-100', 'bg-white');
            btnTransfer.classList.add('shadow-sm', 'bg-white');
            
            // Matikan COD
            btnCOD.classList.replace('text-[#0B5C4A]', 'text-gray-500');
            btnCOD.classList.replace('font-bold', 'font-medium');
            btnCOD.classList.replace('border-gray-200', 'border-transparent');
            btnCOD.classList.replace('bg-white', 'hover:bg-gray-100');
            btnCOD.classList.remove('shadow-sm', 'bg-white');
            
            proofCont.classList.remove('hidden');
            proofCont.classList.add('block');
            codCont.classList.remove('block');
            codCont.classList.add('hidden');
        } else {
            // Aktifkan COD
            btnCOD.classList.replace('text-gray-500', 'text-[#0B5C4A]');
            btnCOD.classList.replace('font-medium', 'font-bold');
            btnCOD.classList.replace('border-transparent', 'border-gray-200');
            btnCOD.classList.replace('hover:bg-gray-100', 'bg-white');
            btnCOD.classList.add('shadow-sm', 'bg-white');
            
            // Matikan Transfer
            btnTransfer.classList.replace('text-[#0B5C4A]', 'text-gray-500');
            btnTransfer.classList.replace('font-bold', 'font-medium');
            btnTransfer.classList.replace('border-gray-200', 'border-transparent');
            btnTransfer.classList.replace('bg-white', 'hover:bg-gray-100');
            btnTransfer.classList.remove('shadow-sm', 'bg-white');

            proofCont.classList.remove('block');
            proofCont.classList.add('hidden');
            codCont.classList.remove('hidden');
            codCont.classList.add('block');
        }
    }

    // Capture file nama display
    document.getElementById('fileInput').addEventListener('change', function(){
        const fileArea = document.getElementById('fileArea');
        const fn = document.getElementById('fileName');
        if(this.files.length > 0){
            fn.textContent = this.files[0].name;
            fileArea.classList.remove('hidden');
            fileArea.classList.add('flex');
        } else {
            fileArea.classList.add('hidden');
            fileArea.classList.remove('flex');
        }
    });

    // Validasi Kosong (Toast Custom)
    function validateOrder(){
        const address = document.getElementById('main_address').value.trim();
        const method = document.getElementById('payment_method').value;

        if(address === ''){
            showToast("Alamat pengiriman masih kosong!", "assets/alert-alamat.png");
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

    // Modal Popup Custom Tailwinds
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

        wrapper.classList.remove('opacity-0', 'pointer-events-none');
        wrapper.classList.add('opacity-100');
        
        toast.classList.remove('scale-90', 'opacity-0');
        toast.classList.add('scale-100', 'opacity-100');

        setTimeout(() => {
            toast.classList.remove('scale-100', 'opacity-100');
            toast.classList.add('scale-90', 'opacity-0');
            wrapper.classList.remove('opacity-100');
            wrapper.classList.add('opacity-0', 'pointer-events-none');
        }, 3000);
    }
</script>

<div id="toastWrapper" class="fixed inset-0 flex items-center justify-center bg-black/40 opacity-0 pointer-events-none transition-all duration-300 z-[100] backdrop-blur-sm">
    <div id="toast" class="bg-white text-center px-10 py-8 rounded-3xl shadow-2xl scale-90 opacity-0 transition-all duration-300">
        <img id="toastImage" src="" class="w-auto max-w-sm max-h-72 object-contain mx-auto mb-6">
        <p id="toastText" class="text-gray-800 font-bold text-lg"></p>
    </div>
</div>

</body>
</html>