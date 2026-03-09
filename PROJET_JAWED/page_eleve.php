<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Élève</title>
    <link rel="stylesheet" href="style_pageeleve.css">
</head>
<body>

    <header>
        <nav class="navbar">
            <div class="logo">🎓 Espace Élève</div>
            <ul class="nav-links">
                <li><a href="profil_eleve.php">Profil</a></li>
                <li><a href="compterendu_eleve.php">Compte Rendu</a></li>
                <li><a href="index.php" class="logout">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main class="content">
        <div class="welcome-box">
            <h1>Bienvenue dans ton espace personnel 👋</h1>
            <p>Accède à ton profil, consulte tes comptes rendus et reste à jour dans ta scolarité.</p>
            <div class="actions">
                <a href="profil_eleve.php" class="btn">Voir mon Profil</a>
                <a href="compterendu_eleve.php" class="btn secondary">Mes Comptes Rendus</a>
            </div>
        </div>
    </main>

</body>
</html>