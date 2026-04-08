<?php
include '../Admin/config/database.php';
$data = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dasbor Admin</title>

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
            <li><a href="users.php" class="hover:text-[#0B483A] transition">manajemen pengguna</a></li>
            <li><a href="products.php" class="text-[#0B483A] font-semibold transition">manajemen produk</a></li>
            <li><a href="reports.php" class="hover:text-[#0B483A] transition">laporan</a></li>
            <li><a href="transactions.php" class="hover:text-[#0B483A] transition">manajemen transaksi</a></li>
            <li><a href="backup.php" class="hover:text-[#0B483A] transition">backup data</a></li>
        </ul>
    </div>

    <!-- CONTENT  -->
    <div class="px-8 mt-8">
        <div class="flex justify-end mb-4">
            <button
                onclick="openModal()"
                class="flex items-center gap-2 bg-[#0B483A] text-white px-12 py-2 rounded-md hover:opacity-80 transition">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                add product
            </button>
        </div>

        <!-- TABLE PRODUCT -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#0B483A] text-white">
                    <tr>
                        <th class="p-3">Id</th>
                        <th class="p-3">Picture</th>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Stok</th>
                        <th class="p-3">Harga</th>
                        <th class="p-3">Aksi</th>
                        <th class="p-3">Tanggal</th>
                    </tr>
                </thead>

                <tbody>
                <?php while($row = mysqli_fetch_assoc($data)) : ?>
                    <tr class="text-left border-t">
                        <td class="p-3"><?= $row['id'] ?></td>

                        <td class="p-3">
                            <?php if($row['picture']) : ?>
                                <img src="uploads/products/<?= $row['picture'] ?>"
                                    class="w-14 h-14 object-cover rounded mx-auto">
                            <?php else : ?>
                                <span class="text-gray-400">No Image</span>
                            <?php endif; ?>
                        </td>

                        <td class="p-3"><?= $row['name'] ?></td>
                        <td class="p-3"><?= $row['category'] ?></td>
                        <td class="p-3"><?= $row['stock'] ?></td>
                        <td class="p-3 font-semibold text-[#0B483A]">
                            Rp<?= number_format($row['price'],0,',','.') ?>
                        </td> 

                        <td class="p-3">
                            <div class="flex justify-center gap-2">
                                <button
                                    onclick="openEditModal(
                                        '<?= $row['id'] ?>',
                                        '<?= $row['name'] ?>',
                                        '<?= $row['category'] ?>',
                                        '<?= $row['stock'] ?>',
                                        '<?= $row['price'] ?>',
                                        '<?= $row['picture'] ?>'
                                    )"
                                    class="bg-emerald-600 text-white px-3 py-1 rounded flex items-center gap-1">
                                    <i data-lucide="pencil" class="w-4 h-4"></i> Edit
                                </button> 

                                <button
                                    onclick="openDeleteModal(
                                        '<?= $row['id'] ?>',
                                        '<?= $row['name'] ?>'
                                    )"
                                    class="bg-red-500 text-white px-3 py-1 rounded flex items-center gap-1">
                                    <i data-lucide="trash" class="w-4 h-4"></i> Hapus
                                </button>

                            </div>
                        </td>

                        <td class="p-3"><?= $row['created_at'] ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- MODAL ADD PRODUCT -->
        <div id="modalAddProduct"
            class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

            <div class="bg-[#EDEDED] w-full max-w-xl rounded-3xl overflow-hidden">
                <!-- HEADER -->
                <div class="bg-[#0B483A] rounded-b-3xl py-6 px-6 flex items-center justify-center">
                    
                    <h2 class="text-white text-xl font-semibold">
                        Tambah Produk
                    </h2>

                   

                </div>

                <!-- FORM -->
                <form action="products_add.php"
                    method="POST"
                    enctype="multipart/form-data"
                    class="px-10 py-8 space-y-4">

                    <!-- Name -->
                    <input type="text" name="name" required
                        placeholder="name of product"
                        class="w-full bg-[#D9D9D9] px-4 py-3 rounded-md
                            outline-none text-sm">
                    
                    <!-- Category -->
                    <select name="category" required
                        class="w-full bg-[#D9D9D9] px-4 py-3 rounded-md outline-none text-sm">

                        <option value="" disabled selected>Select category</option>

                        <option value="Shirt">Shirt</option>
                        <option value="T-Shirt">T-Shirt</option>
                        <option value="Pants">Pants</option>
                        <option value="Shoes">Shoes</option>

                    </select>


                    
                    <!-- Price -->
                    <input type="number" name="price" required
                        placeholder="price"
                        class="w-full bg-[#D9D9D9] px-4 py-3 rounded-md
                            outline-none text-sm">

                    <!-- Stock + File -->
                    <div class="flex gap-3">

                        <!-- Stock Counter -->
                        <div class="flex items-center bg-[#D9D9D9] rounded-md overflow-hidden w-40">
                            <button type="button"
                                onclick="decreaseStock()"
                                class="px-4 py-3 hover:bg-gray-300">−</button>

                            <input type="number" name="stock" id="stockInput"
                                value="0"
                                class="w-full text-center bg-transparent outline-none">

                            <button type="button"
                                onclick="increaseStock()"
                                class="px-4 py-3 hover:bg-gray-300">+</button>
                        </div>

                        <!-- IMAGE UPLOAD -->
                        <div class="flex-1 flex flex-col gap-3">

                            <!-- Drop Area -->
                            <div id="dropArea"
                                class="border-2 border-dashed border-gray-300 rounded-xl
                                        flex flex-col items-center justify-center
                                        text-sm text-gray-500 cursor-pointer
                                        py-8 transition hover:border-[#199276] hover:bg-gray-50">

                                <i data-lucide="image" class="w-8 h-8 mb-2"></i>
                                <p>Drag & Drop image here</p>
                                <span class="text-xs text-gray-400 mt-1">or click to choose file</span>

                                <input type="file"
                                    name="picture"
                                    id="imageInput"
                                    accept="image/*"
                                    class="hidden">
                            </div>

                            <!-- Preview -->
                            <div id="previewContainer" class="hidden relative">
                                <img id="imagePreview"
                                    class="w-full h-48 object-cover rounded-xl border">

                                <!-- Remove Button -->
                                <button type="button"
                                        onclick="removeImage()"
                                        class="absolute top-2 right-2 bg-black/60 text-white
                                            p-1 rounded-full hover:bg-red-500 transition">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>

                            

                        </div>                           
                    </div>

                    <!-- Description -->
                    <textarea name="description"
                        placeholder="Desc"
                        rows="4"
                        class="w-full bg-[#D9D9D9] px-4 py-3 rounded-md
                            outline-none text-sm resize-none"></textarea>

                    <div class="flex gap-4 pt-4">
                        <!-- Cancel -->
                        <button type="button"
                            onclick="closeModal()"
                            class="w-1/2 border border-[#0B483A] py-4 rounded-lg
                                text-[#0B483A] font-semibold
                                hover:bg-gray-300 hover:text-[#0B483A] transition">
                            Batal
                        </button>

                        <!-- Add -->
                        <button type="submit" name="save"
                            class="w-1/2 bg-[#199276] py-4 rounded-lg
                                text-[#0B483A] font-semibold
                                hover:brightness-110 hover:scale-[1.02]
                                transition duration-200">
                            Add
                        </button>
                    </div>


                </form>
            </div>
        </div>

        <!-- MODAL EDIT -->
        <div id="modalEditProduct"
            class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

            <div class="bg-white w-full max-w-lg rounded-xl p-6 relative">
                <!-- Header -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-[#0B483A]">
                        Edit Product
                    </h2>
                </div>

                <form action="products_edit.php" method="POST" enctype="multipart/form-data" class="space-y-4">

                    <input type="hidden" name="id" id="edit_id">
                    <input type="hidden" name="old_picture" id="old_picture">

                    <div>
                        <label class="text-sm text-gray-600">Product Name</label>
                        <input type="text" name="name" id="edit_name" required
                            class="w-full border rounded-md px-4 py-2">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Stok</label>
                        <input type="number" name="stock" id="edit_stock" required
                            class="w-full border rounded-md px-4 py-2">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Harga</label>
                        <input type="number" name="price" id="edit_price" required
                            class="w-full border rounded-md px-4 py-2">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Picture (optional)</label>
                        <input type="file" name="picture" accept="image/*"
                            class="w-full border rounded-md px-4 py-2">
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <button type="button" onclick="closeEditModal()"
                                class="px-5 py-2 rounded-md border">
                            Batal
                        </button>
                        <button type="submit" name="update"
                                class="px-5 py-2 rounded-md bg-[#199276] text-white">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL DELETE -->
        <div id="modalDeleteProduct"
            class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

            <div class="bg-white w-full max-w-md rounded-xl p-6 text-center">
                <h2 class="text-xl font-semibold text-red-600 mb-2">
                    Delete Product
                </h2>

                <p class="text-gray-600 mb-6">
                    Are you sure you want to delete
                    <span id="deleteProductName" class="font-semibold"></span>?
                </p>

                <form action="products_delete.php" method="POST" class="flex justify-center gap-3">
                    <input type="hidden" name="id" id="delete_id">

                    <button type="button"
                            onclick="closeDeleteModal()"
                            class="px-5 py-2 border rounded-md">
                        Batal
                    </button>

                    <button type="submit" name="delete"
                            class="px-5 py-2 bg-red-500 text-white rounded-md">
                        Yes, Delete
                    </button>
                </form>
            </div>
        </div>


    </div>

<script>
    lucide.createIcons()

    // ADD MODAL PRODUCT
    function openModal() {
        document.getElementById('modalAddProduct').classList.remove('hidden');
        document.getElementById('modalAddProduct').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('modalAddProduct').classList.add('hidden');
        document.getElementById('modalAddProduct').classList.remove('flex');
    }

    function increaseStock() {
    let input = document.getElementById("stockInput");
    input.value = parseInt(input.value) + 1;
    }

    function decreaseStock() {
        let input = document.getElementById("stockInput");
        if (input.value > 0) {
            input.value = parseInt(input.value) - 1;
        }
    }

    // EDIT MODAL PRODUCT
    function openEditModal(id, name, stock, price, picture) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_stock').value = stock;
        document.getElementById('edit_price').value = price;
        document.getElementById('old_picture').value = picture;

        document.getElementById('modalEditProduct').classList.remove('hidden');
        document.getElementById('modalEditProduct').classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('modalEditProduct').classList.add('hidden');
        document.getElementById('modalEditProduct').classList.remove('flex');
    }
    
    // DELETE MODAL PRODUCT
    function openDeleteModal(id, name){
        document.getElementById('delete_id').value = id;
        document.getElementById('deleteProductName').innerText = name;

        const modal = document.getElementById('modalDeleteProduct');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal(){
        const modal = document.getElementById('modalDeleteProduct');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

</script>

<!-- SCRIPT ADD PRODUCT -->
<script>
const dropArea = document.getElementById("dropArea");
const input = document.getElementById("imageInput");
const preview = document.getElementById("imagePreview");
const previewContainer = document.getElementById("previewContainer");

// Click open file
dropArea.addEventListener("click", () => input.click());

// Handle file select
input.addEventListener("change", handleFile);

// Drag events
dropArea.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropArea.classList.add("border-[#199276]", "bg-gray-50");
});

dropArea.addEventListener("dragleave", () => {
    dropArea.classList.remove("border-[#199276]", "bg-gray-50");
});

dropArea.addEventListener("drop", (e) => {
    e.preventDefault();
    dropArea.classList.remove("border-[#199276]", "bg-gray-50");

    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith("image/")) {
        input.files = e.dataTransfer.files;
        showPreview(file);
    }
});

function handleFile(e) {
    const file = e.target.files[0];
    if (file) {
        showPreview(file);
    }
}

function showPreview(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        preview.src = e.target.result;
        previewContainer.classList.remove("hidden");
        dropArea.classList.add("hidden");
    }
    reader.readAsDataURL(file);
}

function removeImage() {
    input.value = "";
    preview.src = "";
    previewContainer.classList.add("hidden");
    dropArea.classList.remove("hidden");
}
</script>

</body>
</html>