
  
        <head>
            <title>SportLock - Résultat de votre inscription </title>
            <link rel='stylesheet' href='styles.css'>




        </head>
        
            <?php $nom = $_POST['nom_user']; 
            
            $nom_event= $_POST['nom_event']; 
            $lieu_event= $_POST['lieu_event']; 
            $long_event= $_POST['long_event'];
            $lat_event= $_POST['lat_event']; 
            $date_event= $_POST['date_event'];
            $prix_event= $_POST['prix_event'];
            $description_event= $_POST['description_event'];
            $url_event= $_POST['url_event'];
            $categorie_event= $_POST['categorie_event'];
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

                $insererUser = 'INSERT INTO events(nom_event, lieu_event, long_event, lat_event, date_event, prix_event, description_event, url_event, categorie_event) VALUES
                (:nom_event, :lieu_event, :long_event, :lat_event, :date_event, :prix_event, :description_event, :url_event, :categorie_event)';
                $insererUser = $bdd->prepare($insererUser);
                $insererUser->execute([ ":nom_event"=> $nom_event , ':lieu_event' => $lieu_event, ':long_event' => $long_event, ':lat_event' => $lat_event,
                 ':date_event' => $date_event, ':prix_event' => $prix_event, ':description_event' => $description_event, ':url_event' => $url_event, ':categorie_event' => $categorie_event ]);
                 header("Location: account.php");
                 exit;
                 
            ?>