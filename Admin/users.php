<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit;
}

require 'config/database.php';

// ambil data officer
$result = mysqli_query($conn, "SELECT * FROM officers ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
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
        <li><a href="users.php" class="text-[#0B483A] font-semibold transition">manajemen pengguna</a></li>
        <li><a href="products.php" class="hover:text-[#0B483A] transition">manajemen produk</a></li>
        <li><a href="reports.php" class="hover:text-[#0B483A] transition">laporan</a></li>
        <li><a href="transactions.php" class="hover:text-[#0B483A] transition">manajemen transaksi</a></li>
        <li><a href="backup.php" class="hover:text-[#0B483A] transition">backup data</a></li>
    </ul>
</div>

<!-- CONTENT -->
<div class="px-8 mt-8">

    <!-- Header Table -->
    <div class="flex justify-end mb-4">
        <button
            onclick="openModal()"
            class="flex items-center gap-2 bg-[#0B483A] text-white px-12 py-2 rounded-lg hover:opacity-90 transition">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            add officer
        </button>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-[#0B483A] text-white">
                <tr>
                    <th class="px-4 py-3 text-left">Id</th>
                    <th class="px-4 py-3 text-left">Username</th>
                    <th class="px-4 py-3 text-left">E-mail</th>
                    <th class="px-4 py-3 text-left">Password</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="border-b">
                            <td class="px-4 py-3"><?= $row['id']; ?></td>
                            <td class="px-4 py-3"><?= $row['username']; ?></td>
                            <td class="px-4 py-3"><?= $row['email'] ?? '-'; ?></td>
                            <td class="px-4 py-3"><?= $row['password']; ?></td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center gap-2">
                                    <button
                                        onclick="openEditModal(
                                            '<?= $row['id']; ?>',
                                            '<?= $row['username']; ?>',
                                            '<?= $row['email']; ?>'
                                        )"
                                        class="bg-emerald-600 text-white px-3 py-1 rounded flex items-center gap-1 hover:opacity-90">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                        Edit
                                    </button>

                                    <button
                                        onclick="openDeleteModal('<?= $row['id']; ?>', '<?= $row['username']; ?>')"
                                        class="bg-red-500 text-white px-3 py-1 rounded flex items-center gap-1 hover:opacity-90">
                                        <i data-lucide="trash" class="w-4 h-4"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <?= date('d/m/Y', strtotime($row['created_at'])); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-400">
                            No officer data
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- MODAL ADD OFFICER -->
    <div id="addOfficerModal"
        class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden">

            <!-- HEADER MODAL -->
            <div class="bg-[#0B483A] rounded-b-3xl px-6 py-5 flex items-center justify-between">
                <h2 class="text-white text-xl font-semibold">
                    Add Officer
                </h2>              
            </div>

            <!-- BODY -->
            <div class="p-6">
                <form action="user_store.php" method="POST" class="space-y-4">

                    <div>
                        <label class="text-sm text-gray-600">Username</label>
                        <input type="text" name="username" required
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#199276]">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">E-mail</label>
                        <input type="email" name="email" required
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#199276]">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#199276]">
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 rounded-lg border hover:bg-gray-100">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2 rounded-lg bg-[#0B483A] text-white hover:opacity-90">
                            Add
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>


    <!-- MODAL EDIT OFFICER -->
    <div id="editOfficerModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
        
        <div class="bg-white rounded-xl w-full max-w-md overflow-hidden shadow-2xl">

            <!-- HEADER MODAL -->
            <div class="bg-[#0B483A] rounded-b-3xl px-6 py-5 flex items-center justify-between relative">
                <h2 class="text-white text-xl font-semibold">
                    Edit Officer
                </h2>
            </div>

            <div class="p-6">
                <form action="user_update.php" method="POST" class="space-y-4">

                    <input type="hidden" name="id" id="edit_id">

                    <div>
                        <label class="text-sm text-gray-600">Username</label>
                        <input type="text" name="username" id="edit_username" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#199276] transition-all">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">E-mail</label>
                        <input type="email" name="email" id="edit_email" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#199276] transition-all">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600 block mb-1">
                            Password <span class="text-xs text-[#199276] font-medium">(biarkan kosong jika tidak ingin diubah)</span>
                        </label>
                        <input type="password" name="password" placeholder="Masukkan password baru..."
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#199276] transition-all">
                    </div>

                    <div class="flex justify-end gap-3 pt-5 border-t border-gray-100 mt-6">
                        <button type="button" onclick="closeEditModal()"
                            class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:bg-gray-50 hover:text-gray-800 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-[#0B483A] text-white font-medium shadow-md shadow-[#0B483A]/20 hover:-translate-y-0.5 hover:shadow-lg hover:bg-[#083b30] transition-all">
                            Update Officer
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div id="deleteOfficerModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl w-full max-w-md p-6 text-center">

            <h2 class="text-xl font-semibold text-red-500 mb-2">
                Delete Officer
            </h2>

            <p class="text-gray-600 mb-6">
                Yakin mau hapus officer
                <span id="delete_username" class="font-semibold"></span>?
                <br>Data tidak bisa dikembalikan.
            </p>

            <form action="user_delete.php" method="POST" class="flex justify-center gap-4">
                <input type="hidden" name="id" id="delete_id">

                <button type="button" onclick="closeDeleteModal()"
                    class="px-4 py-2 rounded-lg border">
                    Batal
                </button>

                <button type="submit"
                    class="px-5 py-2 rounded-lg bg-red-500 text-white hover:opacity-90">
                    Hapus
                </button>
            </form>
        </div>
    </div>

</div>

<script>
    lucide.createIcons();

    // MODAL ADD OFFICER 
    function openModal() {
        document.getElementById('addOfficerModal').classList.remove('hidden');
        document.getElementById('addOfficerModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('addOfficerModal').classList.add('hidden');
    }
    // MODAL EDIT OFFICER
    function openEditModal(id, username, email) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_username').value = username;
        document.getElementById('edit_email').value = email;

        document.getElementById('editOfficerModal').classList.remove('hidden');
        document.getElementById('editOfficerModal').classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('editOfficerModal').classList.add('hidden');
    }
    // MODAL DELETE 
    function openDeleteModal(id, username) {
        document.getElementById('delete_id').value = id;
        document.getElementById('delete_username').innerText = username;

        document.getElementById('deleteOfficerModal').classList.remove('hidden');
        document.getElementById('deleteOfficerModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteOfficerModal').classList.add('hidden');
    }

</script>


</body>
</html>
