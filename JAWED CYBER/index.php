<?php

session_start();

include '_conf_cb.php';
$pdo = new pdo('mysql:host=localhost;dbname=td4_bloc3','root','');

?>

<?php

if ($pdo)
{
    //SI la connexion a réussi
    echo "connexion BDD réussi !"; 
}
else
{
    echo 'Erreur'; //on affiche un message d'erreur
}

?> 


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <br>
    <title>Page de connexion:</title>
    <br>
    <form method="post" action="accueil.php">
    <label>Login:</label>
    <input type="text" name="login" required>
    <br>
    <label>Mot de passe:</label>
    <input type="password" name="motdepasse" required>
    <br>
    <input type="submit" name="send_con" value="Se connecter">
    </form>
    
</head>

<body>
    
</body>
</html>


