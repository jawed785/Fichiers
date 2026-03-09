<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/phpmailer/Exception.php';
require __DIR__ . '/phpmailer/PHPMailer.php';
require __DIR__ . '/phpmailer/SMTP.php';

$mail = new PHPMailer(true);
?>

<?php
include '_conf.php';

function genererChaineAleatoire($longueur = 10) {
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()=+[]{}';
    $chaineAleatoire = '';
    $longueurCaracteres = strlen($caracteres);
    for ($i = 0; $i < $longueur; $i++) {
        $indexAleatoire = random_int(0, $longueurCaracteres - 1);
        $chaineAleatoire .= $caracteres[$indexAleatoire];
    }
    return $chaineAleatoire;
}
$prenom= "personne";
if(isset($_POST['email'])) {
    $lemail = $_POST['email'];
    echo $lemail;

    $bdd = mysqli_connect($serveurBDD, $userBDD, $mdpBDD, $nomBDD);
    $requete = "Select * from utilisateur WHERE email= '$lemail'";
    $resultat = mysqli_query($bdd, $requete);
    $mdp = "0";

    while($donnees = mysqli_fetch_assoc($resultat)) {
        $mdp = $donnees['motdepasse'];
        $num=$donnees['num'];
        $nom=$donnees['nom'];
        $prenom=$donnees['prenom'];
        $type=$donnees['type'];
        $login=$donnees['login'];
        
    }

    if($mdp == "0") {
        echo " Erreur d'envoie d'email";
    } else {
        echo " Votre email a bien été envoyéeee !";
        $newmdp = genererChaineAleatoire(10);
        echo "<hr>$newmdp</hr>";
        $mdphash = md5($newmdp);
        $requete2 = "UPDATE `utilisateur` SET `motdepasse` = '$mdphash' WHERE `utilisateur`.`email` = '$lemail';";
        if (!mysqli_query($bdd, $requete2)) {
            echo "<br>Erreur : ".mysqli_error($connexion)."<br>";
        }

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

            $mail->Body = 'Bonjour Monsieur, Madame,

                            Suite à votre demande de réinitialisation, voici votre nouveau mot de passe :

                            🔐 Votre Nouveau Mot de passe : '.$newmdp.'

                            Nous vous recommandons de vous connecter dès que possible et de modifier ce mot de passe depuis votre espace personnel pour garantir la sécurité de votre compte.

                            Si vous n’êtes pas à l’origine de cette demande, veuillez nous contacter immédiatement.

                            Cordialement,

                            L’équipe de récupération';

            $mail->AltBody = '';
            $mail->send();
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
    <input type="submit" value="Comfffffirmer">
    <hr>
    <p>Bonjour <?php echo $prenom; ?> !</p>
</form>
<?php
}
?>
</body>
</html>
