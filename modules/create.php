<?php
require_once '../database/connexion.php';

// Liste des formations pour le <select>
$formations = $pdo->query('SELECT id_formation, titre FROM formations ORDER BY titre')->fetchAll(PDO::FETCH_ASSOC);

$erreur = '';
$intitule = '';
$volume = '';
$id_formation = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $intitule     = trim($_POST['intitule'] ?? '');
    $volume       = (int) ($_POST['volume_horaire'] ?? 0);
    $id_formation = (int) ($_POST['id_formation'] ?? 0);

    if ($intitule === '' || $volume <= 0 || $id_formation <= 0) {
        $erreur = 'Veuillez remplir tous les champs correctement.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO modules (intitule, volume_horaire, id_formation)
                               VALUES (:intitule, :volume, :formation)');
        $stmt->execute([':intitule' => $intitule, ':volume' => $volume, ':formation' => $id_formation]);
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
    <title>Ajouter un module</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header class="topbar">
    <span class="brand">CENTRE DE FORMATION</span>
    <a href="../index.php">Accueil</a>
    <a href="../formations/index.php">Formations</a>
    <a href="../etudiants/index.php">Étudiants</a>
    <a href="index.php">Modules</a>
</header>
<main>
    <h1>Ajouter un module</h1>
    <?php if ($erreur): ?><div class="alert alert-err"><?= htmlspecialchars($erreur) ?></div><?php endif; ?>
    <div class="card">
    <?php if (empty($formations)): ?>
        <p class="empty">Créez d'abord une formation avant d'ajouter un module.
            <a href="../formations/create.php">Ajouter une formation</a></p>
    <?php else: ?>
        <form method="post">
            <label for="intitule">Intitulé</label>
            <input type="text" id="intitule" name="intitule" value="<?= htmlspecialchars($intitule) ?>" required>

            <label for="volume_horaire">Volume horaire (heures)</label>
            <input type="number" id="volume_horaire" name="volume_horaire" min="1" value="<?= htmlspecialchars((string) $volume) ?>" required>

            <label for="id_formation">Formation</label>
            <select id="id_formation" name="id_formation" required>
                <option value="">-- Choisir une formation --</option>
                <?php foreach ($formations as $f): ?>
                    <option value="<?= (int) $f['id_formation'] ?>" <?= $id_formation === (int) $f['id_formation'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($f['titre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a class="btn" href="index.php">Annuler</a>
            </div>
        </form>
    <?php endif; ?>
    </div>
</main>
</body>
</html>
