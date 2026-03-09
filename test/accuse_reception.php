<h1>Message bien reçu !</h1>
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Rappel de vos informations</h5>
        <p class="card-text"><b>Prénom</b> : <?php echo htmlspecialchars($_POST['prenom']); ?></p>
        <p class="card-text"><b>Nom</b> : <?php echo htmlspecialchars($_POST['nom']); ?></p>
        <p class="card-text"><b>Age</b> : <?php echo htmlspecialchars($_POST['age']); ?></p>
        <p class="card-text"><b>Email</b> : <?php echo htmlspecialchars($_POST['courriel']); ?></p>
        <p class="card-text"><b>Mot de passe</b> : <?php echo htmlspecialchars($_POST['motpasse']); ?></p>
        <p class="card-text"><b>Message que vous avez écrit</b> : <?php echo htmlspecialchars($_POST['message']); ?></p>
    </div>
</div>

<?php
// Ouverture du script PHP
if (!isset($_POST['prenom']) || !isset($_POST['nom']) || !isset($_POST['motpasse'])) {
    echo 'Il faut un prénom, un nom et un mot de passe pour soumettre le formulaire.';
    return;
}

try {
    $bdd = new PDO('mysql:host=localhost;dbname=bdd;charset=utf8', 'root', 'root'); // Connexion à la base de données
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insertion de l'utilisateur
    $sqlQuery = 'INSERT INTO utilisateurs (prenom, nom, email, mdp, age) VALUES (:prenom, :nom, :email, :mdp, :age)';
    $insererUser = $bdd->prepare($sqlQuery);
    $insererUser->execute([
        'prenom' => $_POST['prenom'],
        'nom' => $_POST['nom'],
        'email' => $_POST['courriel'],
        'mdp' => $_POST['motpasse'],
        'age' => $_POST['age']
    ]);

    // Mise à jour de l'utilisateur (assurez-vous que la colonne 'email' existe)
    $sqlQuery = 'UPDATE utilisateurs SET email=:email WHERE id_user=1';
    $updateUser = $bdd->prepare($sqlQuery);
    $updateUser->execute([
        'email' => $_POST['courriel']
    ]);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>
