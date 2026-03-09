<?php
require_once 'config.php';

// Récupérer l'ID de la question
$question_id = $_GET['id'] ?? null;

if (!$question_id) {
    header('Location: index.php');
    exit;
}

// Récupérer la question
$stmt = $pdo->prepare("
    SELECT q.*, u.login 
    FROM question q 
    LEFT JOIN utilisateur u ON q.user_id = u.user_id 
    WHERE q.q_id = ?
");
$stmt->execute([$question_id]);
$question = $stmt->fetch();

if (!$question) {
    header('Location: index.php');
    exit;
}

// Récupérer les réponses
$stmt = $pdo->prepare("
    SELECT r.*, u.login 
    FROM reponse r 
    LEFT JOIN utilisateur u ON r.user_id = u.user_id 
    WHERE r.r_fk_question_id = ? 
    ORDER BY r.r_id ASC
");
$stmt->execute([$question_id]);
$reponses = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($question['q_titre']); ?> - Forum</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <h1 class="logo"><a href="index.php">Forum Questions</a></h1>
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
        <a href="index.php" class="back-link">← Retour aux questions</a>

        <!-- Question -->
        <div class="question-detail">
            <div class="question-header">
                <h2><?php echo htmlspecialchars($question['q_titre']); ?></h2>
            </div>
            <div class="question-body">
                <p><?php echo nl2br(htmlspecialchars($question['q_contenu'])); ?></p>
            </div>
            <div class="question-footer">
                <span class="author">Posté par <strong><?php echo htmlspecialchars($question['login']); ?></strong></span>
                <span class="date"><?php echo htmlspecialchars($question['q_date_ajout']); ?></span>
                
                <?php if (isLoggedIn() && $question['user_id'] == getUserId()): ?>
                    <div class="actions">
                        <a href="modifier_question.php?id=<?php echo $question['q_id']; ?>" class="btn-action btn-edit">Modifier</a>
                        <a href="supprimer_question.php?id=<?php echo $question['q_id']; ?>" 
                           class="btn-action btn-delete" 
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette question ?');">Supprimer</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Réponses -->
        <div class="reponses-section">
            <h3><?php echo count($reponses); ?> réponse(s)</h3>
            
            <?php if (empty($reponses)): ?>
                <p class="empty-state">Aucune réponse pour le moment. Soyez le premier à répondre !</p>
            <?php else: ?>
                <?php foreach ($reponses as $reponse): ?>
                    <div class="reponse-card">
                        <div class="reponse-body">
                            <p><?php echo nl2br(htmlspecialchars($reponse['r_contenu'])); ?></p>
                        </div>
                        <div class="reponse-footer">
                            <span class="author">Par <strong><?php echo htmlspecialchars($reponse['login']); ?></strong></span>
                            <span class="date"><?php echo htmlspecialchars($reponse['r_date_ajout']); ?></span>
                            
                            <?php if (isLoggedIn() && $reponse['user_id'] == getUserId()): ?>
                                <div class="actions">
                                    <a href="modifier_reponse.php?id=<?php echo $reponse['r_id']; ?>" class="btn-action btn-edit">Modifier</a>
                                    <a href="supprimer_reponse.php?id=<?php echo $reponse['r_id']; ?>" 
                                       class="btn-action btn-delete" 
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réponse ?');">Supprimer</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Formulaire de réponse -->
        <?php if (isLoggedIn()): ?>
            <div class="reponse-form">
                <h3>Votre réponse</h3>
                <form action="poster_reponse.php" method="POST">
                    <input type="hidden" name="question_id" value="<?php echo $question['q_id']; ?>">
                    <textarea name="contenu" rows="6" placeholder="Écrivez votre réponse ici..." required></textarea>
                    <button type="submit" class="btn btn-primary">Publier la réponse</button>
                </form>
            </div>
        <?php else: ?>
            <div class="login-prompt">
                <p>Vous devez être <a href="login.php">connecté</a> pour répondre à cette question.</p>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 Forum Questions. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>