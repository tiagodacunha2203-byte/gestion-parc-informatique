<?php
require_once('db.php');

// --- LOGIQUE DE RECHERCHE MULTICRITÈRE ---
$search = $_GET['s'] ?? '';
$cat_id = $_GET['c'] ?? '';

$query = "SELECT * FROM vue_materiel WHERE (nom LIKE :s OR details LIKE :s)";
if ($cat_id != '') { $query .= " AND id_type_raw = :cat"; } // Note: il faudra ajouter id_type dans ta VUE SQL

$stmt = $connexion->prepare($query);
$params = [':s' => "%$search%"];
if ($cat_id != '') { $params[':cat'] = $cat_id; }
$stmt->execute($params);
$liste = $stmt->fetchAll();

$categories = $connexion->query("SELECT * FROM CATEGORIE ORDER BY libelle ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Parc M2L</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between mb-4">
        <h1>Inventaire du Parc</h1>
        <a href="admin.php" class="btn btn-outline-dark">Administration</a>
    </div>

    <div class="card mb-4 p-3">
        <form method="GET" class="row g-2">
            <div class="col-md-6"><input name="s" class="form-control" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>"></div>
            <div class="col-md-4">
                <select name="c" class="form-select">
                    <option value="">Toutes les catégories</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['id_type'] ?>" <?= $cat_id == $cat['id_type'] ? 'selected' : '' ?>><?= $cat['libelle'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Filtrer</button></div>
        </form>
    </div>

    <table class="table bg-white shadow-sm rounded">
        <thead class="table-dark">
            <tr><th>Nom</th><th>Catégorie</th><th>Détails</th><th>Parent</th></tr>
        </thead>
        <tbody>
            <?php foreach($liste as $item): ?>
            <tr>
                <td><strong><?= htmlspecialchars($item['nom']) ?></strong></td>
                <td><span class="badge bg-info text-dark"><?= htmlspecialchars($item['type_libelle']) ?></span></td>
                <td><small><?= nl2br(htmlspecialchars($item['details'])) ?></small></td>
                <td><em><?= htmlspecialchars($item['parent_nom'] ?: '—') ?></em></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
</body>
</html>
