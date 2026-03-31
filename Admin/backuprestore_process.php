<?php
include '../config/database.php';

if(isset($_FILES['backup_file'])){
    $fileTmp = $_FILES['backup_file']['tmp_name'];

    $command = "C:/xampp/mysql/bin/mysql --user=root --password= ukom_project < $fileTmp";

    system($command);

    header("Location: backup_restore.php");
    exit;
}
?>
