<?php
session_start();

if (empty($_SESSION['form_data'])) {
    die("Aucune donnée à sauvegarder. <a href='formulaire.php'>Retour</a>");
}

$data = $_SESSION['form_data'];

// Préparer le texte à sauvegarder (format lisible)
$texte  = "============================\n";
$texte .= "Nom : " . ($data['nom'] ?? '') . "\n";
$texte .= "Prénom : " . ($data['prenom'] ?? '') . "\n";
$texte .= "Âge : " . ($data['age'] ?? '') . "\n";
$texte .= "Téléphone : " . ($data['telephone'] ?? '') . "\n";
$texte .= "Email : " . ($data['email'] ?? '') . "\n";
$texte .= "Filière : " . ($data['filiere'] ?? '') . "\n";
$texte .= "Année : " . ($data['annee'] ?? '') . "\n";
$texte .= "Modules : " . (!empty($data['modules']) ? implode(", ", $data['modules']) : "Aucun") . "\n";
$texte .= "Nombre de projets : " . ($data['nb_projets'] ?? '') . "\n";
$texte .= "Projets / Stages : " . ($data['projets'] ?? '') . "\n";
$texte .= "Centres d'intérêt : " . ($data['interets'] ?? '') . "\n";
$texte .= "Formations : " . ($data['formations'] ?? '') . "\n";
$texte .= "Compétences : " . ($data['competences'] ?? '') . "\n";
$texte .= "Langues : " . ($data['langues'] ?? '') . "\n";
if (!empty($data['photo_path'])) {
    $texte .= "Photo (chemin) : " . $data['photo_path'] . "\n";
}
$texte .= "-------------------------------\n";

file_put_contents("etudiants.txt", $texte, FILE_APPEND);

// on nettoie la session temporaire
unset($_SESSION['form_data']);

echo "<div style='text-align:center; margin-top:50px; font-family:Arial'>";
echo "<h2 style='color:green'> Informations sauvegardées !</h2>";
echo "<a href='formulaire.php' style='display:inline-block; margin-top:20px; padding:10px 20px; background:#007bff; color:#fff; text-decoration:none; border-radius:5px;'>Retour au formulaire</a>";
echo "</div>";
