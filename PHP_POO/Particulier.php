<?php 

class Particulier extends Client {

    public function Payer_facture(): string {
        return "Le particulier paie en espèces ou par carte bancaire";
    }

    public function Reclamation(): string {
        return "Réclamation du particulier: " . $this->getNom();
    }

    public function __toString() {
        return "--- PARTICULIER ---\n" .
               "ID: " . $this->getId() . "\n" .
               "Nom: " . $this->getNom() . "\n" .
               "Adresse: " . $this->getAdresse() . "\n" .
               "---------------------\n";
    }

}

?>
