<?php
require_once('credentials.php');

try {
    $connexion = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $password);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
