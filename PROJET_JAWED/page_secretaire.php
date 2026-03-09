<?php
session_start();
include '_conf.php';

// Vérifier si l'utilisateur est connecté et est secrétaire (type 4)
if (!isset($_SESSION['Sid']) || $_SESSION['type'] != 4) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Secrétariat</title>
    <link rel="stylesheet" href="style_pageeleve.css">
    <style>
        .secretaire-only {
            background-color: #4ecdc4;
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
        <div class="logo">📋 Espace Secrétariat</div>
        <ul class="nav-links">
            <li><a href="profil_eleve.php">Profil</a></li>
            <li><a href="statistiques.php" class="active">📈 Statistiques</a></li>
            <li><a href="index.php" class="logout">Déconnexion</a></li>
        </ul>
    </nav>
</header>

<main class="content">
    <div class="welcome-box">
        <div class="secretaire-only">
            📄 Accès secrétariat
        </div>
        <h1>Bienvenue dans l'espace Secrétariat 📋</h1>
        <p>Consultation des statistiques et gestion administrative.</p>
        <div class="actions">
            <a href="statistiques.php" class="btn">Voir les Statistiques</a>
            <a href="documents.php" class="btn secondary">Documents</a>
        </div>
    </div>
</main>

</body>
</html>