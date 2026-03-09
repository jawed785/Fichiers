<?php
session_start();
include '_conf_cb.php';

$pdo = new PDO('mysql:host=localhost;dbname=td4_bloc3;charset=utf8','root','');
echo "Connexion BDD réussie !<br>";

if (isset($_POST['send_con'])) {
    $lemail = $_POST['login'];
    $mdp = $_POST['motdepasse'];

    //REQUETE PREPARE
    $stmt = $pdo->prepare('SELECT * FROM acheteur WHERE email = ? AND pwd = ?'); //PREPARATION
    $stmt->execute([$lemail, $mdp]); //EXECUTION
    $user = $stmt->fetch(); // récupère une seule ligne

    if ($user) {

    
        echo "Votre email a bien été trouvé !<br>";
        echo "Nom : " . $user['nom'] . "<br>";
        echo "Prenom : " . $user['prenom'] . "<br>";
        echo "Email : " . $user['email'] . "<br>";
        echo "Mdp : " . $user['pwd'] . "<br>";

        ?> <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
</body>
</html><?php
    
        // $_SESSION['Sid'] = $lemail;
    } else {
        echo "EMAIL NON TROUVÉ";
    }
}
?>
 