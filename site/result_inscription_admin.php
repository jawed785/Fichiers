
  
        <head>
            <title>SportLock - Résultat de votre inscription </title>
            <link rel='stylesheet' href='styles.css'>




        </head>
        
            <?php $nom = $_POST['nom_user']; 
            
            $prenom= $_POST['prenom_user']; 
            $email= $_POST['email_user']; 
            $mdp= $_POST['mdp_user'];
            $date_naissance= $_POST['date_naissance']; 
            $role='admin';
            ?>

            <?php
                try {
                    // On se connecte à MySQL
                    $bdd = new PDO('mysql:host=localhost;dbname=site_event;', 'root', 'root');
                    }
                    catch(Exception $e) {
                    // En cas d'erreur, on affiche un message et on arrête tout
                    die('Erreur : '.$e->getMessage());
                    }

                $insererUser = 'INSERT INTO utilisateur(nom_utilisateur, prenom_utilisateur, email, mdp, date_naissance, role_utilisateur) VALUES
                (:nom_utilisateur, :prenom_utilisateur, :email, :mdp, :date_naissance, :role_utilisateur)';
                $insererUser = $bdd->prepare($insererUser);
                $insererUser->execute([ ":nom_utilisateur"=> $nom , ':prenom_utilisateur' => $prenom, ':email' => $email, ':mdp' => $mdp,
                 ':date_naissance' => $date_naissance, ':role_utilisateur' => $role ]);
                 header("Location: login.php");
                 exit;
                 
            ?>