<?php
$backupCount = count(glob("../backup/*.sql") ?: []);
$backupFiles = glob("../backup/*.sql") ?: [];
usort($backupFiles, function($a, $b){ return filemtime($b) - filemtime($a); });
$lastBackup = !empty($backupFiles) ? date("d M Y · H:i", filemtime($backupFiles[0])) : null;
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
        @keyframes pulse-ring {
            0%   { transform: scale(1);   opacity: 0.6; }
            100% { transform: scale(1.6); opacity: 0; }
        }
        .pulse-ring::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 9999px;
            background: currentColor;
            animation: pulse-ring 1.5s ease-out infinite;
            opacity: 0;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- NAVBAR -->
    <div class="bg-[#0B483A] rounded-b-3xl px-8 py-5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-[#199276] rounded-xl flex items-center justify-center">
                <i data-lucide="layout-dashboard" class="w-5 h-5 text-white"></i>
            </div>
            <h1 class="text-white text-xl font-bold">Dasbor Admin</h1>
        </div>
        <a href="logout.php" class="flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white px-5 py-2 rounded-xl font-medium transition text-sm">
            <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
        </a>
    </div>

    <!-- MENU -->
    <div class="px-8 mt-4 flex justify-center">
        <ul class="flex gap-8 text-sm text-gray-400">
            <li><a href="dashboard.php" class="hover:text-[#0B483A] transition">beranda</a></li>
            <li><a href="users.php" class="hover:text-[#0B483A] transition">manajemen pengguna</a></li>
            <li><a href="products.php" class="hover:text-[#0B483A] transition">manajemen produk</a></li>
            <li><a href="reports.php" class="hover:text-[#0B483A] transition">laporan</a></li>
            <li><a href="transactions.php" class="hover:text-[#0B483A] transition">manajemen transaksi</a></li>
            <li><a href="backup.php" class="text-[#0B483A] font-semibold transition">backup data</a></li>
        </ul>
    </div>

    <!-- CONTENT -->
    <div class="max-w-3xl mx-auto px-6 mt-10 space-y-6">

        <!-- STATUS ALERTS -->
        <?php if(isset($_GET['restore'])): ?>
        <div class="px-5 py-4 rounded-2xl flex items-center gap-3 text-sm font-medium
            <?= $_GET['restore'] === 'failed' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-green-50 text-green-700 border border-green-200'; ?>">
            <i data-lucide="<?= $_GET['restore'] === 'failed' ? 'alert-circle' : 'check-circle'; ?>" class="w-5 h-5 flex-shrink-0"></i>
            <?php if($_GET['restore'] === 'failed'): ?>
                <?= $_GET['reason'] === 'format' ? 'Gagal: File harus berformat <strong>.sql</strong>' : 'Restore gagal. Pastikan file backup valid.'; ?>
            <?php else: ?>
                Restore database berhasil dilakukan!
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- STATS ROW -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-[#0B483A]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="archive" class="w-6 h-6 text-[#0B483A]"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-[#0B483A]"><?= $backupCount; ?></p>
                    <p class="text-xs text-gray-500 mt-0.5">Total File Backup</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="clock" class="w-6 h-6 text-blue-500"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-800"><?= $lastBackup ?? 'Belum ada'; ?></p>
                    <p class="text-xs text-gray-500 mt-0.5">Backup Terakhir</p>
                </div>
            </div>
        </div>

        <!-- BACKUP CARD -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 bg-[#0B483A] rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-[#0B483A]/20">
                        <i data-lucide="database" class="w-7 h-7 text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-lg font-bold text-gray-800">Backup Database</h2>
                        <p class="text-sm text-gray-500 mt-1">Buat salinan penuh database <span class="font-semibold text-[#0B483A]">azula_store</span>. File disimpan dalam format <code class="bg-gray-100 px-1.5 py-0.5 rounded text-xs">.sql</code>.</p>
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-4 border-t border-gray-100 pt-6">
                    <a href="backup_process.php" id="backupBtn"
                        class="flex items-center gap-3 bg-[#0B483A] text-white px-8 py-3.5 rounded-xl font-semibold hover:bg-[#199276] transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0">
                        <i data-lucide="cloud-upload" class="w-5 h-5"></i>
                        Backup Sekarang
                    </a>
                    <a href="backup_restore.php"
                        class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#0B483A] font-medium transition">
                        <i data-lucide="folder-open" class="w-4 h-4"></i>
                        Lihat Semua File (<?= $backupCount; ?>)
                    </a>
                </div>
            </div>
        </div>

        <!-- RESTORE CARD -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 bg-amber-500 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-amber-500/20">
                        <i data-lucide="rotate-ccw" class="w-7 h-7 text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-lg font-bold text-gray-800">Restore Database</h2>
                        <p class="text-sm text-gray-500 mt-1">Pulihkan database dari file backup <code class="bg-gray-100 px-1.5 py-0.5 rounded text-xs">.sql</code> yang telah diunduh sebelumnya.</p>
                        
                        <!-- Warning notice -->
                        <div class="mt-3 flex items-start gap-2 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-4 py-3 text-xs">
                            <i data-lucide="alert-triangle" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                            <p>Proses restore akan <strong>menimpa data yang ada</strong> saat ini. Pastikan Anda memilih file backup yang benar.</p>
                        </div>
                    </div>
                </div>

                <!-- FORM RESTORE -->
                <form action="backuprestore_process.php" method="POST" enctype="multipart/form-data" class="mt-6 border-t border-gray-100 pt-6">
                    
                    <!-- File Drop Zone -->
                    <label for="backup_file"
                        class="w-full flex flex-col items-center justify-center gap-2 border-2 border-dashed border-gray-200 hover:border-[#199276] bg-gray-50 hover:bg-[#199276]/5 rounded-xl py-8 cursor-pointer transition-all group">
                        <div class="w-12 h-12 bg-white rounded-full shadow-sm border border-gray-100 flex items-center justify-center group-hover:border-[#199276]/30 transition">
                            <i data-lucide="upload" class="w-5 h-5 text-gray-400 group-hover:text-[#0B483A] transition"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-600 group-hover:text-[#0B483A] transition">Pilih File Backup</p>
                        <p class="text-xs text-gray-400">Format .sql · Klik untuk browse</p>
                        <input type="file" id="backup_file" name="backup_file" accept=".sql" class="hidden" required onchange="showFileName(this)">
                    </label>

                    <!-- File Name Preview -->
                    <div id="filePreview" class="hidden mt-3 flex items-center gap-3 bg-[#0B483A]/5 border border-[#0B483A]/10 px-4 py-3 rounded-xl">
                        <i data-lucide="file-check" class="w-5 h-5 text-[#0B483A]"></i>
                        <p id="selectedFileName" class="text-sm font-medium text-[#0B483A] truncate"></p>
                    </div>

                    <button type="submit"
                        class="mt-4 w-full flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white py-3.5 rounded-xl font-semibold transition-all hover:-translate-y-0.5 active:translate-y-0 shadow-md hover:shadow-lg">
                        <i data-lucide="rotate-ccw" class="w-5 h-5"></i>
                        Restore Sekarang
                    </button>
                </form>
            </div>
        </div>

        <div class="pb-10"></div>
    </div>

    <script>
        lucide.createIcons();

        function showFileName(input){
            const preview = document.getElementById('filePreview');
            const name    = document.getElementById('selectedFileName');
            if(input.files.length > 0){
                name.textContent = input.files[0].name;
                preview.classList.remove('hidden');
                preview.classList.add('flex');
            }
        }

        // Loading state for backup button
        document.getElementById('backupBtn').addEventListener('click', function(){
            this.innerHTML = '<svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg> Sedang memproses...';
            this.classList.add('opacity-70', 'pointer-events-none');
        });
    </script>

</body>
</html>