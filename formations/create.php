<?php
require_once '../database/connexion.php';

$erreur = '';
$titre = '';
$duree = '';
$niveau = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre  = trim($_POST['titre'] ?? '');
    $duree  = (int) ($_POST['duree_mois'] ?? 0);
    $niveau = trim($_POST['niveau'] ?? '');

    if ($titre === '' || $niveau === '' || $duree <= 0) {
        $erreur = 'Veuillez remplir tous les champs correctement.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO formations (titre, duree_mois, niveau) VALUES (:titre, :duree, :niveau)');
        $stmt->execute([':titre' => $titre, ':duree' => $duree, ':niveau' => $niveau]);
        header('Location: index.php?msg=ajoute');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter une formation</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header class="topbar">
    <span class="brand">CENTRE DE FORMATION</span>
    <a href="../index.php">Accueil</a>
    <a href="index.php">Formations</a>
    <a href="../etudiants/index.php">Étudiants</a>
    <a href="../modules/index.php">Modules</a>
</header>
<main>
    <h1>Ajouter une formation</h1>
    <?php if ($erreur): ?><div class="alert alert-err"><?= htmlspecialchars($erreur) ?></div><?php endif; ?>
    <div class="card">
        <form method="post">
            <label for="titre">Titre</label>
            <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($titre) ?>" required>

            <label for="duree_mois">Durée (en mois)</label>
            <input type="number" id="duree_mois" name="duree_mois" min="1" value="<?= htmlspecialchars((string) $duree) ?>" required>

            <label for="niveau">Niveau</label>
            <input type="text" id="niveau" name="niveau" placeholder="Licence, Master, Certification..." value="<?= htmlspecialchars($niveau) ?>" required>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a class="btn" href="index.php">Annuler</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>
