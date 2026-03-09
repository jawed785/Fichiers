<!DOCTYPE html>
<?php
session_start();  // démarrage d'une session

// on vérifie que les variables de session identifiant l'utilisateur existent
if (isset($_SESSION['email']) && isset($_SESSION['mdp'])) {
    $email = $_SESSION['email'];
    $mdp = $_SESSION['mdp'];
    $nom_utilisateur = $_SESSION['nom_utilisateur'];
    $prenom_utilisateur = $_SESSION['prenom_utilisateur'];
}
try {
  $bdd = new PDO('mysql:host=localhost;dbname=site_event;', 'root', 'root');
} catch(Exception $e) {
  die('Erreur : '.$e->getMessage());
}

$host = 'localhost';
$db = 'site_event';
$user = 'root';
$pass = 'root';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$sql = "SELECT * FROM events ORDER BY date_event ASC";
$stmt = $pdo->query($sql);
$events = $stmt->fetchAll();
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passe Sport - Accueil</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Affichage du message de bienvenue en pop-up avec SweetAlert
        window.onload = function() {
            Swal.fire({
                title: 'Bienvenue, <?php echo htmlspecialchars($prenom_utilisateur . 'Benjammin ' . $nom_utilisateur); ?> !',
                text: 'Vous êtes maintenant connecté(e) à votre compte.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }
    </script>
</head>
<body>
    
    <header>
        <nav>
            <div class="logo-container">
                <div id="logo">
                    <a href="accueil.php"><img src="pass.png" alt="Sportlock" /></a>
                </div>
                <button class="nav-button event-btn" onclick="window.location.href ='eventpage.php';">Tous les événements</button>
            </div>
            
            <div class="auth-buttons">
                <?php if (isset($email) && isset($mdp)): ?>
                    <button class="nav-button account-btn" onclick="window.location.href = 'account.php';">Mon Compte</button>
                <?php else: ?>
                    <button class="nav-button login-btn" onclick="window.location.href ='login.php';">Connexion</button>
                    <button class="nav-button signup-btn" onclick="window.location.href ='inscription.php';">Inscription</button>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    
    <section class="hero">
        <div class="hero-content">
            <h1>Trouvez l'événement sportif fait pour vous !</h1>
            <p>Découvrez des expériences uniques qui vous correspondent</p>
        </div>
    </section>

    <div class="container">
        <div class="carousel">
            <div class="carousel-header">
                <h2 class="section-title">Événements à la une</h2>
                <a href="#" class="view-all">Voir tout</a>
            </div>

            <div class="carousel-container">
                <?php foreach ($events as $event): ?>
                    <div class="event-card">
                        <img src="<?php echo htmlspecialchars($event['url_event']); ?>" alt="<?php echo htmlspecialchars($event['nom_event']); ?>" class="event-image">
                        <div class="event-details">
                            <div class="event-date"><?php echo htmlspecialchars($event['date_event']); ?></div>
                            <h3 class="event-title"><?php echo htmlspecialchars($event['nom_event']); ?></h3>
                            <div class="event-location"><?php echo htmlspecialchars($event['lieu_event']); ?></div>
                            <div class="event-price">À partir de <?php echo htmlspecialchars($event['prix_event']); ?>€</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="carousel-nav">
                <button class="carousel-button prev"><<i class="fas fa-chevron-left"></i></button>
                <button class="carousel-button next">><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 SportLock - <a href="mentions_legales.php">Mentions légales</a></p>
    </footer>

    <script src='accueil.js'></script>
</body>
</html>