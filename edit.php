<?php require_once('auth.php'); ?>
<?php
require_once('db.php');

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: admin.php"); exit; }

// --- LOGIQUE DE MISE À JOUR ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "UPDATE MATERIEL SET nom = :nom, annee = :annee, details = :details, 
            id_type = :id_type, id_parent = :id_parent WHERE id = :id";
    
    $upd = $connexion->prepare($sql);
    $upd->execute([
        ':nom'       => $_POST['nom'],
        ':annee'     => $_POST['annee'],
        ':details'   => $_POST['details'],
        ':id_type'   => $_POST['id_type'],
        ':id_parent' => !empty($_POST['id_parent']) ? $_POST['id_parent'] : null,
        ':id'        => $id
    ]);
    header("Location: admin.php?msg=Modifié avec succès");
    exit;
}

// --- RÉCUPÉRATION DES INFOS ACTUELLES ---
$stmt = $connexion->prepare("SELECT * FROM MATERIEL WHERE id = ?");
$stmt->execute([$id]);
$materièl = $stmt->fetch();

$categories = $connexion->query("SELECT * FROM CATEGORIE ORDER BY libelle ASC")->fetchAll();
$parents = $connexion->query("SELECT id, nom FROM MATERIEL WHERE id != $id ORDER BY nom ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'équipement</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light p-5">
    <div class="container" style="max-width: 600px;">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark"><strong>Modifier : <?= htmlspecialchars($materièl['nom']) ?></strong></div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3"><label>Nom</label><input name="nom" class="form-control" value="<?= htmlspecialchars($materièl['nom']) ?>" required></div>
                    <div class="mb-3"><label>Année</label><input name="annee" type="number" class="form-control" value="<?= $materièl['annee'] ?>"></div>
                    <div class="mb-3">
                        <label>Catégorie</label>
                        <select name="id_type" class="form-select">
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat['id_type'] ?>" <?= $cat['id_type'] == $materièl['id_type'] ? 'selected' : '' ?>><?= $cat['libelle'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Parent (Rattaché à)</label>
                        <select name="id_parent" class="form-select">
                            <option value="">Aucun</option>
                            <?php foreach($parents as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= $p['id'] == $materièl['id_parent'] ? 'selected' : '' ?>><?= $p['nom'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3"><label>Détails</label><textarea name="details" class="form-control"><?= htmlspecialchars($materièl['details']) ?></textarea></div>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    <a href="admin.php" class="btn btn-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
