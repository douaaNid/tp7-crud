<?php require_once 'database/connexion.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Centre de formation - Accueil</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="topbar">
    <span class="brand">CENTRE DE FORMATION</span>
    <a href="index.php">Accueil</a>
    <a href="formations/index.php">Formations</a>
    <a href="etudiants/index.php">Étudiants</a>
    <a href="modules/index.php">Modules</a>
</header>
<main>
    <h1>Gestion du centre de formation</h1>
    <p>Choisissez un espace de gestion.</p>
    <div class="grid">
        <div class="quick-card">
            <h2>Formations</h2>
            <p>Gérez les formations proposées par le centre.</p>
            <a class="btn btn-primary" href="formations/index.php">Gérer les formations</a>
        </div>
        <div class="quick-card">
            <h2>Étudiants</h2>
            <p>Inscrivez les étudiants dans une formation.</p>
            <a class="btn btn-primary" href="etudiants/index.php">Gérer les étudiants</a>
        </div>
        <div class="quick-card">
            <h2>Modules</h2>
            <p>Gérez les modules de chaque formation.</p>
            <a class="btn btn-primary" href="modules/index.php">Gérer les modules</a>
        </div>
    </div>
</main>
</body>
</html>
