<?php
require_once 'Connexion.php';
require_once 'Facture.php';

$facture_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$client_id = isset($_GET['client_id']) ? (int)$_GET['client_id'] : null;
$message = '';
$facture = null;

try {
    $pdo = Connexion::getInstance();
    
    if ($facture_id) {
        $sql = "SELECT * FROM Facture WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $facture_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Facture');
        $facture = $stmt->fetch();
        
        if (!$facture) {
            $message = "Facture non trouvée.";
        }
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $facture) {
        $montant = (float)$_POST['montant'];
        $date_facture = $_POST['date_facture'];
        $description = $_POST['description'];
        
        if ($facture->modifierFacture($pdo, $montant, $date_facture, $description)) {
            $message = "Facture modifiée avec succès!";
        } else {
            $message = "Erreur lors de la modification.";
        }
    }
    
} catch (PDOException $e) {
    $message = "ERREUR: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Facture</title>
</head>
<body>
    <h1>Modifier une Facture</h1>
    <hr>
    
    <?php if ($message): ?>
        <p><b><?php echo $message; ?></b></p>
        <hr>
    <?php endif; ?>
    
    <?php if ($facture): ?>
        <p><b>Facture #<?php echo $facture->id; ?></b></p>
        <hr>
        <form method="POST" action="">
            <p>Montant (DH): <input type="number" name="montant" step="0.01" value="<?php echo $facture->montant; ?>" required></p>
            <p>Date: <input type="date" name="date_facture" value="<?php echo $facture->date_facture; ?>" required></p>
            <p>Description: <textarea name="description" rows="3" cols="50"><?php echo $facture->description ?? ''; ?></textarea></p>
            <p><button type="submit">Enregistrer les modifications</button></p>
        </form>
        
        <hr>
        <p>
            <?php if ($client_id): ?>
                <a href="client_details.php?id=<?php echo $client_id; ?>">Retour aux détails du client</a>
            <?php else: ?>
                <a href="afficher_factures.php">Retour aux factures</a>
            <?php endif; ?>
        </p>
    <?php else: ?>
        <p><b>Facture introuvable.</b></p>
        <p><a href="afficher_factures.php">Liste des factures</a> | <a href="afficher_clients.php">Liste des clients</a></p>
    <?php endif; ?>
</body>
</html>
