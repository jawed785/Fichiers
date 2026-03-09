<?php
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($login && $password) {
        // Rechercher l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_login'] = $user['login'];
            header('Location: index.php');
            exit;
        } else {
            $error = "Login ou mot de passe incorrect.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Forum</title>
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
                    <a href="register.php" class="btn btn-primary">Inscription</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container main-content">
        <div class="auth-container">
            <div class="auth-box">
                <h2>Connexion</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <form method="POST" action="login.php">
                    <div class="form-group">
                        <label for="login">Nom d'utilisateur</label>
                        <input type="text" id="login" name="login" required autofocus>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
                </form>
                
                <p class="auth-link">Pas encore de compte ? <a href="register.php">Inscrivez-vous</a></p>
                
                <div class="demo-info">
                    <p><strong>Comptes de test :</strong></p>
                    <p>Login : <code>bob</code> / Mot de passe : <code>test123</code></p>
                </div>
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