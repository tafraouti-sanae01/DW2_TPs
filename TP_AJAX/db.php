<?php
// Configuration simple de la base de données
$host = "localhost";
$dbname = "petition";
$username = "root";
$password = "";

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Fonction pour ajouter une pétition
function ajouterPetition($titre, $description, $dateFin, $nomPorteur, $email) {
    global $pdo;
    $sql = "INSERT INTO petitions (TitreP, DescriptionP, DateFinP, NomPorteurP, Email) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$titre, $description, $dateFin, $nomPorteur, $email]);
}

// Fonction pour ajouter une signature
function ajouterSignature($idPetition, $nom, $prenom, $pays, $email) {
    global $pdo;
    $sql = "INSERT INTO signatures (IDP, NomS, PrenomS, PaysS, EmailS) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$idPetition, $nom, $prenom, $pays, $email]);
}

// Fonction pour récupérer toutes les pétitions
function toutesLesPetitions() {
    global $pdo;
    $sql = "SELECT * FROM petitions ORDER BY DateAjoutP DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour compter les signatures d'une pétition
function compterSignatures($idPetition) {
    global $pdo;
    $sql = "SELECT COUNT(*) as total FROM signatures WHERE IDP = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idPetition]);
    $result = $stmt->fetch();
    return $result['total'];
}

// Fonction pour récupérer les 5 dernières signatures
function dernieresSignatures() {
    global $pdo;
    $sql = "SELECT s.*, p.TitreP FROM signatures s 
            JOIN petitions p ON s.IDP = p.IDP 
            ORDER BY s.DateS DESC, s.HeureS DESC 
            LIMIT 5";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour trouver la pétition la plus signée
function pétitionPlusSignée() {
    global $pdo;
    $sql = "SELECT p.*, COUNT(s.IDS) as nbSignatures
            FROM petitions p
            LEFT JOIN signatures s ON p.IDP = s.IDP
            GROUP BY p.IDP
            ORDER BY nbSignatures DESC
            LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction pour modifier une pétition
function modifierPetition($id, $titre, $description, $dateFin, $nomPorteur, $email) {
    global $pdo;
    $sql = "UPDATE petitions SET TitreP=?, DescriptionP=?, DateFinP=?, NomPorteurP=?, Email=? WHERE IDP=?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$titre, $description, $dateFin, $nomPorteur, $email, $id]);
}

// Fonction pour supprimer une pétition
function supprimerPetition($id) {
    global $pdo;
    // D'abord supprimer les signatures
    $sql = "DELETE FROM signatures WHERE IDP = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    
    // Puis supprimer la pétition
    $sql = "DELETE FROM petitions WHERE IDP = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$id]);
}

// Fonction pour récupérer une pétition par ID
function pétitionParId($id) {
    global $pdo;
    $sql = "SELECT * FROM petitions WHERE IDP = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>