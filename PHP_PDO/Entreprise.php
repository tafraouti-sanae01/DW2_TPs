<?php 

class Entreprise extends Client {
    private array $DataEntreprise = [
        'Num_Patente' => '',
        'Type_Societe' => '',
        'Secteur_Activite' => ''
    ];

    public function __set($name, $value) {
        if (array_key_exists($name, $this->DataEntreprise)) {
            $this->DataEntreprise[$name] = $value;
        }
    }

    public function __get($name) {
        if (array_key_exists($name, $this->DataEntreprise)) {
            return $this->DataEntreprise[$name];
        }
        return null;
    }

    public function Payer_facture(): string {
        return "L'entreprise paie par chèque";
    }

    public function Reclamation(): string {
        return "Secteur d'activité: " . $this->DataEntreprise['Secteur_Activite'];
    }
}

?>
