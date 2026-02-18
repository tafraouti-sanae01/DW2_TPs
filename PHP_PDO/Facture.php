<?php
class Facture {
    public int $id;
    public int $client_id;
    public float $montant;
    public string $date_facture;
    public int $annee;
    public bool $est_payee;
    public ?string $description;
    
    public function payerFacture(PDO $pdo): bool {
        try {
            $sql = "UPDATE Facture SET est_payee = TRUE WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $this->id]);
            $this->est_payee = true;
            return true;
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
            return false;
        }
    }
    
    public function modifierFacture(PDO $pdo, float $montant, string $date_facture, string $description): bool {
        try {
            $sql = "UPDATE Facture SET montant = :montant, date_facture = :date_facture, description = :description WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                'montant' => $montant,
                'date_facture' => $date_facture,
                'description' => $description,
                'id' => $this->id
            ]);
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
            return false;
        }
    }
    
    public static function ajouterFacture(PDO $pdo, int $client_id, float $montant, string $date_facture, int $annee, string $description): bool {
        try {
            $sql = "INSERT INTO Facture (client_id, montant, date_facture, annee, description) 
                    VALUES (:client_id, :montant, :date_facture, :annee, :description)";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                'client_id' => $client_id,
                'montant' => $montant,
                'date_facture' => $date_facture,
                'annee' => $annee,
                'description' => $description
            ]);
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
            return false;
        }
    }
}
?>

