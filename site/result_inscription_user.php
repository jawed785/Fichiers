<?php
session_start();

// Initialisation
$erreur = "";

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données
    $nom = $_POST['nom_user'] ?? '';
    $prenom = $_POST['prenom_user'] ?? '';
    $email = $_POST['email_user'] ?? '';
    $mdp = $_POST['mdp_user'] ?? '';
    $date_naissance = $_POST['date_naissance'] ?? '';
    $role = 'utilisateur';

    // Vérification du mot de passe
    if (strlen($mdp) < 8) {
        $erreur = "Le mot de passe doit contenir au moins 8 caractères.";
    } else {
        // Connexion à la base de données
        try {
            $bdd = new PDO('mysql:host=localhost;dbname=site_event;', 'root', 'root');
        } catch (Exception $e) {
            die('Erreur : '.$e->getMessage());
        }

        // Hash du mot de passe
        $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

        // Insertion des données
        $insererUser = $bdd->prepare('INSERT INTO utilisateur (nom_utilisateur, prenom_utilisateur, email, mdp, date_naissance, role_utilisateur)
                                      VALUES (:nom, :prenom, :email, :mdp, :date_naissance, :role)');
        $insererUser->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':mdp' => $mdp_hash,
            ':date_naissance' => $date_naissance,
            ':role' => $role
        ]);

        // Redirection après succès
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription utilisateur</title>
    <link rel="stylesheet" href="styles.css">
    
</head>
<body>


<h2>Inscription Utilisateur</h2>

<?php if (!empty($erreur)): ?>
    <div style="color: red; font-weight: bold;"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<form action="" method="post">
    <input type="text" name="prenom_user" placeholder="Prénom" required><br>
    <input type="text" name="nom_user" placeholder="Nom" required><br>
    <input type="date" name="date_naissance" placeholder="Date de naissance" required><br>
    <input type="email" name="email_user" placeholder="E-mail" required><br>
    <input type="password" name="mdp_user" placeholder="Mot de passe" required><br>
    <button type="submit">S'inscrire</button>
</form>

</body>
</html>
