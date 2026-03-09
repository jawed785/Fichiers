<?php
require_once 'config.php';

// Récupérer toutes les questions avec le nom de l'auteur
$stmt = $pdo->query("
    SELECT q.*, u.login 
    FROM question q 
    LEFT JOIN utilisateur u ON q.user_id = u.user_id 
    ORDER BY q.q_id DESC
");
$questions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum - Toutes les questions</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <h1 class="logo">Forum Questions</h1>
                <nav class="nav">
                    <?php if (isLoggedIn()): ?>
                        <span class="user-info">Bonjour, <?php echo htmlspecialchars(getUserLogin()); ?></span>
                        <a href="poster_question.php" class="btn btn-primary">Poser une question</a>
                        <a href="logout.php" class="btn btn-secondary">Déconnexion</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-secondary">Connexion</a>
                        <a href="register.php" class="btn btn-primary">Inscription</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container main-content">
        <div class="page-header">
            <h2>Toutes les questions</h2>
            <p class="subtitle"><?php echo count($questions); ?> question(s) au total</p>
        </div>

        <!-- Liste des questions -->
        <div class="questions-list">
            <?php if (empty($questions)): ?>
                <div class="empty-state">
                    <p>Aucune question pour le moment.</p>
                    <?php if (isLoggedIn()): ?>
                        <a href="poster_question.php" class="btn btn-primary">Poser la première question</a>
                    <?php else: ?>
                        <p><a href="login.php">Connectez-vous</a> pour poser une question.</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php foreach ($questions as $question): ?>
                    <div class="question-card">
                        <div class="question-header">
                            <h3 class="question-title">
                                <a href="question.php?id=<?php echo $question['q_id']; ?>">
                                    <?php echo htmlspecialchars($question['q_titre']); ?>
                                </a>
                            </h3>
                        </div>
                        <div class="question-body">
                            <p><?php echo htmlspecialchars($question['q_contenu']); ?></p>
                        </div>
                        <div class="question-footer">
                            <span class="author">Posté par <strong><?php echo htmlspecialchars($question['login']); ?></strong></span>
                            <span class="date"><?php echo htmlspecialchars($question['q_date_ajout']); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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