<?php

class Connexion extends PDO {
    
    private static $instance = null;
    
    private function __construct() {
        parent::__construct('mysql:host=localhost;dbname=FacturesElect', 'root', '');
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public static function getInstance(): Connexion {
        if (self::$instance === null) {
            self::$instance = new self();  
        }
        return self::$instance;

        
    }
}
?>
