<?php
require_once 'Connexion.php';
require_once 'Facture.php';

$client_id = isset($_GET['client_id']) ? (int)$_GET['client_id'] : null;
$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = Connexion::getInstance();
        
        $client_id_post = (int)$_POST['client_id'];
        $montant = (float)$_POST['montant'];
        $date_facture = $_POST['date_facture'];
        $annee = (int)$_POST['annee'];
        $description = $_POST['description'];
        
        if (Facture::ajouterFacture($pdo, $client_id_post, $montant, $date_facture, $annee, $description)) {
            $message = "Facture ajoutée avec succès!";
            $success = true;
            $client_id = $client_id_post;
        } else {
            $message = "Erreur lors de l'ajout de la facture.";
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
    <title>Ajouter une Facture</title>
</head>
<body>
    <h1>Ajouter une Nouvelle Facture</h1>
    <p><a href="index.php">Accueil</a> | <a href="afficher_clients.php">Liste Clients</a></p>
    <hr>
    
    <?php if ($message): ?>
        <p><b><?php echo $success ? 'SUCCÈS: ' : 'ERREUR: '; ?><?php echo $message; ?></b></p>
        <hr>
    <?php endif; ?>
    
    <h2>Formulaire d'Ajout</h2>
    <form method="POST" action="">
        <p>Client:
        <select name="client_id" required>
            <option value="">-- Sélectionner --</option>
            <?php
            try {
                $pdo = Connexion::getInstance();
                $stmt = $pdo->query("SELECT id, Nom FROM Client ORDER BY id");
                while ($c = $stmt->fetch()) {
                    $selected = ($client_id == $c['id']) ? 'selected' : '';
                    echo "<option value='{$c['id']}' $selected>{$c['Nom']} (ID: {$c['id']})</option>";
                }
            } catch (PDOException $e) {
                echo "<option value=''>Erreur</option>";
            }
            ?>
        </select></p>
        
        <p>Montant (DH):
        <input type="number" name="montant" step="0.01" min="0" required></p>
        
        <p>Date:
        <input type="date" name="date_facture" value="<?php echo date('Y-m-d'); ?>" required></p>
        
        <p>Année:
        <input type="number" name="annee" value="<?php echo date('Y'); ?>" required></p>
        
        <p>Description:
        <textarea name="description" rows="3" cols="50"></textarea></p>
        
        <p><button type="submit">Ajouter la Facture</button></p>
    </form>

    <hr>
    <p>
        <a href="afficher_clients.php">← Retour aux clients</a>
        <?php if ($success && $client_id): ?>
            <a href="client_details.php?id=<?php echo $client_id; ?>">Voir le client</a>
        <?php endif; ?>
    </p>
</body>
</html>
