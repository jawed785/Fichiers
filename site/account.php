<?php
session_start();  // démarrage de la session

// Vérification que les variables de session identifiant l'utilisateur existent
if (isset($_SESSION['email']) && isset($_SESSION['mdp'])) {
    $email = $_SESSION['email'];
    $mdp = $_SESSION['mdp'];
    $nom_utilisateur = $_SESSION['nom_utilisateur'];
    $prenom_utilisateur = $_SESSION['prenom_utilisateur'];  // Utilisez la bonne variable ici
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Compte - Passe Sport</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <nav>
        <div class="logo-container">
            <div id="logo">
                <a href="accueil.php"><img src="pass.png" alt="Sportlock" /></a>
            </div>
            <button class="nav-button event-btn" onclick="window.location.href ='eventpage.php';">Tous les événements</button>
        </div>
        
        <div class="auth-buttons">
            <?php if (isset($email) && isset($mdp)): ?>
                <button class="nav-button account-btn" onclick="window.location.href = 'account.php';">Mon Compte</button>
            <?php else: ?>
                <button class="nav-button login-btn" onclick="window.location.href ='login.php';">Connexion</button>
                <button class="nav-button signup-btn" onclick="window.location.href ='inscription.php';">Inscription</button>
            <?php endif; ?>
        </div>
    </nav>
</header>

<main class="auth-container">
    <div class="account-header">
        <h1><i class="fas fa-user-circle"></i> Mon Compte</h1>
        <?php if (isset($prenom_utilisateur) && isset($nom_utilisateur)): ?>
            <p class="welcome-message">Bienvenue, <?= htmlspecialchars($prenom_utilisateur) ?> <?= htmlspecialchars($nom_utilisateur) ?> !</p>
        <?php else: ?>
            <p class="welcome-message">Bienvenue sur votre espace personnel !</p>
        <?php endif; ?>
    </div>
    
    <div class="auth-content active">
        <h2><i class="fas fa-user-edit"></i> Informations personnelles</h2>
        
        <form method="POST" action="update_account.php" class="account-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                <i class="fas fa-envelope form-icon"></i>
            </div>
            
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($nom_utilisateur) ?>" required>
                <i class="fas fa-user form-icon"></i>
            </div>
            
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($prenom_utilisateur) ?>" required>
                <i class="fas fa-user form-icon"></i>
            </div>
            
            <button type="submit" class="auth-button">
                <i class="fas fa-sync-alt"></i> Mettre à jour
            </button>
        </form>
        
        <button class="btn-disconnect" onclick="window.location.href = 'deconnexion.php';">
            <i class="fas fa-sign-out-alt"></i> Se déconnecter
        </button>
    </div>

    <div id="user-tab" class="auth-content active">
        <h2><i class="fas fa-user"></i> Ajoutez un événement</h2>
        <form action="add_event.php" method="post">
            <div class="form-group">
                <input type="text" name="prenom_utilisateur" placeholder="Prénom" required>
                <i class="fas fa-user form-icon"></i>
            </div>
            <div class="form-group">
                <input type="text" name="nom_utilisateur" placeholder="Nom" required>
                <i class="fas fa-user form-icon"></i>
            </div>
            <div class="form-group">
                <input type="date" name="date_naissance" placeholder="Date de naissance" required>
                <i class="fas fa-calendar-alt form-icon"></i>
            </div>
            <div class="form-group">
                <input type="email" name="email_user" placeholder="E-mail" required>
                <i class="fas fa-envelope form-icon"></i>
            </div>
            <div class="form-group">
                <input type="password" name="mdp_user" placeholder="Mot de passe" required>
                <i class="fas fa-lock form-icon"></i>
            </div>
            <button type="submit" class="auth-button">S'inscrire</button>
        </form>
        <p class="auth-link">Vous avez déjà un compte ? <a href="login.php">Connectez-vous</a></p>
    </div>
</main>
</body>
</html>
