<?php
$avatar = $_SESSION['avatar'] ?? null;

if(!$avatar && isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
    $q = mysqli_query($conn, "SELECT avatar FROM users WHERE id='$user_id'");
    $d = mysqli_fetch_assoc($q);
    $avatar = $d['avatar'] ?? null;
}
?>

<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$count = 0;

if(isset($_SESSION['cart'])){
    $count = count($_SESSION['cart']); // total unique products
}
?>

<!-- NAVBAR -->
<nav class="fixed top-0 left-0 w-full z-50 
bg-[#0B5C4A]/90 backdrop-blur-md 
flex items-center justify-between px-10 py-5">

    <!-- LEFT -->
    <div class="flex items-center gap-6 text-sm">

        <a href="index.php" class="text-white hover:opacity-80 transition">
            Beranda
        </a>

        <!-- DROPDOWN SHOP (pindah ke sini) -->
        <div class="relative">

            <button id="shopButton"
                class="flex items-center gap-2 text-white hover:opacity-80 transition">
                Belanja
                <svg id="arrowIcon" xmlns="http://www.w3.org/2000/svg"
                    class="w-4 h-4 transition-transform duration-300"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown -->
            <div id="shopMenu"
                class="absolute left-0 mt-3 w-48
                    bg-[#199276] text-white rounded-md shadow-lg
                    opacity-0 translate-y-2 scale-95
                    pointer-events-none
                    transition-all duration-200 ease-out">

                <a href="category.php?kategori=Shirt"
                class="block px-5 py-3 hover:bg-white/20 transition">
                    Kemeja
                </a>

                <a href="category.php?kategori=Tshirt"
                class="block px-5 py-3 hover:bg-white/20 transition">
                    Kaos
                </a>

                <a href="category.php?kategori=Pants"
                class="block px-5 py-3 hover:bg-white/20 transition">
                    Celana
                </a>

                <a href="category.php?kategori=Shoes"
                class="block px-5 py-3 hover:bg-white/20 transition">
                    Sepatu
                </a>

            </div>

        </div>

    </div>

    <!-- CENTER BRAND -->
    <div class="brand text-2xl text-white tracking-wide">
        Azula
    </div>

    <!-- RIGHT -->
    <div class="flex items-center gap-5 text-white">

        <!-- NOTIFICATION BELL -->
        <div class="relative" id="notifWrapper">
            <?php
            $notifications = [];
            $user_email = '';
            if(isset($_SESSION['user_id'])){
                $u_id = $_SESSION['user_id'];

                // Ambil email user
                $q_user_email = mysqli_query($conn, "SELECT email FROM users WHERE id='$u_id'");
                if($q_user_email && $row_email = mysqli_fetch_assoc($q_user_email)){
                    $user_email = $row_email['email'];
                }

                $q_orders = mysqli_query($conn, "SELECT id, status, created_at FROM orders WHERE user_id='$u_id' ORDER BY id DESC LIMIT 5");
                if($q_orders){
                    while($row_o = mysqli_fetch_assoc($q_orders)){
                        $order_id = $row_o['id'];
                        $status = $row_o['status'];
                        $created_at = $row_o['created_at'];

                        $q_items = mysqli_query($conn, "SELECT products.name FROM order_items JOIN products ON order_items.product_id = products.id WHERE order_items.order_id='$order_id'");
                        $item_names = [];
                        while($i = mysqli_fetch_assoc($q_items)){
                            $item_names[] = $i['name'];
                        }
                        $produk_str = implode(', ', $item_names);

                        if($status === 'pending'){
                            $notifications[] = [
                                'title'      => 'Berhasil Checkout',
                                'desc'       => 'Anda berhasil checkout produk: ' . $produk_str . '.',
                                'icon'       => 'shopping-bag',
                                'icon_color' => 'text-blue-600',
                                'icon_bg'    => 'bg-blue-50',
                                'link'       => 'transaction_history.php',
                                'link_label' => 'Lihat Riwayat Transaksi →',
                                'link_target'=> '_self'
                            ];
                        } elseif($status === 'processing'){
                            $notifications[] = [
                                'title'      => 'Pesanan Diproses',
                                'desc'       => 'Pesanan Anda sedang dipersiapkan oleh tim kami.',
                                'icon'       => 'package',
                                'icon_color' => 'text-blue-600',
                                'icon_bg'    => 'bg-blue-50',
                                'link'       => 'transaction_history.php',
                                'link_label' => 'Lihat Detail →',
                                'link_target'=> '_self'
                            ];
                        } elseif($status === 'shipped'){
                            $notifications[] = [
                                'title'      => 'Pesanan Dikirim! 🚚',
                                'desc'       => 'Pesanan Anda dalam perjalanan: ' . $produk_str . '.',
                                'icon'       => 'truck',
                                'icon_color' => 'text-purple-600',
                                'icon_bg'    => 'bg-purple-50',
                                'link'       => 'https://mail.google.com/mail/u/' . urlencode($user_email),
                                'link_label' => 'Buka Email Anda →',
                                'link_target'=> '_blank'
                            ];
                        } elseif($status === 'delivered'){
                            $notifications[] = [
                                'title'      => 'Pesanan Selesai! ✅',
                                'desc'       => 'Pesanan ' . $produk_str . ' telah sampai. Terima kasih sudah belanja di Azula!',
                                'icon'       => 'badge-check',
                                'icon_color' => 'text-[#0B5C4A]',
                                'icon_bg'    => 'bg-[#0B5C4A]/10',
                                'link'       => 'transaction_history.php',
                                'link_label' => 'Lihat Riwayat →',
                                'link_target'=> '_self'
                            ];
                        }
                    }
                }
            }
            $notif_count = count($notifications);
            ?>

            <button id="notifBtn" class="relative flex items-center justify-center text-white hover:opacity-80 transition h-6 w-6">
                <i data-lucide="bell" class="w-6 h-6"></i>
                <?php if($notif_count > 0): ?>
                <span id="notifDot" class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-[#0B5C4A]"></span>
                <?php endif; ?>
            </button>

            <!-- Dropdown Panel -->
            <div id="notifMenu"
                class="absolute right-0 mt-3 w-80
                    bg-white text-gray-800 rounded-2xl shadow-2xl border border-gray-100
                    opacity-0 translate-y-2 scale-95 pointer-events-none
                    transition-all duration-200 ease-out z-50">

                <!-- Header -->
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <p class="font-bold text-[#0B5C4A] text-sm">Notifikasi</p>
                    <?php if($notif_count > 0): ?>
                    <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-medium"><?= $notif_count; ?> baru</span>
                    <?php endif; ?>
                </div>

                <div class="max-h-80 overflow-y-auto">
                    <?php if(empty($notifications)): ?>
                        <div class="px-5 py-6 text-center text-gray-500 text-sm">Belum ada notifikasi</div>
                    <?php else: ?>
                        <?php foreach($notifications as $n): ?>
                        <a href="<?= htmlspecialchars($n['link']); ?>" target="<?= $n['link_target']; ?>" onclick="markRead()" class="px-5 py-4 flex gap-4 hover:bg-[#0B5C4A]/5 transition border-b border-gray-50 cursor-pointer group no-underline">
                            <div class="flex-shrink-0 w-10 h-10 <?= $n['icon_bg']; ?> rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                <i data-lucide="<?= $n['icon']; ?>" class="w-5 h-5 <?= $n['icon_color']; ?>"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($n['title']); ?></p>
                                <p class="text-xs text-gray-500 mt-1 leading-relaxed"><?= htmlspecialchars($n['desc']); ?></p>
                                <p class="text-[11px] text-[#0B5C4A] font-semibold mt-1.5 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <?= htmlspecialchars($n['link_label']); ?>
                                </p>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <a href="cart.php" class="relative flex items-center justify-center h-6 w-6">
        
            <!-- ICON -->
            <i data-lucide="shopping-cart" class="w-6 h-6"></i>

            <!-- BADGE -->
            <?php if($count > 0): ?>
            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs 
                    w-5 h-5 flex items-center justify-center rounded-full
                    animate-bounce">
                <?= $count; ?>
            </span>
            <?php endif; ?>

        </a>

        <div class="flex items-center gap-4">

            <?php if(isset($_SESSION['user_id'])): ?>

                <!-- USER LOGIN -->
                <a href="profile.php" class="flex items-center gap-2">

                    <span class="text-sm text-white">
                        <?= $_SESSION['user_name']; ?>
                    </span>

                    <img src="<?= !empty($_SESSION['avatar']) 
                        ? '/ukom-project/'.$_SESSION['avatar'] 
                        : 'assets/default-avatar.png'; ?>" 
                    class="w-8 h-8 rounded-full object-cover border-2 border-white">
                    </a>

            <?php else: ?>

                <!-- BELUM LOGIN -->
                <a href="profile.php">
                    <img src="assets/default-avatar.png"
                        class="w-8 h-8 rounded-full object-cover border-2 border-white hover:opacity-80 transition">
                </a>

            <?php endif; ?>

        </div>

</div>
</nav>

<script>
    const shopButton = document.getElementById("shopButton");
    const shopMenu = document.getElementById("shopMenu");
    const arrowIcon = document.getElementById("arrowIcon");

    let isOpen = false;

    shopButton.addEventListener("click", function (e) {
        e.stopPropagation();
        isOpen = !isOpen;

        if (isOpen) {
            shopMenu.classList.remove("opacity-0", "translate-y-2", "scale-95", "pointer-events-none");
            shopMenu.classList.add("opacity-100", "translate-y-0", "scale-100");
            arrowIcon.classList.add("rotate-180");
        } else {
            shopMenu.classList.add("opacity-0", "translate-y-2", "scale-95", "pointer-events-none");
            shopMenu.classList.remove("opacity-100", "translate-y-0", "scale-100");
            arrowIcon.classList.remove("rotate-180");
        }
    });

    document.addEventListener("click", function (e) {
        if (!shopMenu.contains(e.target) && !shopButton.contains(e.target)) {
            shopMenu.classList.add("opacity-0", "translate-y-2", "scale-95", "pointer-events-none");
            shopMenu.classList.remove("opacity-100", "translate-y-0", "scale-100");
            arrowIcon.classList.remove("rotate-180");
            isOpen = false;
        }
    });
</script>

<script>
    // === NOTIFICATION BELL ===
    const notifBtn = document.getElementById('notifBtn');
    const notifMenu = document.getElementById('notifMenu');
    let notifOpen = false;

    notifBtn.addEventListener('click', function(e){
        e.stopPropagation();
        notifOpen = !notifOpen;
        if(notifOpen){
            notifMenu.classList.remove('opacity-0','translate-y-2','scale-95','pointer-events-none');
            notifMenu.classList.add('opacity-100','translate-y-0','scale-100');
        } else {
            notifMenu.classList.add('opacity-0','translate-y-2','scale-95','pointer-events-none');
            notifMenu.classList.remove('opacity-100','translate-y-0','scale-100');
        }
    });

    document.addEventListener('click', function(e){
        if(!notifMenu.contains(e.target) && !notifBtn.contains(e.target)){
            notifMenu.classList.add('opacity-0','translate-y-2','scale-95','pointer-events-none');
            notifMenu.classList.remove('opacity-100','translate-y-0','scale-100');
            notifOpen = false;
        }
    });

    function markRead(){
        const dot = document.getElementById('notifDot');
        if(dot) dot.style.display = 'none';
        const badge = document.querySelector('#notifMenu .text-xs.bg-red-100');
        if(badge) badge.style.display = 'none';
    }
</script>

<script>
const profileBtn = document.getElementById("profileBtn");
const profileMenu = document.getElementById("profileMenu");
const profileArrow = document.getElementById("profileArrow");

let profileOpen = false;

if(profileBtn){
    profileBtn.addEventListener("click", function(e){
        e.stopPropagation();
        profileOpen = !profileOpen;

        if(profileOpen){
            profileMenu.classList.remove("opacity-0","translate-y-2","scale-95","pointer-events-none");
            profileMenu.classList.add("opacity-100","translate-y-0","scale-100");
            profileArrow.classList.add("rotate-180");
        } else {
            profileMenu.classList.add("opacity-0","translate-y-2","scale-95","pointer-events-none");
            profileMenu.classList.remove("opacity-100","translate-y-0","scale-100");
            profileArrow.classList.remove("rotate-180");
        }
    });

    document.addEventListener("click", function(e){
        if(!profileMenu.contains(e.target) && !profileBtn.contains(e.target)){
            profileMenu.classList.add("opacity-0","translate-y-2","scale-95","pointer-events-none");
            profileMenu.classList.remove("opacity-100","translate-y-0","scale-100");
            profileArrow.classList.remove("rotate-180");
            profileOpen = false;
        }
    });
}
</script>