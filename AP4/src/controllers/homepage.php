<?php

//on inclu le model et toutes ses méthodes
require_once('src/model.php');

function homepage()
{
	//appel de la fonction getPosts() qui récupère les post. on stock ça dans la variable $posts
	$posts=getPosts();

	//appel de la vue
	require('templates/homepage.php');
}

?>