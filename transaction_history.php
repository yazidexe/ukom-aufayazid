<?php
session_start();
include "Admin/config/database.php";

$user_id = $_SESSION['user_id'] ?? null;

$orders     = [];
$all_items  = [];
$orders_json = [];

if($user_id){
    // Fetch orders
    $query = mysqli_query($conn, "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY id DESC");

    // Pre-load all order items for modal
    $q_all_items = mysqli_query($conn, "
        SELECT order_items.order_id, order_items.qty, order_items.price,
               products.name, products.description, products.picture
        FROM order_items
        JOIN products ON products.id = order_items.product_id
        WHERE order_items.order_id IN (
            SELECT id FROM orders WHERE user_id='$user_id'
        )
    ");
    if($q_all_items){
        while($item = mysqli_fetch_assoc($q_all_items)){
            $all_items[$item['order_id']][] = $item;
        }
    }

    // Build JSON for JS
    if($query){
        while($o = mysqli_fetch_assoc($query)){
            $oid = $o['id'];
            $items_data = [];
            foreach(($all_items[$oid] ?? []) as $it){
                $items_data[] = [
                    'name'        => $it['name'],
                    'description' => $it['description'],
                    'picture'     => $it['picture'],
                    'qty'         => (int)$it['qty'],
                    'price'       => (int)$it['price'],
                    'subtotal'    => (int)$it['qty'] * (int)$it['price'],
                ];
            }
            $orders_json[$oid] = [
                'id'              => $oid,
                'status'          => $o['status'],
                'payment_method'  => $o['payment_method'],
                'address'         => $o['address'],
                'total'           => (int)$o['total'],
                'created_at'      => $o['created_at'],
                'expedition_name' => $o['expedition_name'] ?? '',
                'shipping_type'   => $o['shipping_type'] ?? '',
                'shipping_cost'   => (int)($o['shipping_cost'] ?? 0),
                'items'           => $items_data,
            ];
        }
        mysqli_data_seek($query, 0); // reset pointer for HTML loop
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Riwayat Transaksi - Azula</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
</style>
</head>

<body class="text-gray-800 antialiased pb-16">

<?php include "includes/cart_header.php"; ?>

<div class="max-w-3xl mx-auto px-4 pt-10">

    <?php if(!$user_id): ?>

        <div class="bg-white rounded-2xl p-16 text-center shadow-sm border border-gray-100">
            <div class="w-20 h-20 bg-[#0B5C4A]/5 rounded-full flex items-center justify-center mx-auto mb-5">
                <i data-lucide="lock" class="w-10 h-10 text-[#0B5C4A]"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-700 mb-2">Belum Login</h2>
            <p class="text-gray-500 text-sm mb-6">Silakan masuk terlebih dahulu untuk melihat riwayat transaksi Anda.</p>
            <a href="login.php" class="inline-flex items-center gap-2 bg-[#0B5C4A] text-white px-6 py-3 rounded-xl font-semibold hover:bg-[#084b3c] transition-colors shadow-sm">
                <i data-lucide="log-in" class="w-4 h-4"></i> Masuk
            </a>
        </div>

    <?php elseif(!$query || mysqli_num_rows($query) == 0): ?>

        <div class="bg-white rounded-2xl p-16 text-center shadow-sm border border-gray-100">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-5 border border-gray-100">
                <i data-lucide="inbox" class="w-10 h-10 text-gray-400"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-700 mb-2">Belum ada transaksi</h2>
            <p class="text-gray-500 text-sm mb-6">Yuk mulai belanja dan temukan produk favoritmu!</p>
            <a href="index.php" class="inline-flex items-center gap-2 bg-[#0B5C4A] text-white px-6 py-3 rounded-xl font-semibold hover:bg-[#084b3c] transition-colors shadow-sm">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i> Mulai Belanja
            </a>
        </div>

    <?php else: ?>

        <div class="space-y-3">
        <?php while($order = mysqli_fetch_assoc($query)):
            $oid = $order['id'];
            $items_for_order = $all_items[$oid] ?? [];
            $prod_names  = array_map(fn($i) => $i['name'], $items_for_order);
            $prod_summary = implode(', ', array_slice($prod_names, 0, 2))
                          . (count($prod_names) > 2 ? ' +' . (count($prod_names)-2) . ' lainnya' : '');
            $status_class = $order['status'] === 'accepted'
                ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                : 'bg-amber-50 text-amber-700 border-amber-200';
            $status_label = $order['status'] === 'accepted' ? '✓ Diterima' : '⏳ Menunggu';
            $total_qty = array_sum(array_column($items_for_order, 'qty'));
        ?>
            <div onclick="openOrderModal(<?= $oid ?>)"
                 class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 cursor-pointer hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 group">

                <!-- Icon -->
                <div class="w-14 h-14 rounded-xl bg-[#0B5C4A]/5 flex items-center justify-center flex-shrink-0 group-hover:bg-[#0B5C4A]/10 transition-colors">
                    <i data-lucide="shopping-bag" class="w-7 h-7 text-[#0B5C4A]"></i>
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 truncate"><?= htmlspecialchars($prod_summary ?: 'Order #'.$oid); ?></p>
                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                        <span class="text-[11px] px-2 py-0.5 rounded-full border font-semibold <?= $status_class ?>"><?= $status_label ?></span>
                        <span class="text-xs text-gray-400"><?= date('d M Y', strtotime($order['created_at'])); ?></span>
                        <?php if($total_qty > 0): ?>
                        <span class="text-xs text-gray-400"><?= $total_qty ?> item</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Total + Chevron -->
                <div class="flex items-center gap-2 flex-shrink-0">
                    <div class="text-right">
                        <p class="font-bold text-[#0B5C4A]">Rp <?= number_format($order['total'],0,',','.'); ?></p>
                        <p class="text-xs text-gray-400"><?= strtoupper($order['payment_method']); ?></p>
                    </div>
                    <i data-lucide="chevron-right" class="w-5 h-5 text-gray-300 group-hover:text-[#0B5C4A] transition-colors"></i>
                </div>
            </div>
        <?php endwhile; ?>
        </div>

    <?php endif; ?>

</div>

<!-- ORDER DATA for JS -->
<script>const ORDER_DATA = <?= json_encode($orders_json); ?>;</script>

<!-- ORDER DETAIL MODAL -->
<div id="orderDetailModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
    <div id="orderDetailCard" class="bg-white w-full max-w-xl rounded-2xl shadow-2xl overflow-hidden scale-95 transition-transform duration-300 max-h-[90vh] flex flex-col">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-[#0B5C4A]/5 to-transparent flex-shrink-0">
            <div>
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i data-lucide="receipt" class="w-5 h-5 text-[#0B5C4A]"></i>
                    Detail Pesanan
                </h3>
                <p id="modal-order-id" class="text-xs text-gray-400 mt-0.5"></p>
            </div>
            <button onclick="closeOrderModal()" class="text-gray-400 hover:text-gray-700 hover:bg-gray-100 p-2 rounded-full transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <!-- Scrollable Body -->
        <div class="overflow-y-auto flex-1 p-6 space-y-5">
            <!-- Status + Meta -->
            <div class="flex flex-wrap gap-2">
                <span id="modal-status-badge" class="text-xs px-3 py-1 rounded-full border font-semibold"></span>
                <span id="modal-payment-badge" class="text-xs px-3 py-1 rounded-full border bg-gray-50 text-gray-600 border-gray-200 font-medium"></span>
                <span id="modal-date-badge" class="text-xs px-3 py-1 rounded-full border bg-gray-50 text-gray-500 border-gray-200"></span>
            </div>
            <!-- TRACKING STEPPER -->
            <div class="py-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Status Pengiriman</p>
                <div class="relative flex items-start justify-between" id="modal-stepper">
                    <div class="absolute top-4 left-0 right-0 h-0.5 bg-gray-200" style="z-index:0"></div>
                    <div id="stepper-line-fill" class="absolute top-4 left-0 h-0.5 bg-[#0B5C4A] transition-all duration-500" style="z-index:1; width:0%"></div>
                    <div class="stepper-step relative flex flex-col items-center text-center w-1/4" data-step="0">
                        <div class="step-circle w-8 h-8 rounded-full border-2 flex items-center justify-center bg-white z-10 transition-all duration-300" style="border-color:#d1d5db">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/><circle cx="12" cy="12" r="9" stroke-width="2"/></svg>
                        </div>
                        <p class="step-label text-[10px] mt-2 font-medium text-gray-400">Menunggu</p>
                    </div>
                    <div class="stepper-step relative flex flex-col items-center text-center w-1/4" data-step="1">
                        <div class="step-circle w-8 h-8 rounded-full border-2 flex items-center justify-center bg-white z-10 transition-all duration-300" style="border-color:#d1d5db">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/></svg>
                        </div>
                        <p class="step-label text-[10px] mt-2 font-medium text-gray-400">Diproses</p>
                    </div>
                    <div class="stepper-step relative flex flex-col items-center text-center w-1/4" data-step="2">
                        <div class="step-circle w-8 h-8 rounded-full border-2 flex items-center justify-center bg-white z-10 transition-all duration-300" style="border-color:#d1d5db">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </div>
                        <p class="step-label text-[10px] mt-2 font-medium text-gray-400">Dikirim</p>
                    </div>
                    <div class="stepper-step relative flex flex-col items-center text-center w-1/4" data-step="3">
                        <div class="step-circle w-8 h-8 rounded-full border-2 flex items-center justify-center bg-white z-10 transition-all duration-300" style="border-color:#d1d5db">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="step-label text-[10px] mt-2 font-medium text-gray-400">Selesai</p>
                    </div>
                </div>
            </div>
            <!-- Products -->
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Produk Dipesan</p>
                <div id="modal-items" class="space-y-4"></div>
            </div>
            <!-- Shipping -->
            <div id="modal-shipping-section" class="hidden">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Pengiriman</p>
                <div class="bg-gray-50 rounded-xl p-4 text-sm space-y-1">
                    <p class="text-gray-700"><span class="font-medium">Ekspedisi:</span> <span id="modal-expedition"></span></p>
                    <p class="text-gray-700"><span class="font-medium">Ongkir:</span> <span id="modal-ongkir"></span></p>
                </div>
            </div>
            <!-- Address -->
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Alamat Pengiriman</p>
                <p id="modal-address" class="text-sm text-gray-700 bg-gray-50 rounded-xl p-4"></p>
            </div>
        </div>
        <!-- Footer Total -->
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-gray-50/60 flex-shrink-0">
            <p class="text-sm text-gray-500 font-medium">Total Pembayaran</p>
            <p id="modal-total" class="text-xl font-bold text-[#0B5C4A]"></p>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    const orderModal     = document.getElementById('orderDetailModal');
    const orderModalCard = document.getElementById('orderDetailCard');

    function rupiah(n){ return 'Rp ' + Number(n).toLocaleString('id-ID'); }

    function openOrderModal(orderId){
        const o = ORDER_DATA[orderId];
        if(!o) return;

        document.getElementById('modal-order-id').textContent =
            'Order #' + o.id + ' · ' + new Date(o.created_at).toLocaleDateString('id-ID',{day:'numeric',month:'long',year:'numeric'});

        const statusBadge = document.getElementById('modal-status-badge');
        const statusMap = {
            pending:    ['bg-amber-50 text-amber-700 border-amber-200',     '⏳ Menunggu'],
            processing: ['bg-blue-50 text-blue-700 border-blue-200',        '🔄 Diproses'],
            shipped:    ['bg-purple-50 text-purple-700 border-purple-200',  '🚚 Dikirim'],
            delivered:  ['bg-emerald-50 text-emerald-700 border-emerald-200','✓ Selesai'],
        };
        const [sCls, sLbl] = statusMap[o.status] ?? ['bg-gray-50 text-gray-600 border-gray-200', o.status];
        statusBadge.textContent = sLbl;
        statusBadge.className = `text-xs px-3 py-1 rounded-full border font-semibold ${sCls}`;

        // Stepper
        const stepOrder = ['pending','processing','shipped','delivered'];
        const currentStep = stepOrder.indexOf(o.status);
        document.querySelectorAll('.stepper-step').forEach(step => {
            const idx = parseInt(step.dataset.step);
            const circle = step.querySelector('.step-circle');
            const label  = step.querySelector('.step-label');
            if(idx <= currentStep){
                circle.style.borderColor = '#0B5C4A';
                circle.style.backgroundColor = '#0B5C4A';
                circle.querySelector('svg').style.stroke = 'white';
                label.style.color = '#0B5C4A';
                label.style.fontWeight = '600';
            } else {
                circle.style.borderColor = '#d1d5db';
                circle.style.backgroundColor = 'white';
                circle.querySelector('svg').style.stroke = '#9ca3af';
                label.style.color = '#9ca3af';
                label.style.fontWeight = '500';
            }
        });
        const fillPct = currentStep === 0 ? 0 : (currentStep / 3) * 100;
        document.getElementById('stepper-line-fill').style.width = fillPct + '%';

        document.getElementById('modal-payment-badge').textContent = o.payment_method.toUpperCase();
        document.getElementById('modal-date-badge').textContent = new Date(o.created_at).toLocaleDateString('id-ID',{day:'numeric',month:'short',year:'numeric'});
        document.getElementById('modal-address').textContent = o.address || '-';
        document.getElementById('modal-total').textContent = rupiah(o.total);

        // Shipping
        if(o.expedition_name){
            document.getElementById('modal-shipping-section').classList.remove('hidden');
            document.getElementById('modal-expedition').textContent = o.expedition_name + (o.shipping_type ? ' ('+o.shipping_type+')' : '');
            document.getElementById('modal-ongkir').textContent = rupiah(o.shipping_cost);
        } else {
            document.getElementById('modal-shipping-section').classList.add('hidden');
        }

        // Items
        const container = document.getElementById('modal-items');
        container.innerHTML = '';
        o.items.forEach(item => {
            const desc = item.description
                ? item.description.substring(0, 120) + (item.description.length > 120 ? '...' : '')
                : '';
            const imgSrc = item.picture
                ? '/ukom-project/uploads/' + item.picture
                : '/ukom-project/assets/default-avatar.png';
            container.innerHTML += `
                <div class="flex gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <img src="${imgSrc}" onerror="this.src='/ukom-project/assets/default-avatar.png'"
                         class="w-20 h-20 rounded-lg object-cover flex-shrink-0 border border-gray-200">
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm leading-snug">${item.name}</p>
                        ${desc ? `<p class="text-xs text-gray-500 mt-1 leading-relaxed">${desc}</p>` : ''}
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-xs text-gray-500">${item.qty} × ${rupiah(item.price)}</span>
                            <span class="text-sm font-bold text-[#0B5C4A]">${rupiah(item.subtotal)}</span>
                        </div>
                    </div>
                </div>`;
        });

        // Show modal
        orderModal.classList.remove('opacity-0','pointer-events-none');
        orderModal.classList.add('opacity-100');
        setTimeout(() => {
            orderModalCard.classList.remove('scale-95');
            orderModalCard.classList.add('scale-100');
        }, 20);
    }

    function closeOrderModal(){
        orderModalCard.classList.remove('scale-100');
        orderModalCard.classList.add('scale-95');
        setTimeout(() => {
            orderModal.classList.remove('opacity-100');
            orderModal.classList.add('opacity-0','pointer-events-none');
        }, 200);
    }

    // Close on backdrop
    orderModal.addEventListener('click', function(e){
        if(e.target === orderModal) closeOrderModal();
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e){
        if(e.key === 'Escape') closeOrderModal();
    });
</script>

</body>
</html>