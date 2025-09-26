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

// Sauvegarder dans le fichier
file_put_contents("etudiants.txt", $texte, FILE_APPEND);

// Sauvegarder également dans la base de données
try {
    require_once __DIR__ . '/db.php';
    $pdo = get_pdo();

    $modulesStr = '';
    if (!empty($data['modules'])) {
        $modulesStr = is_array($data['modules']) ? implode(', ', $data['modules']) : (string)$data['modules'];
    }

    $sql = "INSERT INTO students (email, nom, prenom, age, telephone, filiere, annee, modules, nb_projets, projets, interets, formations, competences, langues, photo_path)
            VALUES (:email, :nom, :prenom, :age, :telephone, :filiere, :annee, :modules, :nb_projets, :projets, :interets, :formations, :competences, :langues, :photo_path)
            ON DUPLICATE KEY UPDATE
              nom = VALUES(nom), prenom = VALUES(prenom), age = VALUES(age), telephone = VALUES(telephone),
              filiere = VALUES(filiere), annee = VALUES(annee), modules = VALUES(modules), nb_projets = VALUES(nb_projets),
              projets = VALUES(projets), interets = VALUES(interets), formations = VALUES(formations),
              competences = VALUES(competences), langues = VALUES(langues), photo_path = VALUES(photo_path)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':email' => $data['email'] ?? null,
        ':nom' => $data['nom'] ?? null,
        ':prenom' => $data['prenom'] ?? null,
        ':age' => !empty($data['age']) ? (int)$data['age'] : null,
        ':telephone' => $data['telephone'] ?? null,
        ':filiere' => $data['filiere'] ?? null,
        ':annee' => !empty($data['annee']) ? (int)$data['annee'] : null,
        ':modules' => $modulesStr,
        ':nb_projets' => !empty($data['nb_projets']) ? (int)$data['nb_projets'] : null,
        ':projets' => $data['projets'] ?? null,
        ':interets' => $data['interets'] ?? null,
        ':formations' => $data['formations'] ?? null,
        ':competences' => $data['competences'] ?? null,
        ':langues' => $data['langues'] ?? null,
        ':photo_path' => $data['photo_path'] ?? null,
    ]);
} catch (Throwable $e) {
    // Si DB non disponible, on continue sans bloquer
}

// on nettoie la session temporaire du formulaire
unset($_SESSION['form_data']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Informations sauvegardées</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="alert alert-success">
                    <h2 class="text-success">Informations sauvegardées !</h2>
                    <p>Vos données ont été enregistrées avec succès.</p>
                </div>
                
                <div class="mt-4">
                    <a href="generate_pdf.php" class="btn btn-primary btn-lg me-3">
                        Télécharger CV (PDF)
                    </a>
                    <a href="formulaire.php" class="btn btn-outline-secondary btn-lg">
                        Retour au formulaire
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>