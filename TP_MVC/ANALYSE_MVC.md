# Analyse Complète de l'Architecture MVC

## 📋 Vue d'ensemble du projet

Ce projet implémente une architecture **MVC (Model-View-Controller)** en PHP pour gérer des clients et des zones. L'architecture MVC sépare l'application en trois couches distinctes :

- **Model (Modèle)** : Gère les données et la logique métier
- **View (Vue)** : Présente les données à l'utilisateur
- **Controller (Contrôleur)** : Coordonne les interactions entre le modèle et la vue

---

## 🗂️ Structure des dossiers

```
MVC_FE/
├── Controllers/      # Contrôleurs (logique de l'application)
├── Models/          # Modèles (accès aux données)
├── Views/           # Vues (présentation)
└── index.php        # Point d'entrée (routage)
```

---

## 📄 ANALYSE DÉTAILLÉE DES FICHIERS

### 1. **index.php** - Point d'entrée et routeur

```php
<?php
```
**Ligne 1** : Ouverture de la balise PHP.

```php
// Chemin vers index.php
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));
```
**Ligne 4** : 
- Définit la constante `ROOT` qui contient le chemin absolu vers la racine du projet
- `$_SERVER['SCRIPT_FILENAME']` contient le chemin complet du script actuel (ex: `C:\xampp\htdocs\MVC_FE\index.php`)
- `str_replace('index.php', '', ...)` supprime 'index.php' pour obtenir le chemin du dossier
- Résultat : `C:\xampp\htdocs\MVC_FE\`

```php
require_once ROOT . 'Controllers/Controller.php';
require_once ROOT . 'Models/Model.php';
```
**Lignes 5-6** : 
- Charge les classes abstraites de base (Controller et Model)
- `require_once` garantit qu'un fichier n'est inclus qu'une seule fois
- Ces classes définissent la structure de base pour tous les contrôleurs et modèles

```php
// récupérer les info de l URL
$url = [];
if (isset($_GET['url'])) {
    $url = explode('/', $_GET['url']);
}
```
**Lignes 8-12** : 
- Initialise un tableau `$url` vide
- Vérifie si le paramètre `url` existe dans `$_GET` (ex: `index.php?url=clients/index`)
- `explode('/', ...)` divise l'URL en segments (ex: `['clients', 'index']`)
- **Concept MVC** : Le routage permet de mapper les URLs aux contrôleurs et actions

```php
//instanciation de l objet contrôleur
if (!empty($url) && $url[0] != '') {
    $controller = ucfirst($url[0]);
```
**Lignes 14-16** : 
- Vérifie si l'URL contient au moins un segment
- `ucfirst($url[0])` met la première lettre en majuscule (ex: 'clients' → 'Clients')
- Le premier segment détermine le contrôleur à utiliser

```php
    // Tester si le Controleur exist
    if (file_exists(ROOT . 'Controllers/' . $controller . '.php')) {
        if (isset($url[1])) {
            $action = $url[1];
        } else {
            $action =  'index';
        }
```
**Lignes 17-23** : 
- Vérifie si le fichier du contrôleur existe
- Si un deuxième segment existe dans l'URL, c'est l'action (méthode) à appeler
- Sinon, l'action par défaut est `index`

```php
        require_once ROOT . 'Controllers/' . $controller . '.php';

        if (method_exists($controller, $action)) {            
            $controller = new $controller;
            unset($url[0]);
            unset($url[1]);
            //call_user_func_array permet d'appeler une fonction en lui faisant passer des paramètres sous forme de tableau.
            call_user_func_array([$controller, $action], $url);
        }
```
**Lignes 24-32** : 
- Charge le fichier du contrôleur
- Vérifie si la méthode (action) existe dans la classe
- Instancie le contrôleur dynamiquement (ex: `new Clients()`)
- Supprime les deux premiers éléments de `$url` (contrôleur et action)
- `call_user_func_array` appelle la méthode avec les paramètres restants
- **Exemple** : URL `clients/getC/5` → appelle `Clients->getC(5)`

```php
    } else {
        require ROOT . 'Views/404.php';
    }
} else {
    require_once(ROOT . 'controllers/Main.php');
    $controller = new Main();
    $controller->index();
}
```
**Lignes 33-40** : 
- Si le contrôleur n'existe pas, affiche la page 404
- Si aucune URL n'est fournie, charge le contrôleur `Main` par défaut
- Appelle la méthode `index()` pour afficher la page d'accueil

---

### 2. **Controllers/Controller.php** - Classe abstraite de base

```php
<?php
abstract class Controller
{
```
**Lignes 1-2** : 
- Classe abstraite : ne peut pas être instanciée directement
- Sert de classe parente pour tous les contrôleurs
- **Concept MVC** : Le contrôleur coordonne les interactions

```php
    public function loadModel(string $model)
    {
        // initiation du model dans la classe fille
        require_once(ROOT . 'models/' . $model . '.php');
        $this->$model = new $model();
    }
```
**Lignes 4-9** : 
- Méthode pour charger dynamiquement un modèle
- `require_once` charge le fichier du modèle
- `$this->$model` crée une propriété dynamique (ex: `$this->Client`)
- Instancie le modèle et le stocke dans le contrôleur
- **Concept MVC** : Le contrôleur utilise le modèle pour accéder aux données

```php
    public function loadView(string $view, array $data = [])
    {
        // Récupère les données et les extraits sous forme de variables
        extract($data);
        require_once(ROOT . 'Views/' . strtolower(get_class($this)) . '/' . $view . '.php');
        // remplacer $data pour un objet
    }
```
**Lignes 11-17** : 
- Méthode pour charger une vue
- `extract($data)` transforme un tableau associatif en variables (ex: `['clients' => $clients]` → `$clients`)
- `get_class($this)` obtient le nom de la classe actuelle (ex: 'Clients')
- `strtolower(...)` convertit en minuscules pour le chemin (ex: 'clients')
- Construit le chemin : `Views/clients/index.php`
- **Concept MVC** : Le contrôleur passe les données à la vue pour l'affichage

---

### 3. **Controllers/Main.php** - Contrôleur principal

```php
<?php 
class Main extends Controller{
```
**Lignes 1-2** : 
- Classe `Main` hérite de `Controller`
- Contrôleur pour la page d'accueil

```php
    public function index(){
    require (ROOT . '/Views/home.php');
}
```
**Lignes 3-5** : 
- Méthode `index()` : action par défaut
- Charge directement la vue `home.php` (sans passer par `loadView`)
- Affiche la page d'accueil avec les liens de navigation

---

### 4. **Controllers/Clients.php** - Contrôleur des clients

```php
<?php
class Clients extends Controller{
```
**Ligne 2** : Classe pour gérer toutes les opérations sur les clients

```php
    function index(){
        // Instanciation de la classe modèle Client 
        $this->loadModel("Client");
        $clients=$this->Client->getAll();
        // compact permet de Créer un tableau à partir de variables et de leur valeur
        $this->loadView("index", compact('clients'));
    }
```
**Lignes 3-9** : 
- **Action `index()`** : Liste tous les clients
- `loadModel("Client")` charge le modèle Client
- `$this->Client->getAll()` récupère tous les clients depuis la base de données
- `compact('clients')` crée un tableau `['clients' => $clients]`
- `loadView("index", ...)` charge la vue `Views/clients/index.php` avec les données
- **Flux MVC** : Controller → Model → View

```php
    //$data sous forme d'un tableau
    public function getC($id){
        $this->loadModel("Client");
        $client=$this->Client->getOne($id);
        // compact permet de Créer un tableau à partir de variables et de leur valeur
        $this->loadView("oneC", compact('client'));
    //récupération de $data sous forme d'objet est possible
    }
```
**Lignes 12-19** : 
- **Action `getC($id)`** : Affiche un client spécifique
- Reçoit l'ID en paramètre depuis l'URL
- `getOne($id)` récupère un seul client
- Charge la vue `oneC.php` pour afficher les détails

```php
    public function getClientV($ville){
        $this->loadModel("Client");
        $clients=$this->Client->getCity($ville);
        var_dump($clients);
    //$this->loadView("clientV", compact('client'));
    }
```
**Lignes 21-26** : 
- **Action `getClientV($ville)`** : Recherche par ville
- Méthode de test (utilise `var_dump` au lieu d'une vue)
- Commentée : la vue n'est pas encore implémentée

```php
    // Afficher le formulaire pour ajouter un client
    public function addC(){
        $this->loadView("addC");
    }
```
**Lignes 28-31** : 
- **Action `addC()`** : Affiche le formulaire d'ajout
- Pas besoin de modèle, juste la vue

```php
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
```
**Lignes 33-49** : 
- **Action `saveC()`** : Traite le formulaire d'ajout
- Récupère les données POST du formulaire
- Appelle `insert()` du modèle pour sauvegarder
- Redirige vers la liste après l'ajout (pattern PRG : Post-Redirect-Get)

```php
    // Afficher le formulaire pour modifier un client
    public function updateC($id){
        $this->loadModel("Client");
        $client = $this->Client->getOne($id);
        $this->loadView("updateC", compact('client'));
    }
```
**Lignes 51-56** : 
- **Action `updateC($id)`** : Affiche le formulaire de modification
- Récupère le client à modifier
- Prépare la vue avec les données existantes

```php
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
```
**Lignes 58-73** : 
- **Action `updateCSave($id)`** : Traite la modification
- Même logique que `saveC()` mais avec `update()`

```php
    // Supprimer un client
    public function deleteC($id){
        $this->loadModel("Client");
        
        // Supprimer le client de la base de données
        $this->Client->delete($id);
        
        // Rediriger vers la liste des clients
        header('Location: ../index');
        exit();
    }
```
**Lignes 75-85** : 
- **Action `deleteC($id)`** : Supprime un client
- Appelle `delete()` du modèle
- Redirige après suppression

---

### 5. **Controllers/Zones.php** - Contrôleur des zones

Structure identique à `Clients.php` mais pour les zones :
- `index()` : Liste des zones
- `getZ($idZ)` : Détails d'une zone
- `addZ()` : Formulaire d'ajout
- `saveZ()` : Sauvegarde
- `updateZ($idZ)` : Formulaire de modification
- `updateZSave($idZ)` : Sauvegarde modification
- `deleteZ($idZ)` : Suppression

---

### 6. **Models/Model.php** - Classe abstraite de base

```php
<?php
abstract class Model{
```
**Ligne 2** : Classe abstraite pour tous les modèles

```php
    // Informations de la base de données
    private $dns = "mysql:host=localhost;dbname=FEMVC";
    private $login = "root";
    private $mdp = "";
```
**Lignes 3-6** : 
- Configuration de la connexion à la base de données
- `$dns` : Data Source Name (chaîne de connexion PDO)
- `$login` et `$mdp` : Identifiants MySQL

```php
    // Attribut statique qui contiendra l'unique instance de Model
    protected static $instance = null;
```
**Ligne 9** : 
- **Pattern Singleton** : Une seule connexion à la base de données
- `static` : Partagé entre toutes les instances
- `protected` : Accessible aux classes filles

```php
    public $table;
    public $id;
```
**Lignes 11-12** : 
- Propriétés publiques pour le nom de la table et l'ID
- Sera définies dans les classes filles

```php
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
        }
    }
```
**Lignes 17-31** : 
- **Méthode Singleton** : Crée la connexion si elle n'existe pas
- `new PDO(...)` : Crée la connexion à MySQL
- `setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)` : Active les exceptions pour les erreurs
- `SET NAMES 'utf8'` : Configure l'encodage UTF-8 (note: il y a une faute de frappe "nameS" au lieu de "NAMES")
- Gestion des erreurs avec `try/catch`

```php
    public function getOne($id)
    {
        $this->id=$id;
        $sql = "SELECT * FROM ".$this->table." WHERE id='".$this->id."'";
        $query = self::$instance->prepare($sql);
        $query->execute();
        return $query->fetch();
    }
```
**Lignes 33-40** : 
- **Méthode générique** : Récupère un enregistrement par ID
- Construit la requête SQL dynamiquement avec `$this->table`
- `prepare()` : Prépare la requête (sécurité)
- `execute()` : Exécute la requête
- `fetch()` : Retourne une ligne sous forme de tableau associatif
- ⚠️ **Note de sécurité** : Injection SQL possible (devrait utiliser des paramètres liés)

```php
    public function getAll(){
        $sql = "SELECT * FROM ".$this->table;
        $query = self::$instance->prepare($sql);
        $query->execute();
        return $query->fetchAll();    
    }
```
**Lignes 52-57** : 
- **Méthode générique** : Récupère tous les enregistrements
- `fetchAll()` : Retourne toutes les lignes sous forme de tableau
- Utilisée par `Clients->index()` et `Zones->index()`

---

### 7. **Models/Client.php** - Modèle Client

```php
<?php
class Client extends Model
{
    public function __construct()
    {
        $this->table = "Client";
        self::getModel();
    }
```
**Lignes 1-7** : 
- Classe `Client` hérite de `Model`
- Constructeur définit le nom de la table
- Appelle `getModel()` pour initialiser la connexion

```php
    public function getCity(string $ville)
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE adresse = '" . $ville."'";
        $query = self::$instance->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }
```
**Lignes 10-16** : 
- **Méthode spécifique** : Recherche par ville
- Construit une requête avec condition WHERE
- ⚠️ **Injection SQL possible** : Devrait utiliser des paramètres liés

```php
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
```
**Lignes 18-29** : 
- **Méthode `insert()`** : Ajoute un nouveau client
- ✅ **Sécurisé** : Utilise des paramètres liés (`:nom`, `:prenom`, `:adresse`)
- `execute([...])` : Passe les valeurs de manière sécurisée
- `lastInsertId()` : Retourne l'ID du nouvel enregistrement

```php
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
```
**Lignes 31-42** : 
- **Méthode `update()`** : Modifie un client existant
- ✅ **Sécurisé** : Utilise des paramètres liés

```php
    // Méthode pour supprimer un client
    public function delete($id)
    {
        $sql = "DELETE FROM " . $this->table . " WHERE id=:id";
        $query = self::$instance->prepare($sql);
        $query->execute([':id' => $id]);
    }
```
**Lignes 44-50** : 
- **Méthode `delete()`** : Supprime un client
- ✅ **Sécurisé** : Utilise des paramètres liés

---

### 8. **Models/Zone.php** - Modèle Zone

Structure similaire à `Client.php` :
- `getOne($idZ)` : Surcharge pour utiliser `idZ` au lieu de `id`
- `insert()`, `update()`, `delete()` : Opérations CRUD pour les zones

---

### 9. **Views/home.php** - Page d'accueil

```php
<h2><a href="Clients/index"> >> Gérer les clients</a></h2>
<h2><a href="Zones/index"> >> Gérer les Zone</a></h2>
```
**Lignes 1-2** : 
- Liens de navigation vers les différentes sections
- URLs relatives vers les contrôleurs

```php
<?php
echo 'page home';
```
**Lignes 4-5** : Message simple pour identifier la page

---

### 10. **Views/clients/index.php** - Liste des clients

```php
<h1> Liste des clients </h1>
<p><a href="../clients/addC"> >> Ajouter un client</a></p>
```
**Lignes 1-2** : Titre et lien pour ajouter un client

```php
<table border="1">
<tr>
    <th>Nom</th>
    <th>Prénom</th>
    <th>Actions</th>
</tr>
```
**Lignes 3-8** : En-tête du tableau HTML

```php
<?php foreach($clients as $client):?>
<tr>
    <td><?= $client['nom'] ?></td>
    <td><?= $client['prenom'] ?></td> 
    <td>
        <a href="../clients/getC/<?=$client['id']?>">Afficher</a> | 
        <a href="../clients/updateC/<?=$client['id']?>">Modifier</a> | 
        <a href="../clients/deleteC/<?=$client['id']?>" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</a>
    </td>
</tr>
<?php endforeach ?>
```
**Lignes 15-25** : 
- **Boucle PHP** : Parcourt le tableau `$clients` (passé par le contrôleur)
- `<?= ... ?>` : Syntaxe courte pour `<?php echo ... ?>`
- Affiche les données de chaque client
- Liens vers les actions : afficher, modifier, supprimer
- JavaScript `confirm()` pour confirmer la suppression

---

### 11. **Views/clients/addC.php** - Formulaire d'ajout

```php
<h1> Ajouter un nouveau client </h1>

<form method="POST" action="../clients/saveC">
```
**Lignes 1-3** : 
- Formulaire HTML avec méthode POST
- Action pointe vers `clients/saveC` (contrôleur)

```php
    <p>
        <label>Nom :</label><br>
        <input type="text" name="nom" required>
    </p>
```
**Lignes 4-7** : 
- Champ de formulaire pour le nom
- `required` : Validation HTML5 (champ obligatoire)

Même structure pour prénom et adresse.

---

### 12. **Views/clients/oneC.php** - Détails d'un client

Affiche les détails complets d'un client dans un tableau, avec liens pour modifier/supprimer.

---

### 13. **Views/clients/updateC.php** - Formulaire de modification

Identique à `addC.php` mais :
- Les champs sont pré-remplis avec `value="<?= $client['nom'] ?>"`
- L'action pointe vers `updateCSave`

---

### 14. **Views/zones/** - Vues des zones

Structure identique aux vues clients mais adaptée aux zones (nomZ, VilleZ, Services publics).

---

### 15. **Views/404.php** - Page d'erreur

Page simple affichant un message d'erreur 404.

---

### 16. **DB_FEMVC.sql** - Script de base de données

```sql
CREATE DATABASE IF NOT EXISTS `femvc` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```
**Ligne 23** : Crée la base de données `femvc`

```sql
CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `nom` text NOT NULL,
  `prenom` text NOT NULL,
  `adresse` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```
**Lignes 32-37** : 
- Crée la table `client` avec 4 colonnes
- `id` : Clé primaire auto-incrémentée
- `nom`, `prenom`, `adresse` : Champs texte

```sql
INSERT INTO `client` (...) VALUES
(1, 'Ada', 'Lovelace', 'Tetouan'),
...
```
**Lignes 43-50** : Insère des données de test

---

## 🔄 FLUX MVC COMPLET - Exemple : Lister les clients

1. **URL** : `index.php?url=clients/index`
2. **index.php** : 
   - Parse l'URL → `['clients', 'index']`
   - Charge `Controllers/Clients.php`
   - Instancie `Clients`
   - Appelle `Clients->index()`

3. **Controllers/Clients.php** :
   - `index()` charge le modèle `Client`
   - Appelle `$this->Client->getAll()`
   - Récupère les données
   - Charge la vue `Views/clients/index.php` avec les données

4. **Models/Client.php** :
   - `getAll()` exécute `SELECT * FROM Client`
   - Retourne le tableau de clients

5. **Views/clients/index.php** :
   - Reçoit `$clients` (via `extract()`)
   - Affiche le tableau HTML avec les données

6. **Résultat** : Page HTML avec la liste des clients

---

## 🎯 CONCEPTS MVC EXPLIQUÉS

### **Séparation des responsabilités**

- **Model** : Accès aux données, requêtes SQL, logique métier
- **View** : Présentation, HTML, affichage
- **Controller** : Coordination, routage, traitement des requêtes

### **Avantages du MVC**

1. **Maintenabilité** : Code organisé et modulaire
2. **Réutilisabilité** : Modèles et vues réutilisables
3. **Testabilité** : Chaque couche testable indépendamment
4. **Évolutivité** : Facile d'ajouter de nouvelles fonctionnalités

### **Patterns utilisés**

1. **Singleton** : Une seule connexion à la base de données
2. **Routage** : Mapping URL → Contrôleur/Action
3. **CRUD** : Create, Read, Update, Delete
4. **Template** : Vues séparées de la logique

---

## ⚠️ POINTS D'AMÉLIORATION

1. **Sécurité** : 
   - Injection SQL dans `getOne()` et `getCity()` (utiliser des paramètres liés)
   - Validation des données d'entrée
   - Protection CSRF pour les formulaires

2. **Code** :
   - Faute de frappe dans `Model.php` ligne 23 : "nameS" → "NAMES"
   - Incohérence : `index.php` ligne 37 utilise `controllers` (minuscule) au lieu de `Controllers`

3. **Architecture** :
   - Ajouter une classe de configuration pour les paramètres de BDD
   - Implémenter une gestion d'erreurs centralisée
   - Ajouter un système de validation

---

## 📚 RÉSUMÉ

Ce projet implémente correctement l'architecture MVC avec :
- ✅ Routage dynamique
- ✅ Séparation Model/View/Controller
- ✅ CRUD complet pour Clients et Zones
- ✅ Réutilisation de code (classes abstraites)
- ✅ Pattern Singleton pour la connexion BDD

L'architecture est claire et suit les principes MVC fondamentaux.

