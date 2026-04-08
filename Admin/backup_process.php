<?php
include '../Admin/config/database.php';

$backupFolder = "../backup/";

// Buat folder jika belum ada
if(!is_dir($backupFolder)){
    mkdir($backupFolder, 0777, true);
}

$filename = "backup_" . date("Y-m-d_H-i-s") . ".sql";
$filepath = $backupFolder . $filename;

$command = "C:/xampp/mysql/bin/mysqldump --user=root --password= --databases azula_store > \"$filepath\"";

system($command, $output);

if(file_exists($filepath) && filesize($filepath) > 0){
    header("Location: backup_restore.php?status=success");
} else {
    header("Location: backup_restore.php?status=failed");
}
exit;
?>
