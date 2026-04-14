<?php 
require_once('auth.php'); // Protection de la page
require_once('db.php');   // Connexion à la base de données

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

        // Recharge la page pour voir le nouvel élément
        header("Location: admin.php?success=1");
        exit;
    }
}

// --- 2. RÉCUPÉRATION DES DONNÉES ---
$liste = $connexion->query("SELECT * FROM vue_materiel ORDER BY id DESC")->fetchAll();
$categories = $connexion->query("SELECT * FROM CATEGORIE ORDER BY libelle ASC")->fetchAll();
$parents = $connexion->query("SELECT id, nom FROM MATERIEL ORDER BY nom ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration du Parc - M2L</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .card { border: none; border-radius: 10px; }
        .table-container { background: white; border-radius: 10px; overflow: hidden; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark mb-4 shadow-sm">
    <div class="container">
        <span class="navbar-brand">🔧 Gestion du Parc - Administration</span>
        <a href="index.php" class="btn btn-outline-light btn-sm">Quitter l'admin</a>
    </div>
</nav>

<div class="container">
    
    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            Matériel ajouté avec succès !
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white font-weight-bold">Ajouter un équipement</div>
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="action" value="ajouter">
                        
                        <div class="mb-2">
                            <label class="form-label">Nom du matériel</label>
                            <input name="nom" class="form-control" placeholder="ex: Dell Latitude 5420" required>
                        </div>
                        
                        <div class="mb-2">
                            <label class="form-label">Année</label>
                            <input name="annee" type="number" class="form-control" value="<?= date('Y') ?>">
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Catégorie</label>
                            <select name="id_type" class="form-select" required>
                                <option value="">-- Sélectionner le Type --</option>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat['id_type'] ?>"><?= htmlspecialchars($cat['libelle']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Rattaché à (Parent)</label>
                            <select name="id_parent" class="form-select">
                                <option value="">-- Appartient à (Aucun) --</option>
                                <?php foreach($parents as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Détails techniques</label>
                            <textarea name="details" class="form-control" rows="3" placeholder="Configuration, RAM, CPU..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100 shadow-sm">Enregistrer l'équipement</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm table-container">
                <div class="card-header bg-dark text-white">Inventaire actuel</div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">ID</th>
                                <th>Désignation</th>
                                <th>Type</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($liste as $item): ?>
                            <tr>
                                <td><span class="text-muted small">#<?= $item['id'] ?></span></td>
                                <td>
                                    <strong><?= htmlspecialchars($item['nom']) ?></strong><br>
                                    <?php if($item['parent_nom']): ?>
                                        <small class="badge bg-light text-dark fw-normal border">Dans : <?= htmlspecialchars($item['parent_nom']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge bg-info text-dark"><?= htmlspecialchars($item['type_libelle']) ?></span></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="edit.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                                        <a href="delete.php?id=<?= $item['id'] ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Voulez-vous vraiment supprimer cet équipement ?')">Suppr.</a>
                                    </div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
