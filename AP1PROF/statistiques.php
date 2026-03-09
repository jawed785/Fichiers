<?php
session_start();
include '_conf.php';

// Vérifier si l'utilisateur est connecté et a les droits
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}

// Vérifier les droits : seulement admin et secrétaire
if (!in_array($_SESSION['type'], [TYPE_ADMIN, TYPE_SECRETAIRE, TYPE_PROF])) {
    die("Accès refusé. Cette page est réservée aux administrateurs, secrétaires et professeurs.");
}

// Inclure le menu approprié
switch ($_SESSION['type']) {
    case TYPE_PROF:
        include '_menuProf.php';
        break;
    case TYPE_SECRETAIRE:
        include '_menuSecretaire.php';
        break;
    case TYPE_ADMIN:
        include '_menuAdmin.php';
        break;
}

// Connexion à la base
$connexion = mysqli_connect($serveurBDD, $userBDD, $mdpBDD, $nomBDD);
if (!$connexion) {
    die("Erreur de connexion à la base de données");
}

// Récupérer les statistiques
// 1. Nombre total de CR
$requete_total = "SELECT COUNT(*) as total_cr FROM cr";
$resultat_total = mysqli_query($connexion, $requete_total);
$total_cr = mysqli_fetch_assoc($resultat_total)['total_cr'];

// 2. Nombre de CR par élève
$requete_par_eleve = "
    SELECT u.nom, u.prenom, u.login, COUNT(c.num) as nb_cr
    FROM utilisateur u
    LEFT JOIN cr c ON u.num = c.num_utilisateur
    WHERE u.type = " . TYPE_ELEVE . "
    GROUP BY u.num
    ORDER BY u.nom, u.prenom
";
$resultat_eleves = mysqli_query($connexion, $requete_par_eleve);

// 3. Statistiques supplémentaires
$requete_stats = "
    SELECT 
        COUNT(DISTINCT num_utilisateur) as eleves_avec_cr,
        COUNT(*) as total_cr,
        MIN(date) as premier_cr,
        MAX(date) as dernier_cr
    FROM cr
";
$resultat_stats = mysqli_query($connexion, $requete_stats);
$stats = mysqli_fetch_assoc($resultat_stats);

mysqli_close($connexion);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Suivi des Stages</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .stats-container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-card h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        
        .stats-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .stats-table th {
            background: #2c3e50;
            color: white;
            padding: 12px;
            text-align: left;
        }
        
        .stats-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        
        .stats-table tr:hover {
            background: #f9f9f9;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
            font-style: italic;
        }
        
        .export-buttons {
            margin: 20px 0;
            text-align: right;
        }
        
        .btn-export {
            background: #27ae60;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }
        
        .btn-export:hover {
            background: #219653;
        }
    </style>
</head>
<body>
    <main>
        <div class="stats-container">
            <h1>📊 Statistiques des Comptes Rendus</h1>
            <p class="subtitle">Consultation réservée aux administrateurs, secrétaires et professeurs</p>
            
            <div class="export-buttons">
                <button class="btn-export" onclick="window.print()">🖨️ Imprimer</button>
                <button class="btn-export" onclick="exportToCSV()">📥 Exporter CSV</button>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total des CR</h3>
                    <div class="stat-value"><?php echo $total_cr; ?></div>
                    <div class="stat-label">Comptes rendus</div>
                </div>
                
                <div class="stat-card">
                    <h3>Élèves avec CR</h3>
                    <div class="stat-value"><?php echo $stats['eleves_avec_cr']; ?></div>
                    <div class="stat-label">Élèves actifs</div>
                </div>
                
                <div class="stat-card">
                    <h3>Premier CR</h3>
                    <div class="stat-value"><?php echo date('d/m/Y', strtotime($stats['premier_cr'])); ?></div>
                    <div class="stat-label">Date du premier</div>
                </div>
                
                <div class="stat-card">
                    <h3>Dernier CR</h3>
                    <div class="stat-value"><?php echo date('d/m/Y', strtotime($stats['dernier_cr'])); ?></div>
                    <div class="stat-label">Date du dernier</div>
                </div>
            </div>
            
            <h2>Détail par élève</h2>
            <div class="table-container">
                <?php if (mysqli_num_rows($resultat_eleves) > 0): ?>
                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Login</th>
                                <th>Nombre de CR</th>
                                <th>Dernière activité</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Réouverture de la connexion pour la deuxième requête
                            $connexion = mysqli_connect($serveurBDD, $userBDD, $mdpBDD, $nomBDD);
                            $resultat_eleves = mysqli_query($connexion, $requete_par_eleve);
                            
                            while ($eleve = mysqli_fetch_assoc($resultat_eleves)):
                                // Récupérer la date du dernier CR pour cet élève
                                $requete_dernier = "
                                    SELECT MAX(date) as dernier_cr 
                                    FROM cr 
                                    WHERE num_utilisateur = (
                                        SELECT num FROM utilisateur WHERE login = '" . $eleve['login'] . "'
                                    )
                                ";
                                $result_dernier = mysqli_query($connexion, $requete_dernier);
                                $dernier_cr = mysqli_fetch_assoc($result_dernier)['dernier_cr'];
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($eleve['nom']); ?></td>
                                <td><?php echo htmlspecialchars($eleve['prenom']); ?></td>
                                <td><?php echo htmlspecialchars($eleve['login']); ?></td>
                                <td>
                                    <span class="badge"><?php echo $eleve['nb_cr']; ?></span>
                                </td>
                                <td>
                                    <?php 
                                    if ($dernier_cr) {
                                        echo date('d/m/Y', strtotime($dernier_cr));
                                    } else {
                                        echo '<span style="color: #95a5a6;">Aucun CR</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-data">
                        <p>Aucun compte rendu trouvé pour le moment.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="chart-container" style="margin-top: 40px;">
                <h2>Répartition des CR</h2>
                <canvas id="crChart" width="400" height="200"></canvas>
            </div>
        </div>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Données pour le graphique
        const elevesData = [
            <?php
            $connexion = mysqli_connect($serveurBDD, $userBDD, $mdpBDD, $nomBDD);
            $resultat_eleves = mysqli_query($connexion, $requete_par_eleve);
            $data = [];
            while ($eleve = mysqli_fetch_assoc($resultat_eleves)) {
                $data[] = [
                    'label' => $eleve['prenom'] . ' ' . substr($eleve['nom'], 0, 1) . '.',
                    'value' => $eleve['nb_cr']
                ];
            }
            echo json_encode($data);
            mysqli_close($connexion);
            ?>
        ];
        
        // Création du graphique
        const ctx = document.getElementById('crChart').getContext('2d');
        const crChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: elevesData.map(item => item.label),
                datasets: [{
                    label: 'Nombre de CR',
                    data: elevesData.map(item => item.value),
                    backgroundColor: '#3498db',
                    borderColor: '#2980b9',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nombre de CR'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Élèves'
                        }
                    }
                }
            }
        });
        
        // Fonction d'export CSV
        function exportToCSV() {
            let csv = 'Nom,Prénom,Login,Nombre de CR,Dernier CR\n';
            
            <?php
            $connexion = mysqli_connect($serveurBDD, $userBDD, $mdpBDD, $nomBDD);
            $resultat_eleves = mysqli_query($connexion, $requete_par_eleve);
            while ($eleve = mysqli_fetch_assoc($resultat_eleves)):
                $requete_dernier = "
                    SELECT MAX(date) as dernier_cr 
                    FROM cr 
                    WHERE num_utilisateur = (
                        SELECT num FROM utilisateur WHERE login = '" . $eleve['login'] . "'
                    )
                ";
                $result_dernier = mysqli_query($connexion, $requete_dernier);
                $dernier_cr = mysqli_fetch_assoc($result_dernier)['dernier_cr'];
                $dernier_cr_formatted = $dernier_cr ? date('d/m/Y', strtotime($dernier_cr)) : 'Aucun';
            ?>
            csv += '<?php echo addslashes($eleve['nom']) . ',' . addslashes($eleve['prenom']) . ',' . $eleve['login'] . ',' . $eleve['nb_cr'] . ',' . $dernier_cr_formatted; ?>\n';
            <?php endwhile; mysqli_close($connexion); ?>
            
            // Téléchargement du fichier CSV
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'statistiques_cr_' + new Date().toISOString().slice(0,10) + '.csv';
            link.click();
        }
    </script>
</body>
</html>