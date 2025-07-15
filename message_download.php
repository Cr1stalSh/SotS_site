<?php
session_start();
$_SESSION['error_message'] = "Вы должны войти в систему, чтобы скачать игру";
header("Location: reg.php");
exit();
?>