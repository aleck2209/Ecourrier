<?php
function creerDossierCourrierArrive($emplacement, $nomDossier) {
    // Chemin complet du dossier à créer
    $cheminComplet = $emplacement . '/' . $nomDossier;

    // Vérifie si le dossier existe déjà
    if (!is_dir($cheminComplet)) {
        // Crée le dossier avec les permissions 0755 (rwxr-xr-x)
        if (mkdir($cheminComplet, 0755)) {
            echo "Le dossier $nomDossier a été créé avec succès dans $emplacement.";
        } else {
            echo "Une erreur s'est produite lors de la création du dossier.";
        }
    } else {
        echo "Le dossier $nomDossier existe déjà.";
    }
}


function creerListeDossiersCourrierArrive($etat_inter_externe, $destinataire) {
    // Définir le chemin de base
    $basePath = '../../..DossiersCouriers/courrierArrive';  // À ajuster selon ton emplacement de base

    // Créer le dossier "courrierdepart" s'il n'existe pas
    if (!file_exists($basePath)) {
        mkdir($basePath, 0644, true);
    }

    
    // Définir le chemin du dossier "interne" ou "externe"
    $dossierInterExterne = $basePath . '/' . ($etat_inter_externe === 'courrier interne' ? 'interne' : 'externe');

    // Créer le dossier interne ou externe s'il n'existe pas
    if (!file_exists($dossierInterExterne)) {
        mkdir($dossierInterExterne, 0644, true);
    }

    // Créer un dossier avec le nom du destinataire
    $dossierDestinataire = $dossierInterExterne . '/' . $destinataire;

    // Créer le dossier du destinataire s'il n'existe pas
    if (!file_exists($dossierDestinataire)) {
        mkdir($dossierDestinataire, 0644, true);
    }

    
    return $dossierDestinataire;  // Renvoie l'emplacement relatif du dernier dossier
}


function deposerFichierDansDossierCourrierArrive($dossier, $fichier) {
    try {
        // Vérifier si le fichier a bien été téléchargé
        if ($fichier['error'] === UPLOAD_ERR_OK) {
            // Récupérer le nom du fichier et ajouter un préfixe si nécessaire (pour éviter les conflits de noms)
            $nomFichier = basename($fichier['name']);
            
            // Créer le chemin absolu pour le fichier final dans le dossier
            $cheminFinal = $dossier . '/' . $nomFichier;

            // Vérifier si le fichier existe déjà
            if (file_exists($cheminFinal)) {
                // Si le fichier existe, ajouter un suffixe pour rendre le nom unique
                $infoFichier = pathinfo($nomFichier);
                $nomFichier = $infoFichier['filename'] . '_' . time() . '.' . $infoFichier['extension'];
                $cheminFinal = $dossier . '/' . $nomFichier;
            }

            // Déplacer le fichier dans le dossier spécifié
            if (move_uploaded_file($fichier['tmp_name'], $cheminFinal)) {
                // Retourner le chemin absolu du fichier dans le dossier
                return $cheminFinal;  // Cela retourne le chemin absolu du fichier
            } else {
                // Si une erreur se produit lors du déplacement du fichier
                throw new Exception("Erreur lors du déplacement du fichier.");
            }
        } else {
            // Si le fichier n'a pas été correctement téléchargé
            throw new Exception("Erreur de téléchargement du fichier. Code erreur: " . $fichier['error']);
        }
    } catch (Exception $e) {
        // Gérer l'exception et renvoyer un message d'erreur
        return "Une erreur est survenue : " . $e->getMessage();
    }
}


//Cette fonction permet de récupérer le chemin de plusieurs fichiers et de déplacer ces fichiers dans un dossier

function get_uploaded_files_pathsarrive($chemin,$param){
    $liens = [];
    
     //Création du repertoire s'il n'existe pas encore
     if (!is_dir($chemin)) {
       mkdir($chemin, 0755, true);
    }
    
    
    for ($i=0; $i <count($_FILES[$param]['name']) ; $i++) { 
       if ($_FILES[$param]['error'][$i]===UPLOAD_ERR_OK) {
          $nomFichier = basename($_FILES[$param]['name'][$i]);
          $cheminFinal = $chemin.'/'.$nomFichier;
          $liens [] = $cheminFinal;
    
          if (move_uploaded_file($_FILES[$param]['tmp_name'][$i], $cheminFinal)) {
             // Retourner le chemin absolu du fichier dans le dossier
             // Cela retourne le chemin absolu du fichier
         }
    
       }
    }return $liens;
 
 
 }
?>