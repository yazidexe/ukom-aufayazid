<?php
session_start();
include "Admin/config/database.php";

// ❌ BELUM LOGIN
if(!isset($_SESSION['user_id'])){
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profile</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; }
</style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="bg-white w-full max-w-md p-10 rounded-md shadow-sm border border-gray-200 text-center relative">
        <div class="relative w-24 h-24 mx-auto mb-6 rounded-full p-1 bg-[#199276]">
            <img src="assets/default-avatar.png" class="w-full h-full object-cover rounded-full border-4 border-white shadow-sm">
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2 relative">Anda belum memiliki profil</h2>
        <p class="text-gray-500 mb-8 text-sm relative">Harap login agar bisa mulai berbelanja ✨</p>
        <div class="flex flex-col gap-4 relative">
            <a href="login.php" class="block w-full bg-[#0B5C4A] text-white py-3.5 rounded-md font-medium tracking-wide hover:-translate-y-0.5 shadow-sm transition-all duration-300">
                Masuk
            </a>
            <a href="register.php" class="block w-full border border-[#0B5C4A]/20 text-[#0B5C4A] py-3.5 rounded-md font-medium hover:bg-[#0B5C4A]/5 transition-all duration-300">
                Daftar Akun Baru
            </a>
        </div>
        <a href="index.php" class="inline-block mt-8 text-sm font-medium text-gray-400 hover:text-[#0B5C4A] transition-colors relative">
            ← Kembali ke Beranda
        </a>
    </div>
</body>
</html>

<?php
exit;
}
?>

<?php
$user_id = $_SESSION['user_id'];

// 🔥 DATA USER
$user = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM users WHERE id='$user_id'
"));

// 🔥 TOTAL ORDER
$totalOrderQuery = mysqli_query($conn, "
    SELECT COUNT(*) as total FROM orders WHERE user_id='$user_id'
");
$totalOrder = $totalOrderQuery ? mysqli_fetch_assoc($totalOrderQuery)['total'] : 0;

// 🔥 TOTAL ITEM
$totalItemQuery = mysqli_query($conn, "
    SELECT SUM(order_items.qty) as total 
    FROM order_items 
    JOIN orders ON orders.id = order_items.order_id
    WHERE orders.user_id='$user_id'
");
$totalItem = $totalItemQuery ? (mysqli_fetch_assoc($totalItemQuery)['total'] ?? 0) : 0;

// 🔥 HISTORY
$history = mysqli_query($conn, "
    SELECT orders.*, products.name AS product_name, order_items.qty
    FROM orders
    JOIN order_items ON orders.id = order_items.order_id
    JOIN products ON products.id = order_items.product_id
    WHERE orders.user_id='$user_id'
    ORDER BY orders.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profile - Azula</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }
    /* Hide scrollbar for clean look */
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
</style>
</head>

<body class="text-gray-800 antialiased relative pb-10">

<!-- HEADER / NAV PLACEHOLDER (You can include your navbar here) -->
<nav class="w-full bg-white border-b border-gray-100 shadow-sm px-8 py-4 flex items-center justify-between sticky top-0 z-40">
    <a href="index.php" class="text-sm font-medium text-[#0B5C4A] flex items-center gap-2">
        <i data-lucide="arrow-left" class="w-5 h-5"></i> Kembali ke Beranda 
    </a>
    <h1 class="text-lg font-semibold text-gray-700">Profil Saya</h1>
    <div class="w-8"></div>
</nav>

<!-- MAIN CONTAINER -->
<div class="max-w-4xl mx-auto px-4 mt-10">

    <!-- HERO PROFILE CARD -->
    <div class="bg-white border border-gray-200 rounded-md p-8 shadow-sm relative flex flex-col md:flex-row items-center gap-8 mb-10 transition-transform duration-300 hover:shadow-md hover:-translate-y-1">
        
        <!-- 3 Dots Menu -->
        <div class="absolute top-4 right-4 z-50">
            <button onclick="toggleMenu()" class="p-4 hover:bg-gray-100 rounded-md transition-colors group cursor-pointer flex items-center justify-center">
                <i data-lucide="more-vertical" class="w-6 h-6 text-gray-400 group-hover:text-gray-700"></i>
            </button>
            
            <div id="profile-menu" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg py-2 z-50 transform origin-top-right transition-all duration-200">
                <button onclick="openModal()" class="w-full text-left px-5 py-3 hover:bg-[#0B5C4A]/5 flex items-center gap-3 text-sm font-medium text-gray-700 transition-colors">
                    <i data-lucide="edit-3" class="w-4 h-4 text-[#0B5C4A]"></i> Edit Profil
                </button>
                <div class="h-px bg-gray-100 my-1"></div>
                <button onclick="confirmHapus()" class="w-full text-left px-5 py-3 hover:bg-red-50 flex items-center gap-3 text-sm font-medium text-red-600 transition-colors">
                    <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus Profile
                </button>
            </div>
        </div>

        <!-- Avatar -->
        <div class="relative group cursor-pointer z-10 shrink-0">
            <div class="w-32 h-32 rounded-full p-1 bg-[#199276] shadow-lg">
                <img src="<?= !empty($_SESSION['avatar']) ? '/ukom-project/'.$_SESSION['avatar'] : 'assets/default-avatar.png'; ?>" 
                    class="w-full h-full rounded-full object-cover border-4 border-white">
            </div>
            <!-- Update Avatar Overlay -->
            <button onclick="document.getElementById('avatar-upload').click()" class="absolute inset-0 m-1 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <i data-lucide="camera" class="w-8 h-8 text-white"></i>
            </button>
            <form action="upload_avatar.php" method="POST" enctype="multipart/form-data" class="hidden" id="form-avatar">
                <input type="file" name="avatar" id="avatar-upload" accept="image/*" onchange="document.getElementById('form-avatar').submit()">
            </form>
        </div>

        <!-- Info -->
        <div class="flex-1 text-center md:text-left z-10">
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight flex items-center justify-center md:justify-start gap-2">
                <?= htmlspecialchars($user['name']); ?>
                <i data-lucide="badge-check" class="w-6 h-6 text-[#199276]"></i>
            </h2>
            <p class="text-gray-500 font-medium mt-1 mb-4 flex items-center justify-center md:justify-start gap-2">
                <i data-lucide="mail" class="w-4 h-4"></i> <?= htmlspecialchars($user['email']); ?>
            </p>
            <div class="bg-gray-50 flex items-center w-fit gap-2 px-4 py-2 mt-2 rounded-md text-sm text-gray-600 border border-gray-200 shadow-sm">
                <i data-lucide="map-pin" class="w-4 h-4 text-[#0B5C4A]"></i>
                <span class="max-w-[200px] md:max-w-xs truncate"><?= htmlspecialchars($user['address'] ?: 'Alamat belum diatur'); ?></span>
            </div>
            
            <div class="mt-5 text-center md:text-left">
                <a href="logout.php" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-red-50 text-red-600 font-semibold rounded-md hover:bg-red-100 hover:shadow-sm border border-red-100 transition-all">
                    <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
                </a>
            </div>
        </div>
    </div>

    <!-- FLASH MESSAGE -->
    <?php if(isset($_SESSION['success'])): ?>
        <div id="flash-message" class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-md flex items-center gap-3 mb-8 shadow-sm">
            <i data-lucide="check-circle-2" class="w-5 h-5"></i>
            <span class="font-medium"><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
            <button onclick="document.getElementById('flash-message').style.display='none'" class="ml-auto opacity-50 hover:opacity-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div id="flash-error" class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-md flex items-center gap-3 mb-8 shadow-sm">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            <span class="font-medium"><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
            <button onclick="document.getElementById('flash-error').style.display='none'" class="ml-auto opacity-50 hover:opacity-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
        <div class="bg-white p-6 rounded-md shadow-sm border border-gray-200 flex items-center gap-6 hover:shadow-md transition-shadow group">
            <div class="w-16 h-16 rounded-md bg-[#0B5C4A]/10 flex items-center justify-center transition-transform duration-300">
                <i data-lucide="shopping-bag" class="w-8 h-8 text-[#0B5C4A]"></i>
            </div>
            <div>
                <p class="text-gray-500 font-medium text-sm text-transform: uppercase tracking-wider mb-1">Total Pesanan</p>
                <h2 class="text-3xl font-bold text-gray-800"><?= $totalOrder; ?></h2>
            </div>
        </div>

        <div class="bg-white p-6 rounded-md shadow-sm border border-gray-200 flex items-center gap-6 hover:shadow-md transition-shadow group">
            <div class="w-16 h-16 rounded-md bg-[#199276]/10 flex items-center justify-center transition-transform duration-300">
                <i data-lucide="package" class="w-8 h-8 text-[#199276]"></i>
            </div>
            <div>
                <p class="text-gray-500 font-medium text-sm text-transform: uppercase tracking-wider mb-1">Barang Dibeli</p>
                <h2 class="text-3xl font-bold text-gray-800"><?= $totalItem; ?></h2>
            </div>
        </div>
    </div>

    <!-- HISTORY -->
    <div>
        <div class="flex items-center gap-3 mb-6">
            <h2 class="text-xl font-medium text-gray-800">Riwayat Pesanan</h2>
            <div class="h-px flex-1 bg-gradient-to-r from-gray-200 to-transparent"></div>
        </div>

        <div class="space-y-4">
        <?php if($history && mysqli_num_rows($history) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($history)): ?>
                <div class="bg-white p-5 rounded-md shadow-sm border border-gray-200 flex items-center justify-between hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-md bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0">
                            <i data-lucide="receipt" class="w-6 h-6 text-gray-400"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800 text-lg"><?= htmlspecialchars($row['product_name']); ?></p>
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1 mt-0.5">
                                <i data-lucide="hash" class="w-3 h-3"></i> Qty: <?= $row['qty']; ?>
                            </p>
                        </div>
                    </div>
                    <div class="text-right flex flex-col gap-1 items-end">
                        <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-md bg-gray-100 text-gray-500">
                            <i data-lucide="calendar" class="w-3 h-3"></i>
                            <?= date('d M Y', strtotime($row['created_at'])); ?>
                        </span>
                        <p class="text-[#0B5C4A] font-medium text-lg mt-1 relative inline-block">
                            Rp <?= number_format($row['total'],0,',','.'); ?>
                        </p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="bg-white border border-dashed border-gray-300 rounded-md p-12 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-md flex items-center justify-center mx-auto mb-4 border border-gray-100">
                    <i data-lucide="inbox" class="w-10 h-10 text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-700 mb-1">Belum ada pesanan</h3>
                <p class="text-gray-500 mb-6">Sepertinya Anda belum melakukan pembelian pertama.</p>
                <a href="index.php" class="inline-flex items-center gap-2 bg-[#0B5C4A] text-white px-6 py-3 rounded-md font-medium hover:bg-[#084b3c] transition-colors shadow-sm">
                    Mulai Belanja
                </a>
            </div>
        <?php endif; ?>
        </div>
    </div>

</div>

<!-- EDIT PROFILE MODAL -->
<div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
    <div class="bg-white w-full max-w-lg rounded-md shadow-lg overflow-hidden scale-95 transition-transform duration-300" id="modalCard">
        <div class="px-8 py-6 border-b border-gray-200 flex items-center justify-between bg-gray-50/50">
            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i data-lucide="user-cog" class="w-5 h-5 text-[#0B5C4A]"></i> Edit Profil
            </h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-700 hover:bg-gray-100 p-2 rounded-full transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <form action="update_profile.php" method="POST" class="p-8">
            <!-- Email -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                <div class="relative">
                    <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" readonly
                        class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-md bg-gray-50 text-gray-500 cursor-not-allowed focus:outline-none">
                </div>
            </div>

            <!-- Name -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Display Name</label>
                <div class="relative">
                    <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>" 
                        class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-md focus:border-[#0B5C4A] focus:ring-1 focus:ring-[#0B5C4A] transition-all outline-none">
                </div>
                <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                    <i data-lucide="info" class="w-3 h-3 text-red-400"></i> Display name hanya bisa diubah sekali setiap 7 hari.
                </p>
            </div>

            <!-- Address -->
            <div class="mb-8">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Pengiriman Tetap</label>
                <div class="relative">
                    <i data-lucide="map" class="absolute left-4 top-4 w-5 h-5 text-gray-400"></i>
                    <textarea name="address" rows="3" placeholder="Masukkan alamat lengkap"
                        class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-md focus:border-[#0B5C4A] focus:ring-1 focus:ring-[#0B5C4A] transition-all outline-none resize-none"><?= htmlspecialchars($user['address']); ?></textarea>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeModal()" class="flex-1 px-6 py-3 border border-gray-200 text-gray-600 rounded-md font-semibold hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-6 py-3 bg-[#0B5C4A] text-white rounded-md font-semibold hover:bg-[#084b3c] shadow-sm hover:shadow-md transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Initialize icons
    lucide.createIcons();

    // Menu Toggle
    const menuBtn = document.querySelector('button[onclick="toggleMenu()"]');
    const menu = document.getElementById('profile-menu');
    
    function toggleMenu() {
        menu.classList.toggle('hidden');
    }

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!menuBtn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });

    // Modal logic
    const modal = document.getElementById('editModal');
    const modalCard = document.getElementById('modalCard');

    function openModal() {
        menu.classList.add('hidden');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100');
        setTimeout(() => {
            modalCard.classList.remove('scale-95');
            modalCard.classList.add('scale-100');
        }, 50);
    }

    function closeModal() {
        modalCard.classList.remove('scale-100');
        modalCard.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0', 'pointer-events-none');
        }, 200);
    }
    
    function confirmHapus() {
        if(confirm("yakin ingin hapus profile?")) {
            window.location.href = "delete_profile.php";
        }
    }
</script>

</body>
</html>