<?php if ($selected): ?>
<div class="card">
    <a href="index.php">✖ Fermer</a>
    <h2>Fiche : <?php echo htmlspecialchars($selected['nom']); ?></h2>
    <p><strong>Catégorie :</strong> <?php echo htmlspecialchars($selected['type_libelle']); ?></p>
    <p><strong>Année d'acquisition :</strong> <?php echo htmlspecialchars($selected['annee']); ?></p>
    <p><strong>Description technique :</strong> <?php echo nl2br(htmlspecialchars($selected['details'] ?: 'Aucun détail.')); ?></p>
    <p><strong>Dépendance :</strong> <?php echo htmlspecialchars($selected['parent_nom'] ?: 'Équipement autonome'); ?></p>

    <!-- Boutons d'action -->
    <div style="margin-top: 15px; display: flex; gap: 10px;">
        <a href="edit.php?id=<?php echo $selected['id']; ?>" class="btn">✏️ Modifier</a>
        <a href="delete.php?id=<?php echo $selected['id']; ?>"
           class="btn btn-danger"
           onclick="return confirm('Supprimer cet équipement ?')">
           🗑️ Supprimer
        </a>
    </div>
</div>
<?php endif; ?>

</body>
</html>
