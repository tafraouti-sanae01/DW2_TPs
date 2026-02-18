<?php
abstract class Client{
private int $id;
private string $Nom;
private string $Adresse;
private string $type; 

public static int $nbr_user=0;

public function __construct(int $id, string $Nom, string $Adresse, string $type = 'local'){
    $this->id = $id;
    $this->Nom = $Nom;
    $this->Adresse = $Adresse;
    $this->type = $type;
    self::$nbr_user++;
}

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
public function getType(): string {
    return $this->type;
}
public function setType(string $type): void {
    $this->type = $type;
}

public function __destruct(){
    self::$nbr_user--;
}

abstract public function Payer_facture(): string;

abstract public function Reclamation(): string;

}
?>