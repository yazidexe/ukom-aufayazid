<?php
session_start();
include "Admin/config/database.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
$user = mysqli_fetch_assoc($query);
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

    <form action="update_profile.php" method="POST">

        <input type="text" name="name" value="<?= $user['name']; ?>" 
            class="w-full border p-2 mb-3 rounded">

        <input type="email" name="email" value="<?= $user['email']; ?>" 
            class="w-full border p-2 mb-3 rounded">

        <button class="bg-[#199276] text-white px-4 py-2 rounded">
            Save
        </button>

    </form>

</div>

</body>
</html>