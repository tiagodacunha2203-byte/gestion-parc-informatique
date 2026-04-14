<?php
require_once('db.php');

if (isset($_GET['id'])) {
    try {
        $stmt = $connexion->prepare("DELETE FROM MATERIEL WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        header("Location: admin.php?msg=Supprimé");
    } catch (Exception $e) {
        // Souvent une erreur si l'équipement est "parent" d'un autre (contrainte SQL)
        die("Erreur : Impossible de supprimer cet équipement car il possède des composants rattachés.");
    }
}
exit;
