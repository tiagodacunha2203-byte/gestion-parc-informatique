<?php
session_start();
$error = "";

if (isset($_POST['password'])) {
    // Mot de passe de test (à changer pour la production)
    if ($_POST['password'] === 'admin123') {
        $_SESSION['admin_logged'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Administration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-secondary vh-100 d-flex align-items-center">
    <div class="container" style="max-width: 400px;">
        <div class="card shadow p-4">
            <h3 class="text-center mb-4">Accès Restreint</h3>
            <?php if($error): ?> <div class="alert alert-danger"><?= $error ?></div> <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label>Mot de passe admin</label>
                    <input type="password" name="password" class="form-control" required autofocus>
                </div>
                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                <div class="text-center mt-3"><a href="index.php" class="text-muted">Retour au site</a></div>
            </form>
        </div>
    </div>
</body>
</html>
