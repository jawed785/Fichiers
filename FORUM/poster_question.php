<?php
require_once 'config.php';

// Vérifier que l'utilisateur est connecté
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    
    if (!$titre || !$contenu) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        // Insérer la question
        $date = date('Y-m-d H:i');
        $stmt = $pdo->prepare("INSERT INTO question (q_date_ajout, q_titre, q_contenu, user_id) VALUES (?, ?, ?, ?)");
        
        if ($stmt->execute([$date, $titre, $contenu, getUserId()])) {
            $question_id = $pdo->lastInsertId();
            header('Location: question.php?id=' . $question_id);
            exit;
        } else {
            $error = "Une erreur est survenue lors de la publication.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poser une question - Forum</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <h1 class="logo"><a href="index.php">Forum Questions</a></h1>
                <nav class="nav">
                    <span class="user-info">Bonjour, <?php echo htmlspecialchars(getUserLogin()); ?></span>
                    <a href="index.php" class="btn btn-secondary">Accueil</a>
                    <a href="logout.php" class="btn btn-secondary">Déconnexion</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container main-content">
        <a href="index.php" class="back-link">← Retour aux questions</a>
        
        <div class="form-container">
            <h2>Poser une nouvelle question</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="poster_question.php">
                <div class="form-group">
                    <label for="titre">Titre de la question</label>
                    <input type="text" id="titre" name="titre" maxlength="50" required autofocus>
                    <small>Maximum 50 caractères</small>
                </div>
                
                <div class="form-group">
                    <label for="contenu">Description détaillée</label>
                    <textarea id="contenu" name="contenu" rows="8" maxlength="150" required></textarea>
                    <small>Maximum 150 caractères</small>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Publier la question</button>
                    <a href="index.php" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 Forum Questions. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>