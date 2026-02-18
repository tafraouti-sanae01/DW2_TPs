<?php
class Zone extends Model
{
    public function __construct()
    {
        $this->table = "zone";
        self::getModel();
    }

    public function getOne($idZ)
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE idZ='" . $idZ . "'";
        $query = self::$instance->prepare($sql);
        $query->execute();
        return $query->fetch();
    }

    // Méthode pour ajouter une nouvelle zone
    public function insert($nomZ, $VilleZ, $ServicesPublics)
    {
        $sql = "INSERT INTO " . $this->table . " (nomZ, VilleZ, `Services publics`) VALUES (:nomZ, :VilleZ, :ServicesPublics)";
        $query = self::$instance->prepare($sql);
        $query->execute([
            ':nomZ' => $nomZ,
            ':VilleZ' => $VilleZ,
            ':ServicesPublics' => $ServicesPublics
        ]);
        return self::$instance->lastInsertId();
    }

    // Méthode pour modifier une zone
    public function update($idZ, $nomZ, $VilleZ, $ServicesPublics)
    {
        $sql = "UPDATE " . $this->table . " SET nomZ=:nomZ, VilleZ=:VilleZ, `Services publics`=:ServicesPublics WHERE idZ=:idZ";
        $query = self::$instance->prepare($sql);
        $query->execute([
            ':idZ' => $idZ,
            ':nomZ' => $nomZ,
            ':VilleZ' => $VilleZ,
            ':ServicesPublics' => $ServicesPublics
        ]);
    }

    // Méthode pour supprimer une zone
    public function delete($idZ)
    {
        $sql = "DELETE FROM " . $this->table . " WHERE idZ=:idZ";
        $query = self::$instance->prepare($sql);
        $query->execute([':idZ' => $idZ]);
    }
}

