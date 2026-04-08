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

        $avatarPath = $uploadDir . $fileName;

        if(move_uploaded_file($file['tmp_name'], $avatarPath)){

            // 🔥 update DB
            $update = mysqli_query($conn, "
                UPDATE users SET avatar='$avatarPath' 
                WHERE id='$user_id'
            ");

            if(!$update){
                die("Gagal update DB: " . mysqli_error($conn));
            }

            // 🔥 update session
            $_SESSION['avatar'] = $avatarPath;

        } else {
            die("Upload file gagal");
        }
    }
}

header("Location: profile.php");
exit;