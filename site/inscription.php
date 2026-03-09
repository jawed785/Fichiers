<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passe Sport - Inscription</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php
    session_start();
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=site_event;', 'root', 'root');
    } catch(Exception $e) {
        die('Erreur : '.$e->getMessage());
    }
    ?>
    
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
        <div class="auth-tabs">
            <button class="auth-tab active" onclick="showTab('user')">Utilisateur</button>
            <button class="auth-tab" onclick="showTab('admin')">Administrateur</button>
        </div>
        
        <div id="user-tab" class="auth-content active">
            <h2><i class="fas fa-user"></i> Créer un compte utilisateur</h2>
            <form action="result_inscription_user.php" method="post">
                <div class="form-group">
                    <input type="text" name="prenom_user" placeholder="Prénom" required>
                    <i class="fas fa-user form-icon"></i>
                </div>
                <div class="form-group">
                    <input type="text" name="nom_user" placeholder="Nom" required>
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
        
        <div id="admin-tab" class="auth-content">
            <h2><i class="fas fa-user-shield"></i> Créer un compte administrateur</h2>
            <form action="result_inscription_admin.php" method="post">
                <div class="form-group">
                    <input type="text" name="prenom_admin" placeholder="Prénom" required>
                    <i class="fas fa-user form-icon"></i>
                </div>
                <div class="form-group">
                    <input type="text" name="nom_admin" placeholder="Nom" required>
                    <i class="fas fa-user form-icon"></i>
                </div>
                <div class="form-group">
                    <input type="date" name="date_naissance" placeholder="Date de naissance" required>
                    <i class="fas fa-calendar-alt form-icon"></i>
                </div>
                <div class="form-group">
                    <input type="email" name="email_admin" placeholder="E-mail" required>
                    <i class="fas fa-envelope form-icon"></i>
                </div>
                <div class="form-group">
                    <input type="password" name="password_admin" placeholder="Mot de passe" required>
                    <i class="fas fa-lock form-icon"></i>
                </div>
                <button type="submit" class="auth-button">S'inscrire</button>
            </form>
            <p class="auth-link">Vous avez déjà un compte ? <a href="connexion.php">Connectez-vous</a></p>
        </div>
    </main>
    <script src='accueil.js'></script>

    
</body>
</html>