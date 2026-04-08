<?php
session_start();
include "Admin/config/database.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Profile</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-md mx-auto mt-20 bg-white p-6 rounded-xl shadow">

    <h2 class="text-xl font-semibold mb-4">Edit Profile</h2>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="bg-red-100 text-red-700 p-2 mb-3 rounded">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="update_profile.php" method="POST">

        <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>" 
            class="w-full border p-2 mb-3 rounded">

        <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" 
            class="w-full border p-2 mb-3 rounded">

        <!-- TAMBAHAN ALAMAT -->
        <label class="text-sm text-gray-600">Alamat:</label>
        <textarea name="address" class="w-full border p-2 mb-3 rounded" rows="3"><?= htmlspecialchars($user['address']); ?></textarea>

        <button class="bg-[#199276] text-white px-4 py-2 rounded">
            Save
        </button>

    </form>

</div>

</body>
</html>