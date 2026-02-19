<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionnaire d'Emails</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Gestionnaire d'Emails</h1>

        <?php
        $actionUtilisateur = isset($_POST['traiter']) || isset($_POST['envoyer_message']) || isset($_POST['ajouter_email']);

        $Emails = [];
        $nom = [];
        $prenom = [];
        $addresseNonValide = [];
        $sansDoublons=[];
        $domainesEmails=[];

        $ExpressionReguliere = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

        if ($actionUtilisateur) {
            $sourcePath = "";
            if (isset($_FILES['emails_file']) && isset($_FILES['emails_file']['tmp_name']) && is_uploaded_file($_FILES['emails_file']['tmp_name'])) {
                $sourcePath = $_FILES['emails_file']['tmp_name'];
            } else {
                $sourcePath = "Emails.txt";
            }

            if (is_readable($sourcePath)) {
                $file3 = fopen($sourcePath, "r");
                if ($file3) {
                    while (!feof($file3)) {
                        $ligne = fgets($file3);
                        if ($ligne === false) { break; }
                        $part=explode(' ',$ligne);
                        $nom[]=trim($part[0]);
                        $prenom[]=trim($part[1]);
                        $Emails[]=trim($part[2]);
                    }
                    fclose($file3);
                }
            }

            // Valider les emails et enregistrer les non valides
            $EmailsValide = [];
            $addresseNonValides = fopen("addresseNonValides.txt", "w");
            foreach ($Emails as $i => $email) {
                if (preg_match($ExpressionReguliere, $email)) {
                    $EmailsValide[] = [
                        'nom' => $nom[$i],
                        'prenom' => $prenom[$i],
                        'email' => $email
                    ];
                } else {
                    fwrite($addresseNonValides, "{$nom[$i]} {$prenom[$i]} {$email}\n");
                }
            }
            fclose($addresseNonValides);
        
            // Supprimer les doublons
            foreach ($EmailsValide as $Email) {
                $sansDoublons[$Email['email']] = $Email;
            }
            $sansDoublons = array_values($sansDoublons);

            $file=fopen("EmailsT.txt","w");
            foreach($sansDoublons as $Email){
                fwrite($file,"{$Email['nom']} {$Email['prenom']} {$Email['email']}\n");
            }
            fclose($file);
        
            // Trier les emails 
            $lignes= [];
            $file=fopen("EmailsT.txt", "r");
            if ($file) {
                while (!feof($file)) {
                    $ligne = fgets($file);
                    if ($ligne === false) { break; }
                    $lignes[]=trim($ligne);
                }
                fclose($file);
            }
            sort($lignes);
            $file=fopen("EmailsT.txt","w");
            foreach($lignes as $ln){
                fwrite($file,$ln."\n");
            }
            fclose($file);

            //séparer les email par les domaines et les enregistrer dans des fichiers
            foreach($sansDoublons as $Email){
                $pos=strpos($Email['email'], '@');
                $domainesEmails[]=substr($Email['email'], $pos+1);
            }
            $domainesEmails=array_values(array_unique($domainesEmails));
            foreach($domainesEmails as $domaine){
                $fileD=fopen($domaine.".txt","w");
                foreach ($sansDoublons as $Email) {
                    $pos = strpos($Email['email'], "@");
                    if (substr($Email['email'], $pos + 1) == $domaine) {
                        fwrite($fileD, "{$Email['nom']} {$Email['prenom']} {$Email['email']}\n");
                    }
                }
                fclose($fileD);
            }
        }

        // Traitement des formulaires
        $message = "";
        $messageType = "";

        if (isset($_POST['ajouter_email'])) {
            $nom_Prenom = trim($_POST['nouvel_nom']);
            $nouvelEmail = trim($_POST['nouvel_email']);
            if ($nouvelEmail != "" && $nom_Prenom != "") {
                if (preg_match($ExpressionReguliere, $nouvelEmail)) {
                    $emailExiste = false;
                    foreach ($sansDoublons as $Email) {
                        if ($Email['email'] == $nouvelEmail) {
                            $emailExiste = true;
                            break;
                        }
                    }

                    if (!$emailExiste) {
                        $part1=explode(' ',$nom_Prenom);
                        $nouveauEmail = [
                            'nom' => trim($part1[0]),
                            'prenom' => trim($part1[1]),
                            'email' => $nouvelEmail
                        ];
                        $sansDoublons[] = $nouveauEmail;
                        $file=fopen("EmailsT.txt","w");
                        foreach($sansDoublons as $Email){
                            fwrite($file,"{$Email['nom']} {$Email['prenom']} {$Email['email']}\n");
                        }
                        fclose($file);
                
                        // Trier les emails 
                        $lignes= [];
                        $file=fopen("EmailsT.txt", "r");
                        if ($file) {
                            while (!feof($file)) {
                                $ligne = fgets($file);
                                if ($ligne === false) { break; }
                                $lignes[]=trim($ligne);
                            }
                            fclose($file);
                        }
                        sort($lignes);
                        $file=fopen("EmailsT.txt","w");
                        foreach($lignes as $ln){
                            fwrite($file,$ln."\n");
                        }
                        fclose($file);

                        // fichiers par domaine
                        $domainesEmails=[];
                        foreach($sansDoublons as $Email){
                            $pos=strpos($Email['email'], '@');
                            $domainesEmails[]=substr($Email['email'], $pos+1);
                        }
                        $domainesEmails=array_values(array_unique($domainesEmails));
                        foreach($domainesEmails as $domaine){
                            $fileD=fopen($domaine.".txt","w");
                            foreach ($sansDoublons as $Email) {
                                $pos = strpos($Email['email'], "@");
                                if (substr($Email['email'], $pos + 1) == $domaine) {
                                    fwrite($fileD, "{$Email['nom']} {$Email['prenom']} {$Email['email']}\n");
                                }
                            }
                            fclose($fileD);
                        }


                        $message = "Email ajouté avec succès !";
                        $messageType = "success";
                    } else {
                        $message = "Cette adresse email existe déjà dans la liste !";
                        $messageType = "warning";
                    }
                } else {
                    $message = "Format d'email invalide !";
                    $messageType = "danger";
                }
            } else {
                $message = "Veuillez saisir une adresse email !";
                $messageType = "danger";
            }
        }
        ?>

        <!-- Lancer le traitement -->
        <div class="row mb-4">
            <div class="col-md-12">
                <form method="POST" enctype="multipart/form-data" class="d-flex gap-2 align-items-center flex-wrap">
                    <input type="file" name="emails_file" accept=".txt" class="form-control" style="max-width: 320px;">
                    <button type="submit" name="traiter" class="btn btn-primary">
                        <i class="bi bi-cpu"></i> Traiter les emails
                    </button>
                    <a href="Emails.txt" class="btn btn-outline-secondary" download>
                        <i class="bi bi-file-earmark-text"></i> Télécharger Emails.txt
                    </a>
                </form>
            </div>
        </div>

        <!-- PARTIE 2 - Formulaire de téléchargement -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Télécharger les fichiers générés</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($actionUtilisateur): ?>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <a href="EmailsT.txt" class="btn btn-primary w-100" download>
                                        <i class="bi bi-download"></i> Télécharger EmailsT.txt
                                    </a>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <a href="addresseNonValides.txt" class="btn btn-outline-danger w-100" download>
                                        <i class="bi bi-exclamation-triangle"></i> Adresses non valides
                                    </a>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="dropdown w-100">
                                        <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-folder2-open"></i> Fichiers par domaine
                                        </button>
                                        <ul class="dropdown-menu w-100 p-2" style="max-height: 300px; overflow:auto">
                                            <?php if ($actionUtilisateur && !empty($domainesEmails)): ?>
                                                <?php foreach ($domainesEmails as $domaineData): ?>
                                                    <li>
                                                        <a class="dropdown-item" href="<?php echo $domaineData; ?>.txt" download>
                                                            <?php echo $domaineData; ?> 
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            <?php elseif ($actionUtilisateur): ?>
                                                <li class="text-muted px-2">Aucun domaine trouvé.</li>
                                            <?php else: ?>
                                                <li class="text-muted px-2">Veuillez d'abord traiter les emails.</li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire d'envoi de message -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Envoyer un message à tous les emails</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="from" class="form-label">From:</label>
                                <input type="text" name="from" id="from" placeholder="nom et prenom" class="form-control" require></input><br>
                                <label for="sub" class="form-label">Subject:</label>
                                <input type="text" name="subject" id="sub" placeholder="Subject" class="form-control" require></input><br>
                                <label for="message" class="form-label">Message à envoyer :</label>
                                <textarea class="form-control" id="message" name="message" rows="4" placeholder="Tapez votre message ici..."></textarea>
                            </div>
                            <button type="submit" name="envoyer_message" class="btn btn-success" <?php echo $actionUtilisateur ? '' : 'disabled'; ?>>
                                <i class="bi bi-send"></i> Envoyer à <?php echo count($sansDoublons); ?> emails
                            </button>
                            
                            <?php if (!$actionUtilisateur): ?>
                                <div class="form-text">Traitez d'abord les emails pour activer l'envoi.</div>
                            <?php endif; ?>

                            <?php
                                use PHPMailer\PHPMailer\PHPMailer;
                                use PHPMailer\PHPMailer\Exception;

                                require 'vendor/autoload.php';

                                if (isset($_POST['envoyer_message'])){
                                    $from=trim($_POST["from"]);
                                    $subject=trim($_POST["subject"]);
                                    $msg=trim($_POST["message"]);

                                    if (!empty($subject) && !empty($msg)){

                                        // Lire les emails
                                        $file=fopen("EmailsT.txt","r");
                                        $nomT=[];
                                        $prenomT=[];
                                        $EmailsT=[];
                                        while(($ligne=fgets($file))!==false){
                                            $part=explode(' ',$ligne);
                                            if (count($part) >= 3) {
                                                $nomT[] = $part[0];
                                                $prenomT[] = $part[1];
                                                $EmailsT[] = $part[2];
                                            }
                                        }
                                        fclose($file);

                                        $mail = new PHPMailer(true);
                                        try {
                                            // Config SMTP (exemple Gmail)
                                            $mail->isSMTP();
                                            $mail->Host = 'smtp.gmail.com';
                                            $mail->SMTPAuth = true;
                                            $mail->Username = 'exemple@gmail.com';
                                            $mail->Password = 'votre_mot_de_passe';
                                            $mail->SMTPSecure = 'tls';
                                            $mail->Port = 587;

                                            foreach ($EmailsT as $i=>$to_email) {
                                                $mail->setFrom('exemple@gmail.com');
                                                $mail->clearAllRecipients();
                                                $mail->addAddress($to_email,);

                                                $mail->Subject=$subject;
                                                $mail->Body="Bonjour, {$nomT[$i]} {$prenomT[$i]}\n\n{$msg}\n\n{$from} ";

                                                $mail->send();   
                                            }

                                            $message = "Message envoyé à tous les emails valides.";
                                            $messageType = "success";
                                            } catch (Exception $e) {
                                                $message = "Erreur lors de l'envoi : " . $mail->ErrorInfo;
                                                $messageType = "danger";
                                            }
                                    } else {
                                        $message = "Veuillez saisir un message!";
                                        $messageType = "danger";
                                    }
                                }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Affichage des messages -->
        <?php if ($message != ""): ?>
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Formulaire d'ajout d'email -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Ajouter une nouvelle adresse email</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="nom_prenom" class="form-label">Nom & Prenom:</label>
                                <input type="text" class="form-control" id="nom_prenom" name="nouvel_nom" placeholder="nom et prenom" require><br>
                                <label for="nouvel_email" class="form-label">Nouvelle adresse email :</label>
                                <input type="email" class="form-control" id="nouvel_email" name="nouvel_email" placeholder="exemple@domaine" require>
                            </div>
                            <button type="submit" name="ajouter_email" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Ajouter l'email
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>