<!DOCTYPE html>
<?php
session_start();  // démarrage d'une session

// Vérifier la connexion
if (isset($_SESSION['email']) && isset($_SESSION['mdp'])) {
    $email = $_SESSION['email'];
    $mdp = $_SESSION['mdp'];
    $nom_utilisateur = $_SESSION['nom_utilisateur'];
    $prenom_utilisateur = $_SESSION['prenom_utilisateur'];
}

// Connexion à la base de données
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

// Si l'utilisateur ajoute un événement au panier
if (isset($_POST['ajouter_panier'])) {
    $event_id = $_POST['event_id'];
    $sql = "SELECT * FROM events WHERE id_event = :id_event";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_event' => $event_id]);
    $event = $stmt->fetch();

    // Ajouter l'événement au panier (stocké dans la session)
    if ($event) {
        $_SESSION['panier'][] = $event;
    }
}

?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passe Sport - Événements</title>
    <link rel="stylesheet" href="style.css">
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
                <?php if (isset($_SESSION['email'])): ?>
                    <button class="nav-button account-btn" onclick="window.location.href = 'account.php';">Mon Compte</button>
                    <button class="nav-button" onclick="window.location.href = 'panier.php';">Mon Panier</button>
                <?php else: ?>
                    <button class="nav-button login-btn" onclick="window.location.href ='login.php';">Connexion</button>
                    <button class="nav-button signup-btn" onclick="window.location.href ='inscription.php';">Inscription</button>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class='pres'>
            <h1>Tous les événements par catégories</h1>
            </br>
        </div>

        <!-- Carrousel des événements Football -->
        <div class="carousel">
            <?php
            $sql = "SELECT * FROM events WHERE categorie_event='Football' ORDER BY date_event ASC";
            $stmt = $pdo->query($sql);
            $events = $stmt->fetchAll();
            ?>
            <div class="carousel-header">
                <h2 class="section-title">Football</h2>
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
                        <!-- Ajouter un événement au panier -->
                        <form action="eventpage.php" method="POST">
                            <input type="hidden" name="event_id" value="<?php echo $event['id_event']; ?>">
                            <button type="submit" name="ajouter_panier">Réserver</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Carrousel des événements Tennis -->
        <div class="carousel">
            <?php
            $sql = "SELECT * FROM events WHERE categorie_event='Tennis' ORDER BY date_event ASC";
            $stmt = $pdo->query($sql);
            $events = $stmt->fetchAll();
            ?>
            <div class="carousel-header">
                <h2 class="section-title">Tennis</h2>
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
                        <!-- Ajouter un événement au panier -->
                        <form action="eventpage.php" method="POST">
                            <input type="hidden" name="event_id" value="<?php echo $event['id_event']; ?>">
                            <button type="submit" name="ajouter_panier">Réserver</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Carrousel des événements Basketball -->
        <div class="carousel">
            <?php
            $sql = "SELECT * FROM events WHERE categorie_event='Basketball' ORDER BY date_event ASC";
            $stmt = $pdo->query($sql);
            $events = $stmt->fetchAll();
            ?>
            <div class="carousel-header">
                <h2 class="section-title">Basketball</h2>
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
                        <!-- Ajouter un événement au panier -->
                        <form action="eventpage.php" method="POST">
                            <input type="hidden" name="event_id" value="<?php echo $event['id_event']; ?>">
                            <button type="submit" name="ajouter_panier">Réserver</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Carrousel des événements Cyclisme -->
        <div class="carousel">
            <?php
            $sql = "SELECT * FROM events WHERE categorie_event='Cyclisme' ORDER BY date_event ASC";
            $stmt = $pdo->query($sql);
            $events = $stmt->fetchAll();
            ?>
            <div class="carousel-header">
                <h2 class="section-title">Cyclisme</h2>
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
                        <!-- Ajouter un événement au panier -->
                        <form action="eventpage.php" method="POST">
                            <input type="hidden" name="event_id" value="<?php echo $event['id_event']; ?>">
                            <button type="submit" name="ajouter_panier">Réserver</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Carrousel des événements Accessible -->
        <div class="carousel">
            <?php
            $sql = "SELECT * FROM events WHERE prix_event<='50' ORDER BY date_event ASC";
            $stmt = $pdo->query($sql);
            $events = $stmt->fetchAll();
            ?>
            <div class="carousel-header">
                <h2 class="section-title">Accessible</h2>
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
                        <!-- Ajouter un événement au panier -->
                        <form action="eventpage.php" method="POST">
                            <input type="hidden" name="event_id" value="<?php echo $event['id_event']; ?>">
                            <button type="submit" name="ajouter_panier">Réserver</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <script src="accueil.js"></script>
</body>
</html>