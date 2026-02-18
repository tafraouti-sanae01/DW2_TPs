<?php

require_once 'Connexion.php';
require_once 'Facture.php';

$client_id = isset($_GET['client_id']) ? (int)$_GET['client_id'] : null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Factures Non Payées</title>
</head>
<body>
    <h1>Factures Non Payées</h1>
    <p><a href="index.php">Retour Accueil</a></p>
    <hr>

    <h3>Sélectionner un client</h3>
    <form method="GET" action="">
        <p>Client: 
        <select name="client_id" required>
            <option value="">-- Choisir un client --</option>
            <?php
            try {
                $pdo = Connexion::getInstance();
                $sql = "SELECT id, Nom FROM Client ORDER BY id";
                $stmt = $pdo->query($sql);
                $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($clients as $c) {
                    $selected = ($client_id == $c['id']) ? 'selected' : '';
                    $id = $c['id'];
                    $nom = $c['Nom'];
                    echo "<option value='$id' $selected>$nom (ID: $id)</option>";
                }
            } catch (PDOException $e) {
                echo "<option value=''>Erreur</option>";
            }
            ?>
        </select>
        <button type="submit">Afficher</button></p>
    </form>

    <hr>

    <?php
    if ($client_id) {
        try {
            $sql_client = "SELECT * FROM Client WHERE id = :id";
            $stmt_client = $pdo->prepare($sql_client);
            $stmt_client->execute(['id' => $client_id]);
            $client = $stmt_client->fetch(PDO::FETCH_ASSOC);
            
            if (!$client) {
                echo "<p><b>Client non trouvé.</b></p>";
                exit;
            }
            
            echo "<h2>Client: {$client['Nom']}</h2>";
            echo "<p><b>ID:</b> {$client['id']} | <b>Adresse:</b> {$client['Adresse']} | <b>Type:</b> {$client['type']}</p>";
            echo "<hr>";
            
            $sql_factures = "SELECT * FROM Facture WHERE client_id = :client_id AND est_payee = FALSE ORDER BY date_facture DESC";
            $stmt_factures = $pdo->prepare($sql_factures);
            $stmt_factures->execute(['client_id' => $client_id]);
            $stmt_factures->setFetchMode(PDO::FETCH_CLASS, 'Facture');
            $factures = $stmt_factures->fetchAll();
            
            if (count($factures) == 0) {
                echo "<p><b>Aucune facture non payée!</b></p>";
            } else {
                echo "<p><b>Factures non payées: " . count($factures) . "</b></p>";
                echo "<hr>";
                
                foreach ($factures as $facture) {
                    echo "<h3>Facture #$facture->id - NON PAYÉE</h3>";
                    echo "<p>Montant: $facture->montant DH</p>";
                    echo "<p>Date: $facture->date_facture (Année: $facture->annee)</p>";
                    echo "<p>Description: $facture->description </p>";
                    
                    echo "<p><a href='payer_facture.php?id=$facture->id&client_id=$client_id'>Payer</a> | ";
                    echo "<a href='modifier_facture.php?id=$facture->id&client_id=$client_id'>Modifier</a></p>";
                    echo "<hr>";
                }
            }
            
        } catch (PDOException $e) {
            echo "<p><b>ERREUR:</b> " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>Veuillez sélectionner un client pour voir ses factures non payées.</p>";
    }
    ?>
</body>
</html>
