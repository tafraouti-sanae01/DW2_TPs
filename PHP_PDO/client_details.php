<?php
require_once 'Connexion.php';
require_once 'Facture.php';

$client_id = isset($_GET['id']) ? (int)$_GET['id'] : 2;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Client</title>
</head>
<body>
    <h1>Détails du Client #<?php echo $client_id; ?></h1>
    <p><a href="index.php">Accueil</a> | <a href="afficher_clients.php">Liste Clients</a></p>
    <hr>

    <?php
    try {
        $pdo = Connexion::getInstance();
        
        $sql = "SELECT * FROM Client WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $client_id]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$client) {
            echo "<p><b>Client non trouvé!</b></p>";
            exit;
        }
        
        echo "<h2>Informations du Client</h2>";
        echo "<p><b>ID:</b> {$client['id']}</p>";
        echo "<p><b>Nom:</b> {$client['Nom']}</p>";
        echo "<p><b>Adresse:</b> {$client['Adresse']}</p>";
        echo "<p><b>Type:</b> {$client['type']}</p>";
        
        $sql_factures = "SELECT * FROM Facture WHERE client_id = :client_id ORDER BY date_facture DESC";
        $stmt_factures = $pdo->prepare($sql_factures);
        $stmt_factures->execute(['client_id' => $client_id]);
        $stmt_factures->setFetchMode(PDO::FETCH_CLASS, 'Facture');
        $factures = $stmt_factures->fetchAll();
        
        echo "<hr>";
        echo "<h2>Liste des Factures (" . count($factures) . ")</h2>";
        
        if (count($factures) == 0) {
            echo "<p>Aucune facture pour ce client.</p>";
        } else {
            foreach ($factures as $facture) {
                echo "<h3>Facture #$facture->id</h3>";
                echo "<p>Montant: $facture->montant DH</p>";
                echo "<p>Date: $facture->date_facture (Année: $facture->annee)</p>";
                echo "<p>Statut: " . ($facture->est_payee ? 'PAYÉE' : 'NON PAYÉE') . "</p>";
                echo "<p>Description: $facture->description </p>";
                
                if (!$facture->est_payee) {
                    echo "<p><a href='payer_facture.php?id=$facture->id&client_id=$client_id'>Payer</a> | ";
                } else {
                    echo "<p>";
                }
                echo "<a href='modifier_facture.php?id=$facture->id&client_id=$client_id'>Modifier</a></p>";
                echo "<hr>";
            }
        }
        
    } catch (PDOException $e) {
        echo "<p><b>ERREUR:</b> " . $e->getMessage() . "</p>";
    }
    ?>
</body>
</html>
