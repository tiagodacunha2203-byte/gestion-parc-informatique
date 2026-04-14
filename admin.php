<?php require_once('auth.php'); ?>
<?php
require_once('db.php'); // On récupère la connexion PDO

// --- 1. LOGIQUE D'INSERTION (AJOUT) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter') {
    if (!empty($_POST['nom']) && !empty($_POST['id_type'])) {
        $sql = "INSERT INTO MATERIEL (nom, annee, details, id_type, id_parent) 
                VALUES (:nom, :annee, :details, :id_type, :id_parent)";
        
        $ins = $connexion->prepare($sql);
        
        $ins->execute([
            ':nom'      => $_POST['nom'],
            ':annee'     => $_POST['annee'],
            ':details'   => $_POST['details'],
            ':id_type'   => $_POST['id_type'],
            ':id_parent' => !empty($_POST['id_parent']) ? $_POST['id_parent'] : null
        ]);

        // Recharge la page pour vider le formulaire et voir le nouvel élément
        header("Location: admin.php?success=1");
        exit;
    }
}

// --- 2. RÉCUPÉRATION DES DONNÉES ---
// On utilise la vue pour l'affichage et les tables pour les formulaires
$liste = $connexion->query("SELECT * FROM vue_materiel ORDER BY id DESC")->fetchAll();
$categories = $connexion->query("SELECT * FROM CATEGORIE ORDER BY libelle ASC")->fetchAll();
$parents = $connexion->query("SELECT id, nom FROM MATERIEL ORDER BY nom ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration du Parc</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand">🔧 Interface d'Administration</span>
        <a href="index.php" class="btn btn-outline-light btn-sm">Voir le site public</a>
    </div>
</nav>

<div class="container">
    
    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">Matériel ajouté avec succès !</div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">Ajouter un équipement</div>
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="action" value="ajouter">
                        
                        <div class="mb-2">
                            <label class="form-label">Nom du matériel</label>
                            <input name="nom" class="form-control" placeholder="ex: Dell Optiplex" required>
                        </div>
                        
                        <div class="mb-2">
                            <label class="form-label">Année</label>
                            <input name="annee" type="number" class="form-control" value="<?= date('Y') ?>">
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Catégorie</label>
                            <select name="id_type" class="form-select" required>
                                <option value="">-- Choisir --</option>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat['id_type'] ?>"><?= htmlspecialchars($cat['libelle']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Rattaché à (Parent)</label>
                            <select name="id_parent" class="form-select">
                                <option value="">Aucun (Équipement principal)</option>
                                <?php foreach($parents as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Détails techniques</label>
                            <textarea name="details" class="form-control" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Inventaire actuel</div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Désignation</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($liste as $item): ?>
                            <tr>
                                <td><span class="badge bg-secondary">#<?= $item['id'] ?></span></td>
                                <td>
                                    <strong><?= htmlspecialchars($item['nom']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($item['parent_nom'] ? 'Dans : '.$item['parent_nom'] : 'Autonome') ?></small>
                                </td>
                                <td><?= htmlspecialchars($item['type_libelle']) ?></td>
                                <td>
                                    <a href="edit.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                                    <a href="delete.php?id=<?= $item['id'] ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Supprimer cet équipement ?')">Suppr.</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

</body>
</html>
