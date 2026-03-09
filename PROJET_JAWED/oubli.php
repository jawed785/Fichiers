<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Chargement des classes PHPMailer
require __DIR__ . '/phpmailer/Exception.php';
require __DIR__ . '/phpmailer/PHPMailer.php';
require __DIR__ . '/phpmailer/SMTP.php';

$mail = new PHPMailer(true); // Instance PHPMailer
?>

<?php
include '_conf.php';

// Génère un mot de passe aléatoire sécurisé
function genererChaineAleatoire($longueur = 10) {
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()=+[]{}';
    $chaineAleatoire = '';
    $longueurCaracteres = strlen($caracteres);

    // Construction caractère par caractère
    for ($i = 0; $i < $longueur; $i++) {
        $indexAleatoire = random_int(0, $longueurCaracteres - 1);
        $chaineAleatoire .= $caracteres[$indexAleatoire];
    }
    return $chaineAleatoire;
}

// Vérifie si un email a été soumis
if(isset($_POST['email'])) {
    $lemail = $_POST['email'];
    echo $lemail;

    // Connexion à la base
    $bdd = mysqli_connect($serveurBDD, $userBDD, $mdpBDD, $nomBDD);

    // Recherche l'utilisateur correspondant à l’email
    $requete = "Select * from utilisateur WHERE email= '$lemail'";
    $resultat = mysqli_query($bdd, $requete);
    $mdp = "0";

    // Récupération du mot de passe existant
    while($donnees = mysqli_fetch_assoc($resultat)) {
        $mdp = $donnees['motdepasse'];
    }

    // Aucun compte trouvé
    if($mdp == "0") {
        echo " Erreur d'envoie d'email";
    } else {
        echo " Votre email a bien été envoyéeee !";

        // Nouveau mot de passe généré
        $newmdp = genererChaineAleatoire(10);
        echo "<hr>$newmdp</hr>";

        // Hash MD5 du nouveau mot de passe
        $mdphash = md5($newmdp);

        // Mise à jour du mot de passe
        $requete2 = "UPDATE `utilisateur` SET `motdepasse` = '$mdphash' WHERE `utilisateur`.`email` = '$lemail';";
        if (!mysqli_query($bdd, $requete2)) {
            echo "<br>Erreur : ".mysqli_error($connexion)."<br>";
        }

        // Tentative d’envoi du mail
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.hostinger.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'contact@sioslam.fr';
            $mail->Password   = '&5&Y@*QHb';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('contact@sioslam.fr', 'CONTACT SIOSLAM');
            $mail->addAddress($lemail, 'Moi');

            $mail->isHTML(true);
            $mail->Subject = 'Nouveau Mot de Passe';

            // Contenu de l'e-mail avec le nouveau mot de passe généré
            $mail->Body = 'Bonjour Monsieur, Madame,

                            Suite à votre demande de réinitialisation, voici votre nouveau mot de passe :

                            🔐 Votre Nouveau Mot de passe : '.$newmdp.'

                            Nous vous recommandons de vous connecter dès que possible et de modifier ce mot de passe depuis votre espace personnel pour garantir la sécurité de votre compte.

                            Si vous n’êtes pas à l’origine de cette demande, veuillez nous contacter immédiatement.

                            Cordialement,

                            L’équipe de récupération';

            $mail->AltBody = '';
            $mail->send(); // Envoi
            echo "✅ Email envoyé avec succès !";

        } catch (Exception $e) {
            echo "❌ Erreur d'envoi : {$mail->ErrorInfo}";
        }
    }
} else {
?>
<form method="post">
    <label>Email</label>
    <input type="text" name="email" required>
    <input type="submit" value="Comfirmer">
</form>
<?php
}
?>
</body>
</html>
