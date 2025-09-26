<?php
// Essayer de charger le dernier étudiant depuis la base (si disponible), sinon fallback fichier
$data = [];

try {
    require_once __DIR__ . '/db.php';
    $pdo = get_pdo();
    $stmt = $pdo->query('SELECT * FROM students ORDER BY email DESC LIMIT 1');
    $row = $stmt->fetch();
    if ($row) {
        $data = [
            'Nom' => $row['nom'] ?? '',
            'Prénom' => $row['prenom'] ?? '',
            'Âge' => $row['age'] ?? '',
            'Téléphone' => $row['telephone'] ?? '',
            'Email' => $row['email'] ?? '',
            'Filière' => $row['filiere'] ?? '',
            'Année' => $row['annee'] ?? '',
            'Modules' => $row['modules'] ?? '',
            'Nombre de projets' => $row['nb_projets'] ?? '',
            'Projets / Stages' => $row['projets'] ?? '',
            'Centres d\'intérêt' => $row['interets'] ?? '',
            'Formations' => $row['formations'] ?? '',
            'Compétences' => $row['competences'] ?? '',
            'Langues' => $row['langues'] ?? '',
            'Photo (chemin)' => $row['photo_path'] ?? '',
        ];
    }
} catch (Throwable $e) {
    // Ignore DB errors, fallback to file
}

if (empty($data)) {
    // Fallback: lire le dernier bloc du fichier
    // Vérifier que le fichier existe
    if (!file_exists('etudiants.txt')) { 
        die("Aucun fichier de données trouvé. <a href='formulaire.php'>Retour</a>");
    }
    // Lire le fichier et récupérer les données du dernier étudiant
    $fileContent = file_get_contents('etudiants.txt');
    $sections = explode("============================", $fileContent);
    $lastSection = trim(end($sections));
    
    if (empty($lastSection)) {
        die("Aucune donnée d'étudiant trouvée. <a href='formulaire.php'>Retour</a>");
    }
    // Parser les données du dernier étudiant
    $lines = explode("\n", $lastSection);
    foreach ($lines as $line) {
        if (strpos($line, ' : ') !== false) {
            $parts = explode(' : ', $line, 2);
            $key = trim($parts[0]);
            $value = trim($parts[1]);
            $data[$key] = $value;
        }
    }
}

// Generate PDF with FPDF
if (file_exists('fpdf186/fpdf.php')) {
    try {
        require_once 'fpdf186/fpdf.php';
        
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Add photo at the top if available
        if (!empty($data['Photo (chemin)']) && file_exists($data['Photo (chemin)'])) {
            // Center the photo
            $pdf->Image($data['Photo (chemin)'], 85, 10, 40, 50); // x=85, y=10, width=40, height=50
            $pdf->Ln(60); // Space after photo
        } else {
            // If no photo, just add some space at top
            $pdf->Ln(10);
        }
        
        // Name as title (no "CURRICULUM VITAE")
        $pdf->SetFont('Arial', 'B', 18);
        $name = ($data['Prénom'] ?? '') . ' ' . ($data['Nom'] ?? '');
        $pdf->Cell(0, 12, mb_convert_encoding($name, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $pdf->Ln(10);
        
        // Personal Information Section
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'INFORMATIONS PERSONNELLES', 0, 1);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Ln(3);
        
        $pdf->Cell(0, 6, 'Nom: ' . mb_convert_encoding($data['Nom'] ?? '', 'ISO-8859-1', 'UTF-8'), 0, 1);
        $pdf->Cell(0, 6, 'Prenom: ' . mb_convert_encoding($data['Prénom'] ?? '', 'ISO-8859-1', 'UTF-8'), 0, 1);
        $pdf->Cell(0, 6, 'Age: ' . ($data['Âge'] ?? '') . ' ans', 0, 1);
        $pdf->Cell(0, 6, 'Telephone: ' . ($data['Téléphone'] ?? ''), 0, 1);
        $pdf->Cell(0, 6, 'Email: ' . ($data['Email'] ?? ''), 0, 1);
        
        // Languages only if filled
        if (!empty($data['Langues']) && trim($data['Langues']) !== '') {
            $pdf->Cell(0, 6, 'Langues: ' . mb_convert_encoding($data['Langues'], 'ISO-8859-1', 'UTF-8'), 0, 1);
        }
        
        $pdf->Ln(8);
        
        // Academic Information Section
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'FORMATION ACADEMIQUE', 0, 1);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Ln(3);
        
        $pdf->Cell(0, 6, 'Filiere: ' . mb_convert_encoding($data['Filière'] ?? '', 'ISO-8859-1', 'UTF-8'), 0, 1);
        $pdf->Cell(0, 6, 'Annee: ' . ($data['Année'] ?? '') . 'eme annee', 0, 1);
        
        // Modules only if not "Aucun"
        if (!empty($data['Modules']) && $data['Modules'] !== 'Aucun') {
            $pdf->Cell(0, 6, 'Modules: ' . mb_convert_encoding($data['Modules'], 'ISO-8859-1', 'UTF-8'), 0, 1);
        }
        
        // Number of projects if filled
        if (!empty($data['Nombre de projets']) && trim($data['Nombre de projets']) !== '') {
            $pdf->Cell(0, 6, 'Projets realises: ' . ($data['Nombre de projets']), 0, 1);
        }
        
        // Optional sections - only if filled
        
        // Formations supplémentaires
        if (!empty($data['Formations']) && trim($data['Formations']) !== '') {
            $pdf->Ln(8);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 8, 'FORMATIONS SUPPLEMENTAIRES', 0, 1);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Ln(3);
            $pdf->MultiCell(0, 5, mb_convert_encoding($data['Formations'], 'ISO-8859-1', 'UTF-8'));
        }
        
        // Compétences
        if (!empty($data['Compétences']) && trim($data['Compétences']) !== '') {
            $pdf->Ln(8);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 8, 'COMPETENCES', 0, 1);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Ln(3);
            $pdf->MultiCell(0, 5, mb_convert_encoding($data['Compétences'], 'ISO-8859-1', 'UTF-8'));
        }
        
        // Projets & Stages
        if (!empty($data['Projets / Stages']) && trim($data['Projets / Stages']) !== '') {
            $pdf->Ln(8);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 8, 'PROJETS & STAGES', 0, 1);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Ln(3);
            $pdf->MultiCell(0, 5, mb_convert_encoding($data['Projets / Stages'], 'ISO-8859-1', 'UTF-8'));
        }
        
        // Centres d'intérêt
        if (!empty($data['Centres d\'intérêt']) && trim($data['Centres d\'intérêt']) !== '') {
            $pdf->Ln(8);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 8, 'CENTRES D\'INTERET', 0, 1);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Ln(3);
            $pdf->MultiCell(0, 5, mb_convert_encoding($data['Centres d\'intérêt'], 'ISO-8859-1', 'UTF-8'));
        }
        
        // Footer
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 6, 'CV genere le ' . date('d/m/Y à H:i'), 0, 1, 'C');
        
        // Output PDF - ALWAYS downloads as PDF
        $filename = 'CV_' . preg_replace('/[^a-zA-Z0-9]/', '_', ($data['Prénom'] ?? '') . '_' . ($data['Nom'] ?? '')) . '.pdf';
        $pdf->Output('D', $filename);
        exit;
        
    } catch (Exception $e) {
        die("FPDF Error: " . $e->getMessage() . "<br><a href='formulaire.php'>Retour</a>");
    }
} else {
    die("FPDF library not found. Please install FPDF in fpdf186/ folder. <a href='formulaire.php'>Retour</a>");
}
?>