<?php
require_once 'Connexion.php';
require_once 'Facture.php';

$facture_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$client_id = isset($_GET['client_id']) ? (int)$_GET['client_id'] : null;
$message = '';

if ($facture_id) {
    try {
        $pdo = Connexion::getInstance();
        
        $sql = "SELECT * FROM Facture WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $facture_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Facture');
        $facture = $stmt->fetch();
        
        if ($facture) {
            if ($facture->est_payee) {
                $message = "Cette facture est déjà payée!";
            } else {
                if ($facture->payerFacture($pdo)) {
                    $message = "Facture #$facture_id payée avec succès!";
                } else {
                    $message = "Erreur lors du paiement.";
                }
            }
        } else {
            $message = "Facture non trouvée.";
        }
        
    } catch (PDOException $e) {
        $message = "ERREUR: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Payer Facture</title>
</head>
<body>
    <h1>Paiement de Facture</h1>
    
    <?php if ($message): ?>
        <p><b><?php echo $message; ?></b></p>
    <?php endif; ?>
    
    <hr>
    <p><a href="<?php echo $client_id ? "client_details.php?id=$client_id" : "afficher_clients.php"; ?>">Retour</a></p>
</body>
</html>
