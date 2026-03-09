<?php
session_start();
include '_conf.php';

// Connexion PDO
$pdo = new PDO('mysql:host=localhost;dbname=projet_jawed;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Variable pour afficher les messages
$message = "";

// Déconnexion
if (isset($_POST['send_deco'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}

// Connexion
if (isset($_POST['send_con'])) {
    
    // Récupérer l'utilisateur par email
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$_POST['login']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Vérifier le mot de passe
        if (password_verify($_POST['motdepasse'], $user['motdepasse'])) {
            // Enregistrer les infos en session
            $_SESSION['Sid'] = $user['email'];
            $_SESSION['type'] = $user['type'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['prenom'] = $user['prenom'];

            // Redirection selon le type d'utilisateur
            switch ($user['type']) {
                case '1': // Élève
                    header("Location: page_eleve.php");
                    exit();
                    
                case '2': // Professeur
                    header("Location: page_prof.php");
                    exit();
                    
                case '3': // Administration
                    header("Location: page_admin.php");
                    exit();
                    
                case '4': // Secrétaire
                    header("Location: page_secretaire.php");
                    exit();
                    
                default:
                    $message = "⚠️ Type d'utilisateur non reconnu.";
                    break;
            }
        } else {
            $message = "❌ Mot de passe incorrect.";
        }
    } else {
        $message = "❌ Adresse email incorrecte.";
    }
}
?>