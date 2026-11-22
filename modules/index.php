<?php
require_once '../database/connexion.php';

// Jointure : on affiche le titre de la formation, pas seulement son identifiant
$sql = "SELECT m.id_module, m.intitule, m.volume_horaire, f.titre AS formation
        FROM modules m
        INNER JOIN formations f ON f.id_formation = m.id_formation
        ORDER BY f.titre, m.intitule";
$modules = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$messages = [
    'ajoute'   => ['ok',  'Module ajouté.'],
    'modifie'  => ['ok',  'Module modifié.'],
    'supprime' => ['ok',  'Module supprimé.'],
    'inconnu'  => ['err', 'Module introuvable.'],
    'erreur'   => ['err', 'Une erreur est survenue.'],
];
$msg = $messages[$_GET['msg'] ?? ''] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modules</title>
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
    <div class="toolbar">
        <h1>Modules</h1>
        <a class="btn btn-primary" href="create.php">+ Ajouter un module</a>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $msg[0] ?>"><?= $msg[1] ?></div>
    <?php endif; ?>

    <div class="card">
    <?php if (empty($modules)): ?>
        <p class="empty">Aucun module pour le moment.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr><th>ID</th><th>Intitulé</th><th>Volume horaire</th><th>Formation</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($modules as $m): ?>
                <tr>
                    <td><?= (int) $m['id_module'] ?></td>
                    <td><?= htmlspecialchars($m['intitule']) ?></td>
                    <td><?= (int) $m['volume_horaire'] ?> h</td>
                    <td><?= htmlspecialchars($m['formation']) ?></td>
                    <td class="actions">
                        <a class="btn" href="edit.php?id=<?= (int) $m['id_module'] ?>">Modifier</a>
                        <form class="inline" action="delete.php" method="post"
                              onsubmit="return confirm('Supprimer ce module ?');">
                            <input type="hidden" name="id" value="<?= (int) $m['id_module'] ?>">
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
