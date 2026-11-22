<?php
require_once '../database/connexion.php';

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM etudiants WHERE id_etudiant = :id');
$stmt->execute([':id' => $id]);
$etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$etudiant) {
    header('Location: index.php?msg=inconnu');
    exit;
}

$formations = $pdo->query('SELECT id_formation, titre FROM formations ORDER BY titre')->fetchAll(PDO::FETCH_ASSOC);

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom          = trim($_POST['nom'] ?? '');
    $prenom       = trim($_POST['prenom'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $id_formation = (int) ($_POST['id_formation'] ?? 0);

    $etudiant['nom'] = $nom;
    $etudiant['prenom'] = $prenom;
    $etudiant['email'] = $email;
    $etudiant['id_formation'] = $id_formation;

    if ($nom === '' || $prenom === '' || $id_formation <= 0) {
        $erreur = 'Veuillez remplir tous les champs.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = 'Adresse email invalide.';
    } else {
        $upd = $pdo->prepare('UPDATE etudiants
                              SET nom = :nom, prenom = :prenom, email = :email, id_formation = :formation
                              WHERE id_etudiant = :id');
        $upd->execute([
            ':nom' => $nom, ':prenom' => $prenom, ':email' => $email,
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
    <title>Modifier un étudiant</title>
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
    <h1>Modifier l'étudiant</h1>
    <?php if ($erreur): ?><div class="alert alert-err"><?= htmlspecialchars($erreur) ?></div><?php endif; ?>
    <div class="card">
        <form method="post">
            <input type="hidden" name="id" value="<?= $id ?>">

            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($etudiant['nom']) ?>" required>

            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($etudiant['prenom']) ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($etudiant['email']) ?>" required>

            <label for="id_formation">Formation</label>
            <select id="id_formation" name="id_formation" required>
                <option value="">-- Choisir une formation --</option>
                <?php foreach ($formations as $f): ?>
                    <option value="<?= (int) $f['id_formation'] ?>" <?= (int) $etudiant['id_formation'] === (int) $f['id_formation'] ? 'selected' : '' ?>>
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
