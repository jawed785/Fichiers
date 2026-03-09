<?php
session_start();
include '_conf.php';

// Connexion PDO (sans try/catch, comme tu le veux)
$pdo = new PDO('mysql:host=localhost;dbname=projet_jawed;charset=utf8', 'root', '');

// Email de l'utilisateur connecté
$email_utilisateur = $_SESSION['Sid'] ?? '';

// Variables d'affichage
$description = '';
$message = '';

// Si une date est choisie
if (!empty($_POST['date'])) {
    $date = $_POST['date'];

    // Vérifie si un compte rendu existe déjà
    $stmt = $pdo->prepare("SELECT description FROM compte_rendu WHERE date = ? AND email_utilisateur = ?");
    $stmt->execute([$date, $email_utilisateur]);
    $cr = $stmt->fetch(PDO::FETCH_ASSOC);

    // Pré-remplir la description si un CR existe
    if ($cr) $description = $cr['description'];
}
    // Si on clique sur "ENREGISTRER"
    if (isset($_POST['send_cr'])) {
        $desc = $_POST['description'];

        if ($cr) {
            // Met à jour le compte rendu
            $update = $pdo->prepare("UPDATE compte_rendu SET description = ? WHERE date = ? AND email_utilisateur = ?");
            $update->execute([$desc, $date, $email_utilisateur]);
            $message = "✅ Compte rendu mis à jour !";
            $description = $_POST ['description'];
        } else {
            // Ajoute un nouveau compte rendu
            $insert = $pdo->prepare("INSERT INTO compte_rendu (date, description, email_utilisateur) VALUES (?, ?, ?)");
            $insert->execute([$date, $desc, $email_utilisateur]);
            $message = "✅ Compte rendu ajouté !";
            $description = $_POST ['description'];
        }
    }

    if (isset($_POST['delete_cr'])) {
        $desc = $_POST['description'];

    
            $update = $pdo->prepare("DELETE FROM compte_rendu WHERE date = ? AND email_utilisateur = ?");
            $update->execute([$date, $email_utilisateur]);
            $message = "❌ Compte rendu mis à jour !";
            $description = '';

        }
    

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Compte Rendu Élève</title>
    <link rel="stylesheet" href="compterendu_eleve.css">
</head>
<body>
<header>
    <nav class="navbar">
        <div class="logo">🎓 Espace Élève</div>
        <ul class="nav-links">
            <li><a href="profil_eleve.php">Profil</a></li>
            <li><a href="compterendu_eleve.php" class="active">Compte Rendu</a></li>
            <li><a href="index.php" class="logout">Déconnexion</a></li>
        </ul>
    </nav>
</header>

<br>
<br>
<br>

<div class="important-info">
    ⚠️ Informations importantes :<br>
    - Choisissez la date pour voir ou créer un compte rendu.<br>
    - N'oubliez pas d'enregistrer vos modifications.<br>
    - Vous pouvez supprimer un compte rendu avec le bouton SUPPRIMER.
</div>


<main class="content">
    <div class="form-container">
        <h2>Créer ou Modifier un Compte Rendu</h2>

        <?php if ($message) echo "<p class='message'>$message</p>"; ?>

        <form method="POST" action="">
            <label for="date">Date :</label>
            <input type="date" id="date" name="date" value="<?php echo $_POST['date'] ?? ''; ?>" required onchange="this.form.submit()">



            <label for="description">Descriptif :</label>
            <textarea id="description" name="description" placeholder="Décris ton activité..."><?php echo htmlspecialchars($description); ?></textarea>

            <button type="submit" name="send_cr">ENREGISTRER</button>
            <button type="submit" name="delete_cr">SUPPRIMER</button>
        </form>

    </div>
</main>
</body>
</html>
