<?php
include '../config/database.php';

$backupFolder = "../backup/";
$filename = "backup_" . date("Y-m-d_H-i-s") . ".sql";
$filepath = $backupFolder . $filename;

$command = "C:/xampp/mysql/bin/mysqldump --user=root --password= --databases ukom_project > $filepath";

system($command, $output);

header("Location: backup_restore.php?status=success");
exit;

?>
