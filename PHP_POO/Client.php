<?php
abstract class Client{
private int $id ;
private string $Nom;
private String $Adresse;

public static int $nbr_user=0;

public function __construct(int $id, string $Nom, string $Adresse, ){
    $this->id = $id;
    $this->Nom = $Nom;
    $this->Adresse = $Adresse;
    self::$nbr_user++;
}

/* public function get_Instance(int $id, string $Nom, string $Adresse): Client {
    $instance = new Client($id, $Nom, $Adresse);
    return $instance;
}*/

public function getId(): int {
    return $this->id;
}
public function setId(int $id): void {
    $this->id = $id;
}
public function getNom(): string {
    return $this->Nom;
}
public function setNom(string $Nom): void {
    $this->Nom = $Nom;
}
public function getAdresse(): string {
    return $this->Adresse;
}
public function setAdresse(string $Adresse): void {
    $this->Adresse = $Adresse;
}

public function affiche(){
    echo "---------------------\n";
    echo "Client ID: " . $this->id . "\n";
    echo "Nom: " . $this->Nom . "\n";
    echo "Adresse: " . $this->Adresse . "\n";

}

public function _destructeur(){
    unset($this->id);
    unset($this->Nom);
    unset($this->Adresse);
    self::$nbr_user--;
}

abstract public function Payer_facture(): string;

abstract public function Reclamation(): string;



}
?>