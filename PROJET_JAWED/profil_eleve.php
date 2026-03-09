<?php
session_start();
include '_conf.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['Sid'])) {
    header("Location: index.php");
    exit;
}

// Connexion PDO
$pdo = new PDO('mysql:host=localhost;dbname=projet_jawed;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Récupération de l'utilisateur connecté
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
$stmt->execute([$_SESSION['Sid']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "❌ Erreur : utilisateur introuvable.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Élève</title>
    <link rel="stylesheet" href="profil_eleve.css">
</head>
<body>

<header>
    <nav class="navbar">
        <div class="logo">🎓 Espace Élève</div>
        <ul class="nav-links">
            <li><a href="profil_eleve.php">Profil</a></li>
            <li><a href="compterendu_eleve.php">Compte Rendu</a></li>
            <li><a href="logout.php" class="logout">Déconnexion</a></li>
        </ul>
    </nav>
</header>

<main class="content">
    <div class="profile-box">
        <h1>Profil :</h1>

        <div class="profile-info">
            <p><strong>Nom :</strong> <?= htmlspecialchars($user['nom']) ?></p>
            <p><strong>Prénom :</strong> <?= htmlspecialchars($user['prenom']) ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Téléphone :</strong> <?= htmlspecialchars($user['tel']) ?></p>
        </div>

        <a href="compterendu_eleve.php" class="btn secondary">Mes Comptes Rendus</a>
    </div>
</main>


</body>
</html>
