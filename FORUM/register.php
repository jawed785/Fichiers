<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $date_naissance = $_POST['date_naissance'] ?? '';
    
    // Validation
    if (!$login || !$password || !$password_confirm || !$date_naissance) {
        $error = "Veuillez remplir tous les champs.";
    } elseif ($password !== $password_confirm) {
        $error = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        // Vérifier si le login existe déjà
        $stmt = $pdo->prepare("SELECT user_id FROM utilisateur WHERE login = ?");
        $stmt->execute([$login]);
        
        if ($stmt->fetch()) {
            $error = "Ce nom d'utilisateur est déjà pris.";
        } else {
            // Créer le compte
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO utilisateur (login, password, date_naissance) VALUES (?, ?, ?)");
            
            if ($stmt->execute([$login, $password_hash, $date_naissance])) {
                $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
            } else {
                $error = "Une erreur est survenue lors de la création du compte.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Forum</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <h1 class="logo"><a href="index.php">Forum Questions</a></h1>
                <nav class="nav">
                    <a href="index.php" class="btn btn-secondary">Accueil</a>
                    <a href="login.php" class="btn btn-primary">Connexion</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container main-content">
        <div class="auth-container">
            <div class="auth-box">
                <h2>Inscription</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($success); ?>
                        <p><a href="login.php">Se connecter maintenant</a></p>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="register.php">
                    <div class="form-group">
                        <label for="login">Nom d'utilisateur</label>
                        <input type="text" id="login" name="login" required autofocus>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" required>
                        <small>Au moins 6 caractères</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirm">Confirmer le mot de passe</label>
                        <input type="password" id="password_confirm" name="password_confirm" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="date_naissance">Date de naissance</label>
                        <input type="date" id="date_naissance" name="date_naissance" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
                </form>
                
                <p class="auth-link">Déjà un compte ? <a href="login.php">Connectez-vous</a></p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 Forum Questions. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>