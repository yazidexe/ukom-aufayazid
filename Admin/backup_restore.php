<?php 
// Sort by newest first
$files = glob("../backup/*.sql");
if($files) {
    usort($files, function($a, $b){ return filemtime($b) - filemtime($a); });
}
$files = $files ?: [];

// Tandai file terbaru (dari query string setelah backup sukses)
$newFile = $_GET['new'] ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Backup Data - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        @keyframes highlightFade {
            0%   { background-color: #d1fae5; }
            70%  { background-color: #d1fae5; }
            100% { background-color: transparent; }
        }
        .row-new { animation: highlightFade 3s ease forwards; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- NAVBAR -->
    <div class="bg-[#0B483A] rounded-b-3xl px-8 py-6 flex items-center justify-between">
        <h1 class="text-white text-2xl font-semibold">Dasbor Admin</h1>
        <a href="logout.php" class="bg-[#199276] text-[#0B483A] px-6 py-2 rounded-br-2xl font-medium hover:opacity-90 transition">Keluar</a>
    </div>

    <!-- MENU -->
    <div class="px-8 mt-4 flex justify-center">
        <ul class="flex gap-8 text-sm text-gray-400">
            <li><a href="dashboard.php" class="hover:text-[#0B483A] transition">beranda</a></li>
            <li><a href="users.php" class="hover:text-[#0B483A] transition">manajemen pengguna</a></li>
            <li><a href="products.php" class="hover:text-[#0B483A] transition">manajemen produk</a></li>
            <li><a href="reports.php" class="hover:text-[#0B483A] transition">laporan</a></li>
            <li><a href="transactions.php" class="hover:text-[#0B483A] transition">manajemen transaksi</a></li>
            <li><a href="backup.php" class="text-[#0B483A] font-semibold">backup data</a></li>
        </ul>
    </div>

    <!-- CONTENT -->
    <div class="max-w-4xl mx-auto px-6 mt-8">

        <!-- STATUS ALERTS -->
        <?php if(isset($_GET['status'])): ?>
        <div class="mb-6 px-5 py-4 rounded-xl flex items-center gap-3 text-sm font-medium
            <?= $_GET['status'] === 'success' ? 'bg-green-50 text-green-700 border border-green-200'
              : ($_GET['status'] === 'restored' ? 'bg-blue-50 text-blue-700 border border-blue-200'
              : 'bg-red-50 text-red-700 border border-red-200'); ?>">
            <i data-lucide="<?= in_array($_GET['status'], ['success','restored']) ? 'check-circle' : 'alert-circle'; ?>" class="w-5 h-5 flex-shrink-0"></i>
            <?php if($_GET['status'] === 'success'): ?>
                Backup berhasil dibuat!
            <?php elseif($_GET['status'] === 'restored'): ?>
                Restore database berhasil!
            <?php else: ?>
                Gagal membuat backup. Pastikan XAMPP berjalan dan folder backup bisa ditulis.
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- CARD DAFTAR FILE -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-5 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#0B483A]/10 rounded-xl flex items-center justify-center">
                        <i data-lucide="database" class="w-5 h-5 text-[#0B483A]"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-gray-800">File Backup</h2>
                        <p class="text-xs text-gray-400"><?= count($files); ?> file tersedia</p>
                    </div>
                </div>
                <a href="backup.php" class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#0B483A] transition font-medium">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
                </a>
            </div>

            <!-- Table -->
            <?php if(empty($files)): ?>
            <div class="text-center py-16 text-gray-400">
                <i data-lucide="folder-open" class="w-12 h-12 mx-auto mb-3 opacity-40"></i>
                <p class="text-sm">Belum ada file backup tersedia.</p>
            </div>
            <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-6 text-left">Nama File</th>
                            <th class="py-3 px-6 text-left">Waktu Backup</th>
                            <th class="py-3 px-6 text-left">Ukuran</th>
                            <th class="py-3 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php foreach($files as $index => $file): 
                            $basename = basename($file);
                            $isNew = ($newFile && $basename === $newFile) || ($index === 0 && isset($_GET['status']) && $_GET['status'] === 'success');
                            $timestamp = filemtime($file);
                        ?>
                        <tr class="transition <?= $isNew ? 'row-new' : 'hover:bg-gray-50/50'; ?>">
                            <td class="py-4 px-6 font-medium text-gray-800">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="file-text" class="w-4 h-4 text-[#199276] flex-shrink-0"></i>
                                    <span class="truncate max-w-[220px]"><?= $basename; ?></span>
                                    <?php if($isNew): ?>
                                    <span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-semibold flex-shrink-0">BARU</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-gray-500">
                                <!-- Ditampilkan secara relatif via JS -->
                                <span class="relative-time" data-timestamp="<?= $timestamp; ?>">
                                    <?= date("d M Y · H:i", $timestamp); ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-gray-500">
                                <?= round(filesize($file)/1024, 2); ?> KB
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex justify-center gap-2">
                                    <!-- Download -->
                                    <a href="../backup/<?= $basename; ?>" download
                                        title="Download"
                                        class="w-9 h-9 flex items-center justify-center bg-[#0B483A] text-white rounded-lg hover:bg-[#199276] transition">
                                        <i data-lucide="download" class="w-4 h-4"></i>
                                    </a>
                                    <!-- Hapus (popup custom) -->
                                    <button onclick="confirmDelete('<?= $basename; ?>')"
                                        title="Hapus"
                                        class="w-9 h-9 flex items-center justify-center bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- ===================== DELETE CONFIRM MODAL ===================== -->
    <div id="deleteModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 opacity-0 transition-opacity duration-300"
        onclick="closeDeleteModal()">

        <div id="deleteModalBox"
            class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 scale-90 opacity-0 transition-all duration-300 overflow-hidden"
            onclick="event.stopPropagation()">

            <!-- Top accent -->
            <div class="h-1.5 bg-gradient-to-r from-red-400 to-red-600 rounded-t-2xl"></div>

            <div class="p-6 text-center">
                <!-- Icon -->
                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="trash-2" class="w-8 h-8 text-red-500"></i>
                </div>

                <h3 class="text-lg font-bold text-gray-800 mb-1">Hapus Backup?</h3>
                <p class="text-sm text-gray-500 mb-1">File yang dihapus tidak dapat dipulihkan.</p>
                <p id="deleteFileName" class="text-xs font-mono bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg mt-3 mb-6 truncate"></p>

                <div class="flex gap-3">
                    <button onclick="closeDeleteModal()"
                        class="flex-1 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <a id="deleteConfirmBtn" href="#"
                        class="flex-1 py-2.5 bg-red-500 text-white rounded-xl text-sm font-bold hover:bg-red-600 transition text-center">
                        Ya, Hapus
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // ===== REALTIME RELATIVE TIME =====
        function timeAgo(timestamp) {
            const now = Math.floor(Date.now() / 1000);
            const diff = now - timestamp;

            if (diff < 60)        return 'Baru saja';
            if (diff < 3600)      return Math.floor(diff / 60) + ' menit yang lalu';
            if (diff < 86400)     return Math.floor(diff / 3600) + ' jam yang lalu';
            if (diff < 2592000)   return Math.floor(diff / 86400) + ' hari yang lalu';

            // Lebih dari 30 hari: tampilkan tanggal biasa
            const d = new Date(timestamp * 1000);
            return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
                + ' · ' + d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }

        function updateRelativeTimes() {
            document.querySelectorAll('.relative-time').forEach(el => {
                const ts = parseInt(el.getAttribute('data-timestamp'));
                el.textContent = timeAgo(ts);
            });
        }

        updateRelativeTimes();
        setInterval(updateRelativeTimes, 30000); // update tiap 30 detik

        // ===== DELETE MODAL =====
        function confirmDelete(filename) {
            document.getElementById('deleteFileName').textContent = filename;
            document.getElementById('deleteConfirmBtn').href = 'backup_delete.php?file=' + encodeURIComponent(filename);

            const modal = document.getElementById('deleteModal');
            const box   = document.getElementById('deleteModalBox');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                modal.classList.add('opacity-100');
                box.classList.remove('scale-90', 'opacity-0');
                box.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const box   = document.getElementById('deleteModalBox');

            modal.classList.remove('opacity-100');
            box.classList.remove('scale-100', 'opacity-100');
            box.classList.add('scale-90', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }
    </script>
</body>
</html>