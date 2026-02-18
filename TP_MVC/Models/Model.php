<?php
abstract class Model{
    // Informations de la base de données
    private $dns = "mysql:host=localhost;dbname=FEMVC";
    private $login = "root";
    private $mdp = "";
    
    // Attribut statique qui contiendra l'unique instance de Model
    protected static $instance = null;

    public $table;
    public $id;

    /*private function __construct()
    {  
    }*/
    public function getModel()
    {
        if (self::$instance === null) {
            try{
                self::$instance = new PDO($this->dns, $this->login, $this->mdp);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->query("SET nameS 'utf8'");
                return self::$instance;
            }catch (PDOException $e){
                echo 'Erreur :'.$e->getMessage();
            }
            
            //self::$instance = new self();
        }
    }

    public function getOne($id)
    {
        $this->id=$id;
        $sql = "SELECT * FROM ".$this->table." WHERE id='".$this->id."'";
        $query = self::$instance->prepare($sql);
        $query->execute();
        return $query->fetch();
    }

//formatage sous forme d'un objet du data . Attention il faut modifier la methode loadView dans controller
/*
    public function getAll(){
        $sql = "SELECT * FROM ".$this->table;
        $query = self::$instance->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);    
    }
*/
//formatage sous forme d'un forme d'un tableau du data
    public function getAll(){
        $sql = "SELECT * FROM ".$this->table;
        $query = self::$instance->prepare($sql);
        $query->execute();
        return $query->fetchAll();    
    }


//.... delete, insert, update

}


