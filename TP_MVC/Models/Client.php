<?php
class Client extends Model
{
    public function __construct()
    {
        $this->table = "Client";
        self::getModel();
    }

    public function getCity(string $ville)
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE adresse = '" . $ville."'";
        $query = self::$instance->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    // Méthode pour ajouter un nouveau client
    public function insert($nom, $prenom, $adresse)
    {
        $sql = "INSERT INTO " . $this->table . " (nom, prenom, adresse) VALUES (:nom, :prenom, :adresse)";
        $query = self::$instance->prepare($sql);
        $query->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':adresse' => $adresse
        ]);
        return self::$instance->lastInsertId();
    }

    // Méthode pour modifier un client
    public function update($id, $nom, $prenom, $adresse)
    {
        $sql = "UPDATE " . $this->table . " SET nom=:nom, prenom=:prenom, adresse=:adresse WHERE id=:id";
        $query = self::$instance->prepare($sql);
        $query->execute([
            ':id' => $id,
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':adresse' => $adresse
        ]);
    }

    // Méthode pour supprimer un client
    public function delete($id)
    {
        $sql = "DELETE FROM " . $this->table . " WHERE id=:id";
        $query = self::$instance->prepare($sql);
        $query->execute([':id' => $id]);
    }
}
