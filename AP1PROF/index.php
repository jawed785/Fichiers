<?php
session_start();
include '_conf.php';

$erreur = '';

if (isset($_POST['envoi'])) {
    $login = trim($_POST['login']);
    $mdp = trim($_POST['mdp']);
    
    // Connexion à la base
    $connexion = mysqli_connect($serveurBDD, $userBDD, $mdpBDD, $nomBDD);
    
    if ($connexion) {
        // Recherche de l'utilisateur
        $requete = "SELECT * FROM utilisateur WHERE login = ?";
        $stmt = mysqli_prepare($connexion, $requete);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $login);
            mysqli_stmt_execute($stmt);
            $resultat = mysqli_stmt_get_result($stmt);
            
            if ($donnees = mysqli_fetch_assoc($resultat)) {
                // Vérification du mot de passe
                if (password_verify($mdp, $donnees['motdepasse'])) {
                    // Connexion réussie
                    $_SESSION['id'] = $donnees['num'];
                    $_SESSION['login'] = $donnees['login'];
                    $_SESSION['type'] = $donnees['type'];
                    $_SESSION['nom'] = $donnees['nom'];
                    $_SESSION['prenom'] = $donnees['prenom'];
                    
                    // Redirection vers l'accueil
                    header('Location: accueil.php');
                    exit();
                } else {
                    $erreur = "Mot de passe incorrect";
                }
            } else {
                $erreur = "Login incorrect";
            }
            
            mysqli_stmt_close($stmt);
        } else {
            $erreur = "Erreur de préparation de la requête";
        }
        
        mysqli_close($connexion);
    } else {
        $erreur = "Erreur de connexion à la base de données";
    }
}

// Déconnexion
if (isset($_GET['deco'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi des Stages - Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav class="menu">
            <div class="logo">Suivi Stages</div>
            <div class="menu-buttons">
                <button class="btn-menu" onclick="window.location.href='oubli.php'">Mot de passe oublié</button>
            </div>
        </nav>
    </header>

    <main>
        <section class="form-container">
            <h2>Connexion</h2>
            
            <?php if ($erreur): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($erreur); ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="">
                <div class="form-group">
                    <label>Login :</label>
                    <input type="text" name="login" required placeholder="Votre login">
                </div>
                <div class="form-group">
                    <label>Mot de passe :</label>
                    <input type="password" name="mdp" required placeholder="Votre mot de passe">
                </div>
                <button type="submit" class="btn-submit" name="envoi" value="1">Se connecter</button>
            </form>
            
            <div class="test-accounts">
                <h3>Comptes de test (mot de passe : test123)</h3>
                <ul>
                    <li><strong>Élève :</strong> jean.dupont</li>
                    <li><strong>Professeur :</strong> pierre.durand</li>
                    <li><strong>Secrétaire :</strong> sophie.leroy</li>
                    <li><strong>Administration :</strong> admin</li>
                </ul>
            </div>
        </section>
    </main>
</body>
</html>