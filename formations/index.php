<?php
require_once '../database/connexion.php';

$sql = "SELECT f.id_formation, f.titre, f.duree_mois, f.niveau,
               (SELECT COUNT(*) FROM etudiants e WHERE e.id_formation = f.id_formation) AS nb_etudiants,
               (SELECT COUNT(*) FROM modules m WHERE m.id_formation = f.id_formation) AS nb_modules
        FROM formations f
        ORDER BY f.titre";
$formations = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$messages = [
    'ajoute'   => ['ok',  'Formation ajoutée.'],
    'modifie'  => ['ok',  'Formation modifiée.'],
    'supprime' => ['ok',  'Formation supprimée.'],
    'lie'      => ['err', 'Suppression impossible : cette formation contient encore des étudiants ou des modules.'],
    'inconnu'  => ['err', 'Formation introuvable.'],
];
$msg = $messages[$_GET['msg'] ?? ''] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formations</title>
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
    <div class="toolbar">
        <h1>Formations</h1>
        <a class="btn btn-primary" href="create.php">+ Ajouter une formation</a>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $msg[0] ?>"><?= $msg[1] ?></div>
    <?php endif; ?>

    <div class="card">
    <?php if (empty($formations)): ?>
        <p class="empty">Aucune formation pour le moment.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr><th>ID</th><th>Titre</th><th>Durée</th><th>Niveau</th><th>Étudiants</th><th>Modules</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($formations as $f): ?>
                <tr>
                    <td><?= (int) $f['id_formation'] ?></td>
                    <td><?= htmlspecialchars($f['titre']) ?></td>
                    <td><?= (int) $f['duree_mois'] ?> mois</td>
                    <td><?= htmlspecialchars($f['niveau']) ?></td>
                    <td><?= (int) $f['nb_etudiants'] ?></td>
                    <td><?= (int) $f['nb_modules'] ?></td>
                    <td class="actions">
                        <a class="btn" href="edit.php?id=<?= (int) $f['id_formation'] ?>">Modifier</a>
                        <form class="inline" action="delete.php" method="post"
                              onsubmit="return confirm('Supprimer cette formation ?');">
                            <input type="hidden" name="id" value="<?= (int) $f['id_formation'] ?>">
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    </div>
</main>
</body>
</html>
