<?php
session_start();
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['avatar']);
unset($_SESSION['cart']);

header("Location: index.php");
exit;