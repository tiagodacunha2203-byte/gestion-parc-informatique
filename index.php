<?php
require_once('db.php');

$search = $_GET['search'] ?? '';
$type_filter = $_GET['id_type'] ?? '';

$sql = "SELECT * FROM vue_materiel WHERE (nom LIKE :s OR details LIKE :s)";
if ($type_filter) { $sql .= " AND id_type_raw = :t"; } // Note : assure-toi que id_type est dans ta vue

$stmt = $connexion->prepare($sql);
$stmt->bindValue(':s', "%$search%");
if ($type_filter) { $stmt->bindValue(':t', $type_filter); }
$stmt->execute();
$liste = $stmt->fetchAll();

$categories = $connexion->query("SELECT * FROM CATEGORIE ORDER BY libelle ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inventaire - M2L</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Mon Parc Informatique</h1>
        <a href="login.php" class="btn btn-outline-secondary">Espace Admin</a>
    </div>

    <form class="row g-3 mb-4">
        <div class="col-md-6">
            <input type="text" name="search" class="form-control" placeholder="Rechercher un matériel..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-4">
            <select name="id_type" class="form-select">
                <option value="">Tous les types</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['id_type'] ?>" <?= $type_filter == $cat['id_type'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['libelle']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filtrer</button>
        </div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr><th>Nom</th><th>Type</th><th>Année</th><th>Parent</th></tr>
        </thead>
        <tbody>
            <?php foreach ($liste as $item): ?>
            <tr>
                <td><strong><?= htmlspecialchars($item['nom']) ?></strong></td>
                <td><?= htmlspecialchars($item['type_libelle']) ?></td>
                <td><?= htmlspecialchars($item['annee']) ?></td>
                <td><?= htmlspecialchars($item['parent_nom'] ?: '—') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

</body>
</html>
