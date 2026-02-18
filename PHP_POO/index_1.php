<?php
require_once 'Client.php';
require_once 'Entreprise.php';
require_once 'Particulier.php';

echo "========================================\n";
echo "TEST DES CLASSES CLIENT\n";
echo "========================================\n\n";

echo "---- Test Particulier ----\n";
$particulier1 = new Particulier(1, "Tafraouti Sanae", "Tetouan");
echo $particulier1; 
echo "Paiement: " . $particulier1->Payer_facture() . "\n";
echo "Réclamation: " . $particulier1->Reclamation() . "\n\n";

echo "---- Test Entreprise ----\n";
$entreprise1 = new Entreprise(2, "Tech Maroc SARL", "Martil");

$entreprise1->Num_Patente = "12345678";
$entreprise1->Type_Societe = "SAP";
$entreprise1->Secteur_Activite = "Informatique";

echo $entreprise1; 
echo "Paiement: " . $entreprise1->Payer_facture() . "\n";
echo "Réclamation: " . $entreprise1->Reclamation() . "\n\n";

echo "---- Test méthodes magiques __get ----\n";
echo "Num Patente: " . $entreprise1->Num_Patente . "\n";
echo "Type Société: " . $entreprise1->Type_Societe . "\n";
echo "Secteur: " . $entreprise1->Secteur_Activite . "\n\n";

$particulier2 = new Particulier(3, "User 2", "Fnideq");
$entreprise2 = new Entreprise(4, "BatiPro SA", "Tanger");

$entreprise2->Num_Patente = "87654321";
$entreprise2->Type_Societe = "SA";
$entreprise2->Secteur_Activite = "Construction";

echo "----------------------------\n";
echo "Nombre de clients: " . Client::$nbr_user . "\n";
echo "----------------------------\n\n";

echo "---- Tous les clients ----\n";
echo $particulier1;
echo $particulier2;
echo $entreprise1;
echo $entreprise2;

echo "========================================\n";
?>
