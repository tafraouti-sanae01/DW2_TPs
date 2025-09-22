<?php
session_start();

// On sauvegarde les données pour les garder si erreur
$_SESSION['form_data'] = $_POST;

// Vérification email
$email = $_POST['email'] ?? '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Email invalide !";
    header('Location: formulaire.php');
    exit;
}

// Vérification et upload photo
$photoPath = '';
$extensionsOK = ['jpg', 'jpeg', 'png', 'gif'];
$mimeOK = ['image/jpeg', 'image/png', 'image/gif'];
$tailleMax = 2 * 1024 * 1024; // 2 Mo

if (!empty($_FILES['photo']['name'])) {
    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    $mime = mime_content_type($_FILES['photo']['tmp_name']);
    $taille = $_FILES['photo']['size'];

    if (!in_array($ext, $extensionsOK)) {
        $_SESSION['error'] = "Extension image non autorisée !";
        header('Location: formulaire.php');
        exit;
    }
    if (!in_array($mime, $mimeOK)) {
        $_SESSION['error'] = "Le fichier n'est pas une image valide !";
        header('Location: formulaire.php');
        exit;
    }
    if ($taille > $tailleMax) {
        $_SESSION['error'] = "Image trop volumineuse (max 2 Mo) !";
        header('Location: formulaire.php');
        exit;
    }

    $dossier = 'uploads/';
    if (!is_dir($dossier)) mkdir($dossier, 0755, true);

    $name = uniqid('photo_') . '.' . $ext;
    $photoPath = $dossier . $name;
    move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
} elseif (!empty($_POST['existing_photo'])) {
    $photoPath = $_POST['existing_photo'];
}

$formData = $_POST;
$formData['photo_path'] = $photoPath;
if (isset($formData['modules']) && !is_array($formData['modules'])) {
    $formData['modules'] = explode(',', $formData['modules']);
}
$_SESSION['form_data'] = $formData;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Récapitulatif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-4">
        <h2 class="text-center mb-4">Récapitulatif des informations</h2>
        <table class="table table-bordered">
            <tr>
                <th>Nom</th>
                <td><?= htmlspecialchars($formData['nom'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Prénom</th>
                <td><?= htmlspecialchars($formData['prenom'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Âge</th>
                <td><?= htmlspecialchars($formData['age'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Téléphone</th>
                <td><?= htmlspecialchars($formData['telephone'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($formData['email'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Photo</th>
                <td>
                    <?php if ($photoPath): ?>
                        <img src="<?= htmlspecialchars($photoPath) ?>" width="150" class="img-thumbnail">
                    <?php else: ?>
                        Aucun
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Filière</th>
                <td><?= htmlspecialchars($formData['filiere'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Année</th>
                <td><?= htmlspecialchars($formData['annee'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Modules</th>
                <td><?= !empty($formData['modules']) ? htmlspecialchars(implode(", ", $formData['modules'])) : 'Aucun' ?></td>
            </tr>
            <tr>
                <th>Nombre de projets</th>
                <td><?= htmlspecialchars($formData['nb_projets'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Projets / Stages</th>
                <td><?= nl2br(htmlspecialchars($formData['projets'] ?? '')) ?></td>
            </tr>
            <tr>
                <th>Centres d'intérêt</th>
                <td><?= nl2br(htmlspecialchars($formData['interets'] ?? '')) ?></td>
            </tr>
            <tr>
                <th>Formations</th>
                <td><?= nl2br(htmlspecialchars($formData['formations'] ?? '')) ?></td>
            </tr>
            <tr>
                <th>Compétences</th>
                <td><?= nl2br(htmlspecialchars($formData['competences'] ?? '')) ?></td>
            </tr>
            <tr>
                <th>Langues</th>
                <td><?= htmlspecialchars($formData['langues'] ?? '') ?></td>
            </tr>
        </table>

        <div class="text-center mt-3">
            <form method="post" action="sauvegarde.php" class="d-inline">
                <button type="submit" class="btn btn-success px-4">VALIDER</button>
            </form>
            <a href="formulaire.php" class="btn btn-warning px-4">MODIFIER</a>
        </div>
    </div>
</body>
</html>