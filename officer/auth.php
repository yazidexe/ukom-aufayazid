<?php
session_start();

if(!isset($_SESSION['officer_id'])){
    header("Location: ../Admin/login.php"); 
    exit();
}
?>