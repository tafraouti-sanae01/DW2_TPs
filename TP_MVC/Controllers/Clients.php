<?php
class Clients extends Controller{
    function index(){
        // Instanciation de la classe modèle Client 
        $this->loadModel("Client");
        $clients=$this->Client->getAll();
        // compact permet de Créer un tableau à partir de variables et de leur valeur
        $this->loadView("index", compact('clients'));
    }
    
    
    //$data sous forme d'un tableau
    public function getC($id){
        $this->loadModel("Client");
        $client=$this->Client->getOne($id);
        // compact permet de Créer un tableau à partir de variables et de leur valeur
        $this->loadView("oneC", compact('client'));
    //récupération de $data sous forme d'objet est possible
    }

    public function getClientV($ville){
        $this->loadModel("Client");
        $clients=$this->Client->getCity($ville);
        var_dump($clients);
    //$this->loadView("clientV", compact('client'));
    }

    // Afficher le formulaire pour ajouter un client
    public function addC(){
        $this->loadView("addC");
    }

    // Sauvegarder le nouveau client
    // traitement du formulaire d'ajout
    public function saveC(){
        $this->loadModel("Client");
        
        // Récupérer les données du formulaire
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $adresse = $_POST['adresse'];
        
        // Insérer le client dans la base de données
        $this->Client->insert($nom, $prenom, $adresse);
        
        // Rediriger vers la liste des clients
        header('Location: ../clients/index');
        exit();
    }

    // Afficher le formulaire pour modifier un client
    public function updateC($id){
        $this->loadModel("Client");
        $client = $this->Client->getOne($id);
        $this->loadView("updateC", compact('client'));
    }

    // Sauvegarder les modifications d'un client
    public function updateCSave($id){
        $this->loadModel("Client");
        
        // Récupérer les données du formulaire
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $adresse = $_POST['adresse'];
        
        // Modifier le client dans la base de données
        $this->Client->update($id, $nom, $prenom, $adresse);
        
        // Rediriger vers la liste des clients
        header('Location: ../index');
        exit();
    }

    // Supprimer un client
    public function deleteC($id){
        $this->loadModel("Client");
        
        // Supprimer le client de la base de données
        $this->Client->delete($id);
        
        // Rediriger vers la liste des clients
        header('Location: ../index');
        exit();
    }
}