<?php
class Zones extends Controller{
    function index(){
        // Instanciation de la classe modèle Zone 
        $this->loadModel("Zone");
        $zones = $this->Zone->getAll();
        // compact permet de Créer un tableau à partir de variables et de leur valeur
        $this->loadView("index", compact('zones'));
    }

    public function getZ($idZ){
        $this->loadModel("Zone");
        $zone = $this->Zone->getOne($idZ);
        $this->loadView("oneZ", compact('zone'));
    }

    // Afficher le formulaire pour ajouter une zone
    public function addZ(){
        $this->loadView("addZ");
    }

    // Sauvegarder la nouvelle zone
    public function saveZ(){
        $this->loadModel("Zone");
        
        // Récupérer les données du formulaire
        $nomZ = $_POST['nomZ'];
        $VilleZ = $_POST['VilleZ'];
        $ServicesPublics = $_POST['ServicesPublics'];
        
        // Insérer la zone dans la base de données
        $this->Zone->insert($nomZ, $VilleZ, $ServicesPublics);
        
        // Rediriger vers la liste des zones
        header('Location: ../zones/index');
        exit();
    }

    // Afficher le formulaire pour modifier une zone
    public function updateZ($idZ){
        $this->loadModel("Zone");
        $zone = $this->Zone->getOne($idZ);
        $this->loadView("updateZ", compact('zone'));
    }

    // Sauvegarder les modifications d'une zone
    public function updateZSave($idZ){
        $this->loadModel("Zone");
        
        // Récupérer les données du formulaire
        $nomZ = $_POST['nomZ'];
        $VilleZ = $_POST['VilleZ'];
        $ServicesPublics = $_POST['ServicesPublics'];
        
        // Modifier la zone dans la base de données
        $this->Zone->update($idZ, $nomZ, $VilleZ, $ServicesPublics);
        
        // Rediriger vers la liste des zones
        header('Location: ../index');
        exit();
    }

    // Supprimer une zone
    public function deleteZ($idZ){
        $this->loadModel("Zone");
        
        // Supprimer la zone de la base de données
        $this->Zone->delete($idZ);
        
        // Rediriger vers la liste des zones
        header('Location: ../index');
        exit();
    }
}

