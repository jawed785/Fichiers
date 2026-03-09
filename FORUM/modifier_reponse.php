<?php
require_once 'config.php';

// Vérifier que l'utilisateur est connecté
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$reponse_id = $_GET['id'] ?? null;

if (!$reponse_id) {
    header('Location: index.php');
    exit;
}

// Récupérer la réponse
$stmt = $pdo->prepare("SELECT * FROM reponse WHERE r_id = ? AND user_id = ?");
$stmt->execute([$reponse_id, getUserId()]);
$reponse = $stmt->fetch();

if (!$reponse) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contenu = trim($_POST['contenu'] ?? '');
    
    if (!$contenu) {
        $error = "Veuillez remplir le champ.";
    } else {
        // Mettre à jour la réponse
        $stmt = $pdo->prepare("UPDATE reponse SET r_contenu = ? WHERE r_id = ? AND user_id = ?");
        
        if ($stmt->execute([$contenu, $reponse_id, getUserId()])) {
            header('Location: question.php?id=' . $reponse['r_fk_question_id']);
            exit;
        } else {
            $error = "Une erreur est survenue lors de la modification.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la réponse - Forum</title>
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
        <a href="question.php?id=<?php echo $reponse['r_fk_question_id']; ?>" class="back-link">← Retour à la question</a>
        
        <div class="form-container">
            <h2>Modifier la réponse</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="modifier_reponse.php?id=<?php echo $reponse_id; ?>">
                <div class="form-group">
                    <label for="contenu">Contenu de la réponse</label>
                    <textarea id="contenu" name="contenu" rows="8" maxlength="150" required autofocus><?php echo htmlspecialchars($reponse['r_contenu']); ?></textarea>
                    <small>Maximum 150 caractères</small>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    <a href="question.php?id=<?php echo $reponse['r_fk_question_id']; ?>" class="btn btn-secondary">Annuler</a>
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