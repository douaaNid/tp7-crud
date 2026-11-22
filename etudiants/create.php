<?php
require_once '../database/connexion.php';

// Liste des formations pour le <select>
$formations = $pdo->query('SELECT id_formation, titre FROM formations ORDER BY titre')->fetchAll(PDO::FETCH_ASSOC);

$erreur = '';
$nom = $prenom = $email = '';
$id_formation = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom          = trim($_POST['nom'] ?? '');
    $prenom       = trim($_POST['prenom'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $id_formation = (int) ($_POST['id_formation'] ?? 0);

    if ($nom === '' || $prenom === '' || $id_formation <= 0) {
        $erreur = 'Veuillez remplir tous les champs.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = 'Adresse email invalide.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO etudiants (nom, prenom, email, id_formation)
                               VALUES (:nom, :prenom, :email, :formation)');
        $stmt->execute([
            ':nom' => $nom, ':prenom' => $prenom, ':email' => $email, ':formation' => $id_formation,
        ]);
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
    <title>Ajouter un étudiant</title>
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
    <h1>Ajouter un étudiant</h1>
    <?php if ($erreur): ?><div class="alert alert-err"><?= htmlspecialchars($erreur) ?></div><?php endif; ?>
    <div class="card">
    <?php if (empty($formations)): ?>
        <p class="empty">Créez d'abord une formation avant d'ajouter un étudiant.
            <a href="../formations/create.php">Ajouter une formation</a></p>
    <?php else: ?>
        <form method="post">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($nom) ?>" required>

            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($prenom) ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

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
