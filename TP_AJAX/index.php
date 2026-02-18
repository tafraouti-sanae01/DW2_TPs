<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Pétitions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="card mb-4">
            <div class="card-body text-center">
                <h1 class="display-4 text-primary">
                    <i class="bi bi-file-earmark-text"></i> Gestion des Pétitions
                </h1>
                <p class="lead text-muted">Créez et gérez vos pétitions en toute simplicité</p>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="text-center mb-4">
            <button class="btn btn-primary btn-lg me-3" onclick="ouvrirModal('addPetitionModal')">
                <i class="bi bi-plus-circle"></i> Nouvelle Pétition
            </button>
            <button class="btn btn-success btn-lg" onclick="ouvrirModal('addSignatureModal')">
                <i class="bi bi-pen"></i> Signer une Pétition
            </button>
        </div>

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Pétitions Actives</h5>
                        <h2 class="text-primary" id="totalPetitions">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Signatures Totales</h5>
                        <h2 class="text-success" id="totalSignatures">0</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pétition la plus signée -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-trophy"></i> Pétition la plus signée</h5>
            </div>
            <div class="card-body" id="topPetition">
                <p class="text-muted">Chargement...</p>
            </div>
        </div>

        <!-- Signatures récentes -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> 5 Dernières Signatures</h5>
            </div>
            <div class="card-body" id="lastSignatures">
                <p class="text-muted">Chargement...</p>
            </div>
        </div>

        <!-- Grille des pétitions -->
        <div class="row" id="petitionsContainer">
            <div class="col-12">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-2">Chargement des pétitions...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modale Ajouter Pétition -->
    <div class="modal fade" id="addPetitionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nouvelle Pétition</h5>
                    <button type="button" class="btn-close" onclick="fermerModal('addPetitionModal')"></button>
                </div>
                <form id="addPetitionForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Titre de la Pétition</label>
                            <input type="text" class="form-control" name="TitreP" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="DescriptionP" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date de Fin</label>
                            <input type="date" class="form-control" name="DateFinP" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nom du Porteur</label>
                            <input type="text" class="form-control" name="NomPorteurP" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="Email" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="fermerModal('addPetitionModal')">Annuler</button>
                        <button type="submit" class="btn btn-primary">Créer la Pétition</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modale Modifier Pétition -->
    <div class="modal fade" id="editPetitionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier la Pétition</h5>
                    <button type="button" class="btn-close" onclick="fermerModal('editPetitionModal')"></button>
                </div>
                <form id="editPetitionForm">
                    <div class="modal-body">
                        <input type="hidden" name="IDP" id="edit_IDP">
                        <div class="mb-3">
                            <label class="form-label">Titre de la Pétition</label>
                            <input type="text" class="form-control" name="TitreP" id="edit_TitreP" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="DescriptionP" id="edit_DescriptionP" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date de Fin</label>
                            <input type="date" class="form-control" name="DateFinP" id="edit_DateFinP" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nom du Porteur</label>
                            <input type="text" class="form-control" name="NomPorteurP" id="edit_NomPorteurP" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="Email" id="edit_Email" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="fermerModal('editPetitionModal')">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer les Modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modale Ajouter Signature -->
    <div class="modal fade" id="addSignatureModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Signer une Pétition</h5>
                    <button type="button" class="btn-close" onclick="fermerModal('addSignatureModal')"></button>
                </div>
                <form id="addSignatureForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Choisir une Pétition</label>
                            <select class="form-select" name="IDP" id="petitionSelect" required>
                                <option value="">-- Sélectionner --</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Votre Nom</label>
                            <input type="text" class="form-control" name="NomS" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Votre Prénom</label>
                            <input type="text" class="form-control" name="PrenomS" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pays</label>
                            <input type="text" class="form-control" name="PaysS" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="EmailS" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="fermerModal('addSignatureModal')">Annuler</button>
                        <button type="submit" class="btn btn-success">Signer la Pétition</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
</body>
</html>