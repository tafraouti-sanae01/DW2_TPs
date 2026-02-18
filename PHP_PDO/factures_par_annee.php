<?php
require_once 'Connexion.php';
require_once 'Facture.php';

$annee = isset($_GET['annee']) ? (int)$_GET['annee'] : 2024;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Factures par Année</title>
</head>
<body>
    <h1>Factures de l'année <?php echo $annee; ?></h1>
    <p><a href="index.php">Retour Accueil</a></p>
    <hr>

    <h3>Rechercher par année</h3>
    <form method="GET" action="">
        <p>Année: <input type="number" name="annee" value="<?php echo $annee; ?>" max="2025" required>
        <button type="submit">Rechercher</button></p>
    </form>

    <hr>

    <?php
    try {
        $pdo = Connexion::getInstance();
        
        $sql = "SELECT * FROM Facture WHERE annee = :annee ORDER BY date_facture DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['annee' => $annee]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Facture');
        $factures = $stmt->fetchAll();
        
        if (count($factures) == 0) {
            echo "<p>Aucune facture trouvée pour l'année $annee</p>";
        } else {
            foreach ($factures as $facture) {
                echo "<h3>Facture #$facture->id</h3>";
                echo "<p>Client ID: $facture->client_id</p>";
                echo "<p>Montant: $facture->montant DH</p>";
                echo "<p>Date: $facture->date_facture</p>";
                echo "<p>Statut: " . ($facture->est_payee ? 'PAYÉE' : 'NON PAYÉE') . "</p>";
                echo "<p>Description: $facture->description </p>";
                echo "<hr>";
            }
        }
        
    } catch (PDOException $e) {
        echo "<p><b>ERREUR:</b> " . $e->getMessage() . "</p>";
    }
    ?>
</body>
</html>
