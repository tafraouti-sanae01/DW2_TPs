<?php
require_once 'Connexion.php';
require_once 'Facture.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Factures</title>
</head>
<body>
    <h1>Liste de Toutes les Factures</h1>
    <p><a href="index.php">Retour Accueil</a></p>
    <hr>

    <?php
    try {
        $pdo = Connexion::getInstance();
        
        $sql = "SELECT * FROM Facture ORDER BY date_facture DESC";
        $stmt = $pdo->query($sql);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Facture');
        
        $factures = $stmt->fetchAll();
        
        foreach ($factures as $facture) {
            echo "<h3>Facture #$facture->id</h3>";
            echo "<p>Client ID: $facture->client_id</p>";
            echo "<p>Montant: $facture->montant DH </p>";
            echo "<p>Date: $facture->date_facture (Année: $facture->annee)</p>";
            echo "<p>Statut: " . ($facture->est_payee ? 'PAYÉE' : 'NON PAYÉE') . "</p>";
            echo "<p>Description: $facture->description </p>";
            echo "<hr>";
        }
        
    } catch (PDOException $e) {
        echo "<p><b>ERREUR:</b> " . $e->getMessage() . "</p>";
    }
    ?>
</body>
</html>
