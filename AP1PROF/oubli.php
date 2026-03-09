﻿<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require __DIR__ . '/phpmailer/Exception.php';
require __DIR__ . '/phpmailer/PHPMailer.php';
require __DIR__ . '/phpmailer/SMTP.php';

include '_conf.php';

// Fonction pour générer un mot de passe aléatoire
function genererMotDePasse($longueur = 10) {
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=';
    $motDePasse = '';
    for ($i = 0; $i < $longueur; $i++) {
        $motDePasse .= $caracteres[random_int(0, strlen($caracteres) - 1)];
    }
    return $motDePasse;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $login = trim($_POST['login'] ?? '');

    if ($email && $login) {
        $connexion = mysqli_connect($serveurBDD, $userBDD, $mdpBDD, $nomBDD);
        if (!$connexion) {
            $message = "Erreur de connexion à la base : " . mysqli_connect_error();
        } else {
            // Requête sécurisée
            $sql = "SELECT * FROM utilisateur WHERE email = ? AND login = ?";
            if ($stmt = mysqli_prepare($connexion, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $email, $login);
                mysqli_stmt_execute($stmt);
                $resultat = mysqli_stmt_get_result($stmt);

                if ($donnees = mysqli_fetch_assoc($resultat)) {
                    // Générer et hasher le nouveau mot de passe
                    $newmdp = genererMotDePasse();
                    $newmdphash = password_hash($newmdp, PASSWORD_BCRYPT);

                    // Mettre à jour le mot de passe
                    $sqlUpdate = "UPDATE utilisateur SET motdepasse = ? WHERE email = ? AND login = ?";
                    if ($stmtUpdate = mysqli_prepare($connexion, $sqlUpdate)) {
                        mysqli_stmt_bind_param($stmtUpdate, "sss", $newmdphash, $email, $login);
                        mysqli_stmt_execute($stmtUpdate);
                        mysqli_stmt_close($stmtUpdate);

                        // Envoi du mail
                        $mail = new PHPMailer(true);
                        try {
                            // Configuration SMTP - ESSAYEZ LES DIFFÉRENTES OPTIONS CI-DESSOUS
                            
                            // OPTION 1: Configuration de base (essayez d'abord celle-ci)
                            $mail->isSMTP();
                            $mail->Host = 'smtp.hostinger.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'contact@siolapie.com';
                            $mail->Password = 'EmailL@pie25';
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // ou PHPMailer::ENCRYPTION_SMTPS
                            $mail->Port = 587; // ou 465 pour SSL
                            
                            // OPTION 2: Désactiver la vérification SSL (pour le développement)
                            // Décommentez ces lignes si l'option 1 ne fonctionne pas
                            
                            $mail->SMTPOptions = [
                                'ssl' => [
                                    'verify_peer' => false,
                                    'verify_peer_name' => false,
                                    'allow_self_signed' => true
                                ]
                            ];
                            
                            
                            // OPTION 3: Configuration alternative (décommentez si les autres ne fonctionnent pas)
                            /*
                            $mail->isSMTP();
                            $mail->Host = 'smtp.hostinger.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'contact@siolapie.com';
                            $mail->Password = 'EmailL@pie25';
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL au lieu de TLS
                            $mail->Port = 465; // Port SSL
                            */
                            
                            // OPTION 4: Debug mode (utile pour voir les erreurs détaillées)
                            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                            
                            $mail->setFrom('contact@siolapie.com', 'CONTACT SIOSLAM');
                            $mail->addAddress($email);
                            $mail->isHTML(true);
                            $mail->Subject = 'Nouveau mot de passe - Suivi Stages';
                            $mail->Body = "
                                <html>
                                <head>
                                    <style>
                                        body { font-family: Arial, sans-serif; line-height: 1.6; }
                                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                                        .header { background-color: #4CAF50; color: white; padding: 15px; text-align: center; }
                                        .content { padding: 20px; background-color: #f9f9f9; }
                                        .password { font-size: 18px; font-weight: bold; color: #d32f2f; background-color: #ffebee; padding: 10px; border-radius: 5px; }
                                        .footer { margin-top: 20px; padding-top: 10px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
                                    </style>
                                </head>
                                <body>
                                    <div class='container'>
                                        <div class='header'>
                                            <h2>Suivi des Stages - Récupération de mot de passe</h2>
                                        </div>
                                        <div class='content'>
                                            <p>Bonjour,</p>
                                            <p>Vous avez demandé la réinitialisation de votre mot de passe.</p>
                                            <p>Voici votre nouveau mot de passe :</p>
                                            <div class='password'>$newmdp</div>
                                            <p style='margin-top: 20px;'>
                                                <strong>Conseil de sécurité :</strong> Connectez-vous dès que possible et modifiez ce mot de passe dans votre profil.
                                            </p>
                                            <p>
                                                <a href='http://votre-site.com/accueil.php' style='background-color: #4CAF50; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; display: inline-block;'>
                                                    Se connecter
                                                </a>
                                            </p>
                                        </div>
                                        <div class='footer'>
                                            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
                                            <p>Si vous n'avez pas demandé cette réinitialisation, veuillez ignorer cet email.</p>
                                        </div>
                                    </div>
                                </body>
                                </html>
                            ";
                            
                            // Version texte pour les clients qui ne supportent pas HTML
                            $mail->AltBody = "Nouveau mot de passe pour votre compte Suivi Stages : $newmdp\n\nConnectez-vous à : http://votre-site.com/accueil.php";

                            if ($mail->send()) {
                                $message = "✅ Un nouveau mot de passe a été envoyé à votre adresse email.";
                            } else {
                                $message = "⚠️ Erreur lors de l'envoi de l'email.";
                            }
                            
                        } catch (Exception $e) {
                            $message = "⚠️ Mot de passe mis à jour, mais erreur d'envoi de mail : " . $mail->ErrorInfo;
                            
                            // Optionnel : sauvegarder le mot de passe dans un fichier log en cas d'échec d'envoi
                            // (UNIQUEMENT pour le développement - à désactiver en production)
                            // error_log("Nouveau mot de passe pour $email : $newmdp");
                        }

                    } else {
                        $message = "Erreur SQL update : " . mysqli_error($connexion);
                    }

                } else {
                    $message = "❌ Utilisateur introuvable avec cet email et login.";
                }

                mysqli_stmt_close($stmt);
            } else {
                $message = "Erreur SQL select : " . mysqli_error($connexion);
            }

            mysqli_close($connexion);
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <style>
        .instructions {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .instructions h3 {
            margin-top: 0;
            color: #007bff;
        }
        .error-box {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .success-box {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .debug-info {
            font-size: 12px;
            color: #666;
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 3px;
            margin-top: 10px;
            border-left: 3px solid #ffc107;
        }
    </style>
</head>
<body>
<header>
    <nav class="menu">
        <div class="logo">Suivi Stages</div>
        <div class="menu-buttons">
            <button class="btn-menu" onclick="window.location.href='index.php'">Retour Index</button>
            <button class="btn-menu" onclick="window.location.href='accueil.php'">Connexion</button>
        </div>
    </nav>
</header>

<main>
    <section id="formulaire" class="form-container">
        <h2>🔑 Mot de passe oublié</h2>
        
        <div class="instructions">
            <h3>Comment récupérer votre mot de passe ?</h3>
            <p>1. Entrez votre adresse email et votre login</p>
            <p>2. Un nouveau mot de passe sera généré et envoyé par email</p>
            <p>3. Connectez-vous avec ce nouveau mot de passe</p>
            <p>4. Modifiez-le immédiatement dans votre profil</p>
        </div>
        
        <?php if ($message): ?>
            <div class="<?php echo strpos($message, '✅') !== false ? 'success-box' : 'error-box'; ?>">
                <?= htmlspecialchars($message) ?>
                <?php if (strpos($message, 'SMTP Error') !== false): ?>
                    <div class="debug-info">
                        <strong>Dépannage :</strong><br>
                        1. Vérifiez vos identifiants SMTP<br>
                        2. Essayez le port 465 avec SSL<br>
                        3. Contactez votre hébergeur pour les paramètres SMTP
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label>📧 Email :</label>
                <input type="email" name="email" required placeholder="votre@email.com">
            </div>
            <div class="form-group">
                <label>👤 Login :</label>
                <input type="text" name="login" required placeholder="Votre nom d'utilisateur">
            </div>
            <button type="submit" class="btn-submit">Recevoir un nouveau mot de passe</button>
        </form>
        
        <div style="margin-top: 20px; text-align: center;">
            <a href="accueil.php" style="color: #007bff;">↩️ Retour à la connexion</a>
        </div>
    </section>
</main>

<script>
    // Ajouter un indicateur de chargement
    document.querySelector('form')?.addEventListener('submit', function() {
        const button = this.querySelector('.btn-submit');
        if (button) {
            button.innerHTML = 'Envoi en cours...';
            button.disabled = true;
        }
    });
</script>
</body>
</html>