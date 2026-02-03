<?php
require('credentials.php');

try {
    $connexion = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $password);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// --- LOGIQUE D'INSERTION ---
if (!empty($_POST['nom']) && !empty($_POST['id_type'])) {
    $sql = "INSERT INTO MATERIEL (nom, annee, details, id_type, id_parent) 
            VALUES (:nom, :annee, :details, :id_type, :id_parent)";
    
    $ins = $connexion->prepare($sql);
    
    // On gère le parent : si vide dans le formulaire, on envoie NULL à la base
    $parentValue = !empty($_POST['id_parent']) ? $_POST['id_parent'] : null;

    $ins->execute([
        ':nom'       => $_POST['nom'],
        ':annee'     => $_POST['annee'],
        ':details'   => $_POST['details'],
        ':id_type'   => $_POST['id_type'],
        ':id_parent' => $parentValue
    ]);

    // Redirection pour éviter de renvoyer le formulaire en actualisant la page
    header("Location: index.php");
    exit;
}

// --- RÉCUPÉRATION DES DONNÉES ---

// 1. Détails d'un élément sélectionné au clic
$selected = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $sel = $connexion->prepare("SELECT * FROM vue_materiel WHERE id = ?");
    $sel->execute([$_GET['id']]);
    $selected = $sel->fetch();
}

// 2. Liste pour le tableau (via ta vue SQL)
$liste = $connexion->query("SELECT * FROM vue_materiel ORDER BY id ASC")->fetchAll();

// 3. Liste des catégories (pour le menu déroulant Type)
$categories = $connexion->query("SELECT * FROM CATEGORIE ORDER BY libelle ASC")->fetchAll();

// 4. Liste des matériels existants (pour le menu déroulant Parent)
$parents = $connexion->query("SELECT id, nom FROM MATERIEL ORDER BY nom ASC")->fetchAll();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Inventaire Informatique M2L</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f7f6; padding: 20px; color: #333; }
        .container { max-width: 1100px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        h1 { color: #2c3e50; border-bottom: 3px solid #3498db; padding-bottom: 15px; margin-top: 0; }
        h3 { color: #2980b9; margin-top: 30px; }
        
        /* Table Styles */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { text-align: left; padding: 15px; border-bottom: 1px solid #edf2f7; }
        th { background: #3498db; color: white; text-transform: uppercase; font-size: 13px; letter-spacing: 1px; }
        tr:hover { background: #f8fbff; cursor: pointer; transition: 0.2s; }
        
        /* Form Styles */
        .form-box { background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #e1e8ed; }
        .form-group { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-bottom: 15px; }
        input, select, textarea { padding: 10px; border: 1px solid #cbd5e0; border-radius: 6px; width: 100%; box-sizing: border-box; font-size: 14px; }
        input:focus, select:focus { border-color: #3498db; outline: none; box-shadow: 0 0 0 3px rgba(52,152,219,0.1); }
        
        .btn { background: #27ae60; color: white; border: none; padding: 12px 25px; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 15px; }
        .btn:hover { background: #219150; }

        /* Detail Card */
        .card { background: #ebf8ff; border-left: 6px solid #3498db; padding: 20px; margin-bottom: 30px; position: relative; }
        .card h2 { margin-top: 0; color: #2c3e50; }
        .card a { color: #e74c3c; text-decoration: none; font-weight: bold; position: absolute; top: 20px; right: 20px; }
        
        .tag { display: inline-block; background: #edf2f7; padding: 4px 10px; border-radius: 4px; font-size: 12px; color: #4a5568; }
    </style>
</head>
<body>

<div class="container">
    <h1><span style="font-weight:300;">Gestion du</span> Parc Informatique</h1>

    <?php if ($selected): ?>
    <div class="card">
        <a href="index.php">✖ Fermer</a>
        <h2>Fiche : <?php echo htmlspecialchars($selected['nom']); ?></h2>
        <p><strong>Catégorie :</strong> <?php echo htmlspecialchars($selected['type_libelle']); ?></p>
        <p><strong>Année d'acquisition :</strong> <?php echo htmlspecialchars($selected['annee']); ?></p>
        <p><strong>Description technique :</strong> <?php echo nl2br(htmlspecialchars($selected['details'] ?: 'Aucun détail.')); ?></p>
        <p><strong>Dépendance :</strong> <?php echo htmlspecialchars($selected['parent_nom'] ?: 'Équipement autonome'); ?></p>
    </div>
    <?php endif; ?>

    <div class="form-box">
        <h3>➕ Ajouter un nouveau matériel</h3>
        <form method="post">
            <div class="form-group">
                <input name="nom" placeholder="Nom (ex: Dell Latitude 5420)" required>
                <input name="annee" type="number" placeholder="Année (ex: 2024)" required>
                
                <select name="id_type" required>
                    <option value="">-- Sélectionner le Type --</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?php echo $cat['id_type']; ?>">
                            <?php echo htmlspecialchars($cat['libelle']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <select name="id_parent">
                    <option value="">-- Appartient à (Aucun) --</option>
                    <?php foreach($parents as $p): ?>
                        <option value="<?php echo $p['id']; ?>">
                            <?php echo htmlspecialchars($p['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <textarea name="details" placeholder="Détails techniques (Configuration, RAM, CPU...)" style="grid-column: span 2; height: 40px;"></textarea>
            </div>
            <button type="submit" class="btn">Enregistrer l'équipement</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Désignation</th>
                <th>Année</th>
                <th>Type</th>
                <th>Parent</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($liste as $item): ?>
            <tr onclick="window.location.href='?id=<?php echo $item['id']; ?>'">
                <td><span class="tag">#<?php echo $item['id']; ?></span></td>
                <td style="color: #3498db; font-weight: 600;"><?php echo htmlspecialchars($item['nom']); ?></td>
                <td><?php echo htmlspecialchars($item['annee']); ?></td>
                <td><strong><?php echo htmlspecialchars($item['type_libelle']); ?></strong></td>
                <td style="color: #7f8c8d; font-style: italic;">
                    <?php echo htmlspecialchars($item['parent_nom'] ?: '—'); ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($liste)): ?>
                <tr><td colspan="5" style="text-align:center;">Aucun matériel dans la base.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
