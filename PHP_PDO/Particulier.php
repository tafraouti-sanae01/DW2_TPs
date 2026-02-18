<?php 

class Particulier extends Client {

    public function Payer_facture(): string {
        return "Le particulier paie en espèces ou par carte bancaire";
    }

    public function Reclamation(): string {
        return "Réclamation du particulier: " . $this->getNom();
    }
}

?>
