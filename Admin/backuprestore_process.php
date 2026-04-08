<?php
include '../Admin/config/database.php';

if(isset($_FILES['backup_file']) && $_FILES['backup_file']['error'] == 0){
    $fileTmp = $_FILES['backup_file']['tmp_name'];
    $ext = strtolower(pathinfo($_FILES['backup_file']['name'], PATHINFO_EXTENSION));

    if($ext !== 'sql'){
        header("Location: backup.php?restore=failed&reason=format");
        exit;
    }

    $command = "C:/xampp/mysql/bin/mysql --user=root --password= azula_store < \"$fileTmp\"";
    system($command, $retval);

    if($retval === 0){
        header("Location: backup_restore.php?status=restored");
    } else {
        header("Location: backup.php?restore=failed&reason=error");
    }
    exit;
}

header("Location: backup.php");
exit;
?>
