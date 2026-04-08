<?php
session_start();
include "Admin/config/database.php";

if(!isset($_SESSION['user_id'])){
    header("Location: profile.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if(isset($_FILES['avatar'])){

    $file = $_FILES['avatar'];

    if($file['name']){

        $allowed = ['jpg','jpeg','png'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if(!in_array($ext, $allowed)){
            die("Format harus JPG/PNG");
        }

        $fileName = time().'_'.$file['name'];
        $uploadDir = "uploads/avatars/";

        if(!is_dir($uploadDir)){
            mkdir($uploadDir, 0777, true);
        }

        move_uploaded_file($file['tmp_name'], $uploadDir.$fileName);

        // 🔥 UPDATE DB
        mysqli_query($conn, "
            UPDATE users SET avatar='$uploadDir$fileName' 
            WHERE id='$user_id'
        ");

        // 🔥 UPDATE SESSION BIAR LANGSUNG KEPAKE DI NAVBAR
        $_SESSION['avatar'] = $uploadDir.$fileName;
    }
}

header("Location: profile.php");
exit;