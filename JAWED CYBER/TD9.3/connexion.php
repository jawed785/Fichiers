<?php
$email=$_POST["email"];
$mdp=$_POST["mdp"];
try
{
	// On se connecte à MySQL
	$pdo = new pdo('mysql:host=localhost;dbname=td8_bloc3', 'root', '');
	
	$stmt=$pdo->query("select * from acheteur where email ='$email' and motdepasse = '$mdp'");
	$res=0;
	while($row=$stmt->fetch())
	{
		$res=1;
		$CB=$row['numeroCB'];
	}
	if($res==0)
		echo "echec connexion : login/mdp non trouvé";
	else
		echo "numéro CB=".$CB;
	
}
catch(Exception $e)
{
	// En cas d'erreur, on affiche un message et on arrête tout
        die('Erreur : '.$e->getMessage());
}

?>