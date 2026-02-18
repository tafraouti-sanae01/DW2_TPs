<?php
require_once 'Connexion.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Clients</title>
</head>
<body>
    <h1>Liste des Clients</h1>
    <p><a href="index.php">Retour Accueil</a></p>
    <hr>

    <?php
    try {
        $pdo = Connexion::getInstance();
        
        $sql = "SELECT * FROM Client ORDER BY id";
        $stmt = $pdo->query($sql);
        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Nom</th>";
        echo "<th>Adresse</th>";
        echo "<th>Type</th>";
        echo "<th>Actions</th>";
        echo "</tr>";
        
        foreach ($clients as $client) {
            echo "<tr>";
            echo "<td>{$client['id']}</td>";
            echo "<td>{$client['Nom']}</td>";
            echo "<td>{$client['Adresse']}</td>";
            echo "<td>{$client['type']}</td>";
            echo "<td>";
            echo "<a href='client_details.php?id={$client['id']}'>Détails</a> | ";
            echo "<a href='ajouter_facture.php?client_id={$client['id']}'>Ajouter Facture</a>";
            echo "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
    } catch (PDOException $e) {
        echo "<p><b>ERREUR:</b> " . $e->getMessage() . "</p>";
    }
    ?>
</body>
</html>
