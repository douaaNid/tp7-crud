<?php
require_once '../database/connexion.php';

// Jointure : on affiche le titre de la formation, pas seulement son identifiant
$sql = "SELECT e.id_etudiant, e.nom, e.prenom, e.email, f.titre AS formation
        FROM etudiants e
        INNER JOIN formations f ON f.id_formation = e.id_formation
        ORDER BY e.nom, e.prenom";
$etudiants = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$messages = [
    'ajoute'   => ['ok',  'Étudiant ajouté.'],
    'modifie'  => ['ok',  'Étudiant modifié.'],
    'supprime' => ['ok',  'Étudiant supprimé.'],
    'inconnu'  => ['err', 'Étudiant introuvable.'],
    'erreur'   => ['err', 'Une erreur est survenue.'],
];
$msg = $messages[$_GET['msg'] ?? ''] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Étudiants</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header class="topbar">
    <span class="brand">CENTRE DE FORMATION</span>
    <a href="../index.php">Accueil</a>
    <a href="../formations/index.php">Formations</a>
    <a href="index.php">Étudiants</a>
    <a href="../modules/index.php">Modules</a>
</header>
<main>
    <div class="toolbar">
        <h1>Étudiants</h1>
        <a class="btn btn-primary" href="create.php">+ Ajouter un étudiant</a>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $msg[0] ?>"><?= $msg[1] ?></div>
    <?php endif; ?>

    <div class="card">
    <?php if (empty($etudiants)): ?>
        <p class="empty">Aucun étudiant pour le moment.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr><th>ID</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Formation</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($etudiants as $e): ?>
                <tr>
                    <td><?= (int) $e['id_etudiant'] ?></td>
                    <td><?= htmlspecialchars($e['nom']) ?></td>
                    <td><?= htmlspecialchars($e['prenom']) ?></td>
                    <td><?= htmlspecialchars($e['email']) ?></td>
                    <td><?= htmlspecialchars($e['formation']) ?></td>
                    <td class="actions">
                        <a class="btn" href="edit.php?id=<?= (int) $e['id_etudiant'] ?>">Modifier</a>
                        <form class="inline" action="delete.php" method="post"
                              onsubmit="return confirm('Supprimer cet étudiant ?');">
                            <input type="hidden" name="id" value="<?= (int) $e['id_etudiant'] ?>">
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
