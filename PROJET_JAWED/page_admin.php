<?php
session_start();
include '_conf.php';

// Vérifier si l'utilisateur est connecté et est admin (type 3)
if (!isset($_SESSION['Sid']) || $_SESSION['type'] != 3) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Administration</title>
    <link rel="stylesheet" href="style_pageeleve.css">
    <style>
        .admin-only {
            background-color: #ff6b6b;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<header>
    <nav class="navbar">
        <div class="logo">👑 Espace Administration</div>
        <ul class="nav-links">
            <li><a href="profil_eleve.php">Profil</a></li>
            <li><a href="statistiques.php" class="active">📈 Statistiques</a></li>
            <li><a href="index.php" class="logout">Déconnexion</a></li>
        </ul>
    </nav>
</header>

<main class="content">
    <div class="welcome-box">
        <div class="admin-only">
            ⚠️ Accès réservé aux administrateurs
        </div>
        <h1>Bienvenue dans l'espace Administration 👑</h1>
        <p>Gestion complète des statistiques et de la structure de l'établissement.</p>
        <div class="actions">
            <a href="statistiques.php" class="btn">Voir les Statistiques</a>
            <a href="structure.php" class="btn secondary">Gérer la Structure</a>
        </div>
    </div>
</main>

</body>
</html>