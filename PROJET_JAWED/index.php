<?php
session_start();
include '_conf.php';

// Connexion PDO 
$pdo = new PDO('mysql:host=localhost;dbname=projet_jawed;charset=utf8', 'root', '');

// Déconnexion si bouton "send_deco" cliqué
if (isset($_POST['send_deco'])) {
    session_destroy();
    echo "Déconnexion réussie !<br>";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="style_connexion.css">
</head>
<body>

    <form method="post" action="accueil.php">
        <h2>Connexion</h2>

        <label>Login :</label>
        <input type="text" name="login" required>

        <label>Mot de passe :</label>
        <input type="password" name="motdepasse" required>

        <button type="submit" name="send_con">Se connecter</button>

        <a href="oubli.php">Mot de passe oublié ?</a>
    </form>

</body>
</html>