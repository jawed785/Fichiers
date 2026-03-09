<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP</title>
    <link rel="stylesheet" href="style.css"> <!-- Correction de la balise pour le CSS -->
</head>

<body>

    <form action="accuse_reception.php" method="post">
        <div class="form-group">
            <!-- données à faire passer à l'aide de champs input -->
            <label for="nom">Nom</label><input name="nom" required>
            <br />
            <label for="prenom">Prénom</label><input name="prenom" required>
            <br />
            <label for="courriel">Courriel</label>
            <input type="email" name="courriel" required>
            <br />
            <label for="motpasse">Mot de passe</label>
            <input type="password" name="motpasse" required>
            <br />
            <label for="age">Age</label>
            <input type="number" name="age" required min="0">
        </div>
        <div>
            <br />
            <label for="message">Votre message</label>
            <textarea placeholder="Exprimez-vous" name="message" required></textarea>
        </div>
        <br />
        <button type="submit">Envoyer</button>
    </form>

    <?php
    // Ouverture du script PHP

    $age = 18; // Définition d'une variable
    $nom = "Ethan";
    // echo "Bonjour $nom, tu as $age ans."; // Affichage d'une phrase avec variables

    $utilisateur = ["Tony", "tony@gmail.com", 18]; // Tableau avec des informations d'utilisateur
    // echo $utilisateur[0]; // Affichage d'un élément spécifique du tableau

    $michael = ["Michael", "michael@", 25];
    $mathieu = ["Mathieu", "mathieu@", 30];
    $utilisateurs = [$michael, $mathieu]; // Tableau de tableaux
    // echo $utilisateurs[1][1]; // Affichage d'un élément spécifique dans un tableau de tableaux

    /* Exemple de boucle while :
    
    $ligne = 1;
    while ($ligne < 100) {
        echo $ligne . '<br>';
        $ligne = $ligne + 1;
    }
    */

    /* Exemple de boucle for :
    
    for ($i = 0; $i < 100; $i++) {
        echo $i . '<br>';
    }
    */

    /* Exemple de condition :
    
    if ($age >= 18) {
        echo "Vous êtes majeur";
    } else {
        echo "Vous êtes mineur";
    }
    */

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=bdd;charset=utf8', 'root', 'root'); // Connexion à la base de données
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage()); // Gestion d'erreur
    }

    // Sélection des utilisateurs dans la base de données
    $requetePreparee = $bdd->prepare('SELECT * FROM utilisateurs');
    $requetePreparee->execute();
    $utilisateurs = $requetePreparee->fetchAll();

    // Sélection des articles dans la base de données
    $requetePreparee = $bdd->prepare('SELECT * FROM articles WHERE etat="actif" AND titre="Voyage"');
    $requetePreparee->execute();
    $articles = $requetePreparee->fetchAll();

    // Affichage des utilisateurs
    foreach ($utilisateurs as $utilisateur) {
        echo "<p>" . htmlspecialchars($utilisateur['nom']) . "</p>";
    }

    // Affichage des articles
    foreach ($articles as $article) {
        echo "<p>" . htmlspecialchars($article['titre']) . "</p>";
    }

    ?>
</body>

</html>
