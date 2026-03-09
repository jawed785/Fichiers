<?php
include '_conf.php';
session_start();

// Connexion à la base de données
$bdd = mysqli_connect($serveurBDD, $userBDD, $mdpBDD, $nomBDD);
if (!$bdd) {
    die("Erreur de connexion à la BDD : " . mysqli_connect_error());
}

// Variable pour afficher le message
$message = "";

// Vérifie si le formulaire a été soumis
if (isset($_POST["send_cr"])) {
    $date = $_POST['date'];
    $description = $_POST['description'];
    $email_utilisateur = $_SESSION['Sid']; // email du professeur

    // Insertion simple dans la BDD
    $sql = "INSERT INTO compte_rendu (date, description, email_utilisateur)
            VALUES ('$date', '$description', '$email_utilisateur')";

    if (mysqli_query($bdd, $sql)) {
        $message = "<p style='color:lime;'>✅ Compte rendu enregistré avec succès !</p>";
    } else {
        $message = "<p style='color:red;'>❌ Erreur lors de l'enregistrement : " . mysqli_error($bdd) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte Rendu Professeur</title>
    <link rel="stylesheet" href="compterendu_prof.css">
</head>
<body>

    <header>
        <nav class="navbar">
            <div class="logo">👨‍🏫 Espace Professeur</div>
            <ul class="nav-links">
                <li><a href="profil_prof.php">Profil</a></li>
                <li><a href="compterendu_prof.php" class="active">Compte Rendu</a></li>
                <li><a href="index.php" class="logout">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main class="content">
        <div class="form-container">
            <h2>Créer un Compte Rendu</h2>

            <form method="POST" action="">
                <label for="date">Date :</label>
                <input type="date" id="date" name="date" required>

                <label for="description">Descriptif :</label>
                <textarea id="description" name="description" placeholder="Décris ton activité..." required></textarea>

                <button type="submit" name="send_cr">ENREGISTRER</button>
            </form>
        </div>

        <!-- Message affiché en bas -->
        <div class="message">
            <?php if(!empty($message)) echo $message; ?>
        </div>
    </main>

</body>
</html>