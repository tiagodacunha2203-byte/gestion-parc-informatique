<?php

require('credentials.php');
try {
    $connexion = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $password);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Erreur de connexion : " . $e->getMessage());
}


if (!empty($_POST['nom'])) {
    $sql = "INSERT INTO materiel (nom, annee, details, type_libelle, parent_nom) 
            VALUES (:nom, :annee, :details, :type, :parent)";
    
    $ins = $connexion->prepare($sql);
    $ins->execute([
        ':nom'     => $_POST['nom'],
        ':annee'   => $_POST['annee'],
        ':details' => $_POST['details'],
        ':type'    => $_POST['type_libelle'],
        ':parent'  => $_POST['parent_nom']
    ]);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


// Récupération de l'élément sélectionné
$selected = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $sel = $connexion->prepare("SELECT * FROM materiel WHERE id = ?");
    $sel->execute([$_GET['id']]);
    $selected = $sel->fetch();
}


$liste = $connexion->query("SELECT * FROM materiel ORDER BY id ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Inventaire Informatique</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f9f9f9; padding: 20px; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #eee; }
        th { background: #3498db; color: white; }
        tr:hover { background: #f1f1f1; cursor: pointer; }
        .card { background: #e8f4fd; border-left: 5px solid #3498db; padding: 15px; margin-bottom: 20px; }
        .form-group { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-bottom: 15px; }
        input, select, textarea { padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 100%; }
        .btn { background: #27ae60; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <h1> Gestion du Parc Informatique</h1>

    <?php if ($selected): ?>
    <div class="card">
        <h2>Détails : <?php echo htmlspecialchars($selected['nom']); ?></h2>
        <p><strong>Type :</strong> <?php echo htmlspecialchars($selected['type_libelle']); ?></p>
        <p><strong>Année :</strong> <?php echo htmlspecialchars($selected['annee']); ?></p>
        <p><strong>Configuration :</strong> <?php echo htmlspecialchars($selected['details']); ?></p>
        <p><strong>Appartient à :</strong> <?php echo htmlspecialchars($selected['parent_nom'] ?: '—'); ?></p>
        <a href="?">✖ Fermer la fiche</a>
    </div>
    <?php endif; ?>

    <h3> Ajouter un composant ou matériel</h3>
    <form method="post">
        <div class="form-group">
            <input name="nom" placeholder="Nom (ex: CPU PC1)" required>
            <input name="annee" type="number" placeholder="Année (ex: 2016)" required>
            <input name="type_libelle" placeholder="Type (ex: RAM, PC, OS)">
        </div>
        <div class="form-group">
            <input name="parent_nom" placeholder="Appartient à... (nom du parent)">
            <textarea name="details" placeholder="Détails techniques..." style="grid-column: span 2;"></textarea>
        </div>
        <button type="submit" class="btn">Enregistrer dans la base</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Année</th>
                <th>Type</th>
                <th>Parent</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($liste as $item): ?>
            <tr onclick="window.location.href='?id=<?php echo $item['id']; ?>'">
                <td><?php echo $item['id']; ?></td>
                <td style="color: #3498db; font-weight: bold;"><?php echo htmlspecialchars($item['nom']); ?></td>
                <td><?php echo htmlspecialchars($item['annee']); ?></td>
                <td><?php echo htmlspecialchars($item['type_libelle']); ?></td>
                <td><?php echo htmlspecialchars($item['parent_nom'] ?: '—'); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
