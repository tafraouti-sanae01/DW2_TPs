// Variables globales
let petitions = [];

// Initialisation au chargement de la page
$(document).ready(function() {
    chargerPetitions();
    chargerDernieresSignatures();
    chargerPetitionPlusSignee();
    
    // Actualiser toutes les 10 secondes
    setInterval(function() {
        chargerPetitions();
        chargerDernieresSignatures();
        chargerPetitionPlusSignee();
    }, 10000);
});

// Charger toutes les pétitions
function chargerPetitions() {
    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: { action: 'view' },
        dataType: 'json',
        success: function(petitions) {
            afficherPetitions(petitions);
            chargerSelectPetitions();
            mettreAJourStatistiques(petitions);
        },
        error: function() {
            alert('Erreur lors du chargement des pétitions');
        }
    });
}

// Afficher les pétitions
function afficherPetitions(petitions) {
    const container = $('#petitionsContainer');
    
    if (petitions.length === 0) {
        container.html(`
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <h3 class="mt-3">Aucune pétition pour le moment</h3>
                        <p class="text-muted">Commencez par créer votre première pétition !</p>
                    </div>
                </div>
            </div>
        `);
        return;
    }

    let html = '';
    petitions.forEach(function(petition) {
        html += `
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">${petition.TitreP}</h5>
                        <p class="card-text"><strong>Porteur:</strong> ${petition.NomPorteurP}</p>
                        <p class="card-text">${petition.DescriptionP}</p>
                        <p class="card-text"><small class="text-muted">Date de fin: ${petition.DateFinP}</small></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary">${petition.nbSignatures} signature${petition.nbSignatures !== 1 ? 's' : ''}</span>
                            <div class="btn-group" role="group">
                                <button class="btn btn-warning btn-sm" onclick="modifierPetition(${petition.IDP})" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="supprimerPetition(${petition.IDP})" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <button class="btn btn-success btn-sm" onclick="ouvrirModalSignature(${petition.IDP})" title="Signer">
                                    <i class="bi bi-pen"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.html(html);
}

// Mettre à jour les statistiques
function mettreAJourStatistiques(petitions) {
    const totalPetitions = petitions.length;
    let totalSignatures = 0;
    
    petitions.forEach(function(petition) {
        totalSignatures += parseInt(petition.nbSignatures) || 0;
    });
    
    $('#totalPetitions').text(totalPetitions);
    $('#totalSignatures').text(totalSignatures);
}

// Charger les pétitions dans le select (toutes les pétitions)
function chargerSelectPetitions() {
    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: { action: 'getPetitionsList' },
        dataType: 'json',
        success: function(allPetitions) {
            let options = '<option value="">-- Choisir une pétition --</option>';
            allPetitions.forEach(function(petition) {
                options += `<option value="${petition.IDP}">${petition.TitreP}</option>`;
            });
            $('#petitionSelect').html(options);
        },
        error: function() {
            $('#petitionSelect').html('<option value="">Erreur de chargement</option>');
        }
    });
}

// Charger les 5 dernières signatures
function chargerDernieresSignatures() {
    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: { action: 'getLastSignatures' },
        dataType: 'json',
        success: function(signatures) {
            let html = '';
            
            if (signatures && signatures.length > 0) {
                signatures.forEach(function(signature) {
                    html += `
                        <div class="border-bottom pb-2 mb-2">
                            <div class="d-flex justify-content-between">
                                <strong>${signature.PrenomS} ${signature.NomS}</strong>
                                <small class="text-muted">${signature.DateS}</small>
                            </div>
                            <div class="text-muted">
                                <small><i class="bi bi-geo-alt"></i> ${signature.PaysS}</small>
                                <br>
                                <small><i class="bi bi-file-text"></i> ${signature.TitreP}</small>
                            </div>
                        </div>
                    `;
                });
            } else {
                html = '<p class="text-muted text-center">Aucune signature récente</p>';
            }
            
            $('#lastSignatures').html(html);
        },
        error: function() {
            $('#lastSignatures').html('<p class="text-danger">Erreur de chargement</p>');
        }
    });
}

// Charger la pétition la plus signée
function chargerPetitionPlusSignee() {
    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: { action: 'getTopPetition' },
        dataType: 'json',
        success: function(topPetition) {
            if (topPetition && topPetition.nbSignatures > 0) {
                $('#topPetition').html(`
                    <div class="alert alert-warning">
                        <h6 class="alert-heading"><i class="bi bi-trophy"></i> ${topPetition.TitreP}</h6>
                        <p class="mb-0"><strong>Signatures:</strong> ${topPetition.nbSignatures}</p>
                        <p class="mb-0"><strong>Porteur:</strong> ${topPetition.NomPorteurP}</p>
                    </div>
                `);
            } else {
                $('#topPetition').html('<p class="text-muted text-center">Aucune signature pour le moment</p>');
            }
        },
        error: function() {
            $('#topPetition').html('<p class="text-danger">Erreur de chargement</p>');
        }
    });
}

// Ouvrir une modale Bootstrap
function ouvrirModal(modalId) {
    const modal = new bootstrap.Modal(document.getElementById(modalId));
    modal.show();
}

// Fermer une modale Bootstrap
function fermerModal(modalId) {
    const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
    if (modal) {
        modal.hide();
    }
}

// Ouvrir la modale de signature avec une pétition pré-sélectionnée
function ouvrirModalSignature(idPetition) {
    $('#petitionSelect').val(idPetition);
    ouvrirModal('addSignatureModal');
}

// Ajouter une pétition
$('#addPetitionForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: $(this).serialize() + '&action=insert',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert(response.message);
                fermerModal('addPetitionModal');
                $('#addPetitionForm')[0].reset();
                chargerPetitions();
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('Erreur lors de la création');
        }
    });
});

// Ajouter une signature
$('#addSignatureForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: $(this).serialize() + '&action=insertS',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert(response.message);
                fermerModal('addSignatureModal');
                $('#addSignatureForm')[0].reset();
                chargerPetitions();
                chargerDernieresSignatures();
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('Erreur lors de l\'ajout de la signature');
        }
    });
});

// Modifier une pétition
function modifierPetition(id) {
    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: { action: 'getPetition', IDP: id },
        dataType: 'json',
        success: function(petition) {
            $('#edit_IDP').val(petition.IDP);
            $('#edit_TitreP').val(petition.TitreP);
            $('#edit_DescriptionP').val(petition.DescriptionP);
            $('#edit_DateFinP').val(petition.DateFinP);
            $('#edit_NomPorteurP').val(petition.NomPorteurP);
            $('#edit_Email').val(petition.Email);
            ouvrirModal('editPetitionModal');
        },
        error: function() {
            alert('Erreur lors du chargement de la pétition');
        }
    });
}

// Soumettre la modification
$('#editPetitionForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: $(this).serialize() + '&action=update',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert(response.message);
                fermerModal('editPetitionModal');
                chargerPetitions();
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('Erreur lors de la modification');
        }
    });
});

// Supprimer une pétition
function supprimerPetition(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette pétition ?')) {
        $.ajax({
            url: 'action.php',
            type: 'POST',
            data: { action: 'delete', IDP: id },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    chargerPetitions();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Erreur lors de la suppression');
            }
        });
    }
}