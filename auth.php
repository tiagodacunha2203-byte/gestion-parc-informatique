<?php
session_start();
// Si la variable de session n'existe pas, on dégage l'utilisateur vers le login
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header("Location: login.php");
    exit;
}
?>
