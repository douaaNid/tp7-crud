<?php
require_once '../database/connexion.php';

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM formations WHERE id_formation = :id');
$stmt->execute([':id' => $id]);
$formation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$formation) {
    header('Location: index.php?msg=inconnu');
    exit;
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre  = trim($_POST['titre'] ?? '');
    $duree  = (int) ($_POST['duree_mois'] ?? 0);
    $niveau = trim($_POST['niveau'] ?? '');

    $formation['titre'] = $titre;
    $formation['duree_mois'] = $duree;
    $formation['niveau'] = $niveau;

    if ($titre === '' || $niveau === '' || $duree <= 0) {
        $erreur = 'Veuillez remplir tous les champs correctement.';
    } else {
        $upd = $pdo->prepare('UPDATE formations SET titre = :titre, duree_mois = :duree, niveau = :niveau WHERE id_formation = :id');
        $upd->execute([':titre' => $titre, ':duree' => $duree, ':niveau' => $niveau, ':id' => $id]);
        header('Location: index.php?msg=modifie');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier une formation</title>
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
    <h1>Modifier la formation</h1>
    <?php if ($erreur): ?><div class="alert alert-err"><?= htmlspecialchars($erreur) ?></div><?php endif; ?>
    <div class="card">
        <form method="post">
            <input type="hidden" name="id" value="<?= $id ?>">

            <label for="titre">Titre</label>
            <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($formation['titre']) ?>" required>

            <label for="duree_mois">Durée (en mois)</label>
            <input type="number" id="duree_mois" name="duree_mois" min="1" value="<?= (int) $formation['duree_mois'] ?>" required>

            <label for="niveau">Niveau</label>
            <input type="text" id="niveau" name="niveau" value="<?= htmlspecialchars($formation['niveau']) ?>" required>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a class="btn" href="index.php">Annuler</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>
