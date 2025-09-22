<?php
session_start();

// On recupere les donnees temporaires et les erreurs
$oldData = $_SESSION['form_data'] ?? [];
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['error']); // On supprime l'erreur pour ne pas la garder à l'infini

function old($key, $default = '')
{
    global $oldData;
    return isset($oldData[$key]) ? htmlspecialchars($oldData[$key]) : $default;
}
function selected($name, $value)
{
    global $oldData;
    return (isset($oldData[$name]) && $oldData[$name] == $value) ? 'checked' : '';
}
function checked($name, $value)
{
    global $oldData;
    return (!empty($oldData[$name]) && is_array($oldData[$name]) && in_array($value, $oldData[$name])) ? 'checked' : '';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire Étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-4">
        <h2 class="text-center mb-4">Fiche de Renseignements</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="recap.php" method="post" enctype="multipart/form-data" class="p-4 bg-white shadow rounded">
            <fieldset class="mb-4">
                <legend><b>Renseignements Personnels</b></legend>
                <div class="mb-3">
                    Nom : <input type="text" name="nom" class="form-control" value="<?= old('nom') ?>" required>
                </div>
                <div class="mb-3">
                    Prénom : <input type="text" name="prenom" class="form-control" value="<?= old('prenom') ?>" required>
                </div>
                <div class="mb-3">
                    Age : <input type="number" name="age" class="form-control" value="<?= old('age') ?>" required>
                </div>
                <div class="mb-3">
                    Téléphone : <input type="text" name="telephone" class="form-control" value="<?= old('telephone') ?>">
                </div>
                <div class="mb-3">
                    Email : <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                </div>
                <div class="mb-3">
                    <label>Photo :</label>
                    <?php if (!empty($oldData['photo_path'])): ?>
                        <div class="mb-2">Photo actuelle :<br>
                            <img src="<?= htmlspecialchars($oldData['photo_path']) ?>" width="120" class="img-thumbnail">
                        </div>
                        <input type="hidden" name="existing_photo" value="<?= htmlspecialchars($oldData['photo_path']) ?>">
                        <small class="form-text text-muted">Laissez vide pour conserver la photo actuelle.</small>
                    <?php endif; ?>
                    <input type="file" name="photo" class="form-control">
                </div>
            </fieldset>

            <fieldset class="mb-4">
                <legend><b>Renseignements Académiques</b></legend>
                <label class="form-label">Filière :</label><br>
                <?php $filieres = ["2AP", "GSTR", "GI", "SCM", "GC", "MS"]; ?>
                <?php foreach ($filieres as $f): ?>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="filiere" value="<?= $f ?>" class="form-check-input" <?= selected('filiere', $f) ?>>
                        <label class="form-check-label"><?= $f ?></label>
                    </div>
                <?php endforeach; ?>
                <br><br>

                <label class="form-label">Année :</label><br>
                <?php for ($i = 1; $i <= 3; $i++): ?>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="annee" value="<?= $i ?>" class="form-check-input" <?= selected('annee', "$i") ?>>
                        <label class="form-check-label"><?= $i ?>ème année</label>
                    </div>
                <?php endfor; ?>
                <br><br>

                <label class="form-label">Modules suivis cette année :</label><br>
                <?php $modules = ["Compilation", "Reseaux", "POO", "BD", "Web"]; ?>
                <?php foreach ($modules as $m): ?>
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="modules[]" value="<?= $m ?>" class="form-check-input" <?= checked('modules', $m) ?>>
                        <label class="form-check-label"><?= $m ?></label>
                    </div>
                <?php endforeach; ?>
                <br><br>

                Nombre de projets réalisés cette année :
                <input type="number" name="nb_projets" class="form-control" value="<?= old('nb_projets') ?>" min="0">
            </fieldset>

            <fieldset class="mb-4">
                <legend><b>Informations Supplémentaires</b></legend>
                <div class="mb-3">
                    Projets / Stages réalisés :
                    <textarea name="projets" class="form-control" rows="4"><?= old('projets') ?></textarea>
                </div>
                <div class="mb-3">
                    Centres d’intérêt :
                    <textarea name="interets" class="form-control" rows="3"><?= old('interets') ?></textarea>
                </div>
                <div class="mb-3">
                    Formations :
                    <textarea name="formations" class="form-control" rows="3"><?= old('formations') ?></textarea>
                </div>
                <div class="mb-3">
                    Compétences :
                    <textarea name="competences" class="form-control" rows="3"><?= old('competences') ?></textarea>
                </div>
                <div class="mb-3">
                    Langues parlées :
                    <input type="text" name="langues" class="form-control" value="<?= old('langues') ?>" placeholder="ex: Français(Courant), Anglais(Professionnel)">
                </div>
            </fieldset>

            <div class="text-center">
                <input type="submit" value="Envoyer" class="btn btn-success px-4">
                <input type="reset" value="Effacer" class="btn btn-danger px-4">
            </div>
        </form>
    </div>
</body>
</html>