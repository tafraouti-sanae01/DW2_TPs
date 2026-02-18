<?php
require_once 'db.php';

// Définir le header pour JSON
header('Content-Type: application/json; charset=utf-8');

// Voir toutes les pétitions
if(isset($_POST['action']) && $_POST['action'] == "view") {
    $petitions = toutesLesPetitions();
    $result = [];
    
    foreach($petitions as $petition) {
        $nbSignatures = compterSignatures($petition['IDP']);
        $petition['nbSignatures'] = $nbSignatures;
        $result[] = $petition;
    }
    
    echo json_encode($result);
    exit;
}

// Ajouter une nouvelle pétition
if(isset($_POST['action']) && $_POST['action'] == "insert") {
    $titre = trim($_POST['TitreP']);
    $description = trim($_POST['DescriptionP']);
    $dateFin = trim($_POST['DateFinP']);
    $nomPorteur = trim($_POST['NomPorteurP']);
    $email = trim($_POST['Email']);
    
    // Validation simple
    if(empty($titre) || empty($description) || empty($dateFin) || empty($nomPorteur) || empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Tous les champs sont obligatoires']);
        exit;
    }
    
    if(ajouterPetition($titre, $description, $dateFin, $nomPorteur, $email)) {
        echo json_encode(['success' => true, 'message' => 'Pétition créée avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la création']);
    }
    exit;
}

// Ajouter une signature
if(isset($_POST['action']) && $_POST['action'] == "insertS") {
    $idPetition = intval($_POST['IDP']);
    $nom = trim($_POST['NomS']);
    $prenom = trim($_POST['PrenomS']);
    $pays = trim($_POST['PaysS']);
    $email = trim($_POST['EmailS']);
    
    // Validation simple
    if(empty($idPetition) || empty($nom) || empty($prenom) || empty($pays) || empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Tous les champs sont obligatoires']);
        exit;
    }
    
    if(ajouterSignature($idPetition, $nom, $prenom, $pays, $email)) {
        echo json_encode(['success' => true, 'message' => 'Signature ajoutée avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout de la signature']);
    }
    exit;
}

// Obtenir la liste des pétitions pour le select
if(isset($_POST['action']) && $_POST['action'] == "getPetitionsList") {
    $petitions = toutesLesPetitions();
    echo json_encode($petitions);
    exit;
}

// Obtenir les 5 dernières signatures
if(isset($_POST['action']) && $_POST['action'] == "getLastSignatures") {
    $signatures = dernieresSignatures();
    echo json_encode($signatures);
    exit;
}

// Obtenir la pétition la plus signée
if(isset($_POST['action']) && $_POST['action'] == "getTopPetition") {
    $topPetition = pétitionPlusSignée();
    echo json_encode($topPetition);
    exit;
}

// Modifier une pétition
if(isset($_POST['action']) && $_POST['action'] == "update") {
    $id = intval($_POST['IDP']);
    $titre = trim($_POST['TitreP']);
    $description = trim($_POST['DescriptionP']);
    $dateFin = trim($_POST['DateFinP']);
    $nomPorteur = trim($_POST['NomPorteurP']);
    $email = trim($_POST['Email']);
    
    if(modifierPetition($id, $titre, $description, $dateFin, $nomPorteur, $email)) {
        echo json_encode(['success' => true, 'message' => 'Pétition modifiée avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la modification']);
    }
    exit;
}

// Supprimer une pétition
if(isset($_POST['action']) && $_POST['action'] == "delete") {
    $id = intval($_POST['IDP']);
    
    if(supprimerPetition($id)) {
        echo json_encode(['success' => true, 'message' => 'Pétition supprimée avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
    }
    exit;
}

// Récupérer une pétition pour édition
if(isset($_POST['action']) && $_POST['action'] == "getPetition") {
    $id = intval($_POST['IDP']);
    $petition = pétitionParId($id);
    
    if($petition) {
        echo json_encode($petition);
    } else {
        echo json_encode(['error' => 'Pétition non trouvée']);
    }
    exit;
}
?>