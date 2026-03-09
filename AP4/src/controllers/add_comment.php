<?php
// src/controllers/add_comment.php

// On inclut le fichier model.php pour pouvoir utiliser la fonction createComment()
require_once('src/model.php');
session_start();

function addComment(string $post, array $input)
{
    // On initialise les variables auteur et commentaire
    $author = null;
    $comment = null;

    // Vérification : les champs "author" et "comment" doivent exister et ne pas être vides
    if (!empty($input['author']) && !empty($input['comment'])) {

        // Si tout est correct, on récupère les données
        $author = $input['author'];
        $comment = $input['comment'];

    } else {
        // Si les champs sont vides ou manquants, on arrête le script avec un message d'erreur
        die('Les données du formulaire sont invalides.');
    }

    // On tente d'insérer le commentaire dans la base grâce à createComment()
    $success = createComment($post, $author, $comment);

    // Si l'insertion échoue, on arrête le script et on affiche un message d'erreur
    if (!$success) {
        die('Impossible d\'ajouter le commentaire !');
    } else {
        // Si tout est OK, on redirige l'utilisateur vers la page du post concerné
        header('Location: index.php?action=post&id=' . $post);
    }
}
?>
