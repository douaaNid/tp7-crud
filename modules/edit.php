<?php
require_once '../database/connexion.php';

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM modules WHERE id_module = :id');
$stmt->execute([':id' => $id]);
$module = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$module) {
    header('Location: index.php?msg=inconnu');
    exit;
}

$formations = $pdo->query('SELECT id_formation, titre FROM formations ORDER BY titre')->fetchAll(PDO::FETCH_ASSOC);

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $intitule     = trim($_POST['intitule'] ?? '');
    $volume       = (int) ($_POST['volume_horaire'] ?? 0);
    $id_formation = (int) ($_POST['id_formation'] ?? 0);

    $module['intitule'] = $intitule;
    $module['volume_horaire'] = $volume;
    $module['id_formation'] = $id_formation;

    if ($intitule === '' || $volume <= 0 || $id_formation <= 0) {
        $erreur = 'Veuillez remplir tous les champs correctement.';
    } else {
        $upd = $pdo->prepare('UPDATE modules
                              SET intitule = :intitule, volume_horaire = :volume, id_formation = :formation
                              WHERE id_module = :id');
        $upd->execute([
            ':intitule' => $intitule, ':volume' => $volume,
            ':formation' => $id_formation, ':id' => $id,
        ]);
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
    <title>Modifier un module</title>
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
    <h1>Modifier le module</h1>
    <?php if ($erreur): ?><div class="alert alert-err"><?= htmlspecialchars($erreur) ?></div><?php endif; ?>
    <div class="card">
        <form method="post">
            <input type="hidden" name="id" value="<?= $id ?>">

            <label for="intitule">Intitulé</label>
            <input type="text" id="intitule" name="intitule" value="<?= htmlspecialchars($module['intitule']) ?>" required>

            <label for="volume_horaire">Volume horaire (heures)</label>
            <input type="number" id="volume_horaire" name="volume_horaire" min="1" value="<?= (int) $module['volume_horaire'] ?>" required>

            <label for="id_formation">Formation</label>
            <select id="id_formation" name="id_formation" required>
                <option value="">-- Choisir une formation --</option>
                <?php foreach ($formations as $f): ?>
                    <option value="<?= (int) $f['id_formation'] ?>" <?= (int) $module['id_formation'] === (int) $f['id_formation'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($f['titre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a class="btn" href="index.php">Annuler</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>
