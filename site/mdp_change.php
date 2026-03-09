<h1>Message bien reçu !</h1>
<div class="card">
<div class="card-body">
<h5 class="card-title">Votre compte a bien été crée</h5>

<p class="card-text"><b> Nouvelle date d'inscription (dd/mm/yyyy)</b> : <?php echo $_POST['date_inscription']; ?> </p>
</div>
</div>

<?php
    try {
        // On se connecte à MySQL
        $bdd = new PDO('mysql:host=localhost;dbname=fac;', 'root', 'root');
        }
        catch(Exception $e) {
        // En cas d'erreur, on affiche un message et on arrête tout
        die('Erreur : '.$e->getMessage());
        }

    $requeteSQL = 'UPDATE eleve SET date_inscription = :nvdate_inscription WHERE id_eleve=:id_eleve';
    $insererUser = $bdd->prepare($requeteSQL);
    $insererUser->execute([ ":id_eleve"=>5 , ':nvdate_inscription' => $nvdate_inscription]);
    
    
   