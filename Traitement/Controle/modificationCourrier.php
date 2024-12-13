<?php

// Inclusion des fichiers nécessaires
require('../../Traitement/Base_de_donnee/Update.php');
require('../../Traitement/Controle/gestionFichierCourrierDepart.php');
require('../../Traitement/Controle/gestionFichiesCourrierArrive.php');
require('../../Traitement/Verification/verifierFormat.php');
require('../../Traitement/Base_de_donnee/insertion.php');

// Récupération du matricule de l'utilisateur et de l'entité à laquelle il appartient
$requete = "SELECT e.id_entite, e.nom_entite
FROM entite_banque e
INNER JOIN utilisateur u ON e.id_entite = u.id_entite
WHERE u.Matricule = ?;";
$matricule = 'user03';  // Exemple de matricule, à remplacer par la variable réelle.

$nom_entite = recupererNomEntiteParIdUtilisateur($requete, $matricule);

// Vérifier que les données ont été envoyées via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $idCourrier = $_POST['idCourrier'];
    $typeCourrier = $_POST['typeCourrier'];
    $objet = $_POST['objet'];
    $destinataire = $_POST['destinataire'];
    $etat_courrier = $_POST['etat_courrier'];
    $numero_ordre = $_POST['numero_ordre'];
    $origine_courrier = $_POST['origine_courrier'];
    $typeDocument = $_POST['Type_document'];
    $categorie = $_POST['categorie'];
    $date_mise_circulation = $_POST['date_mise_circulation'];
    $signature_gouverneur = $_POST['signature_gouverneur'];

    // Initialiser la variable du lien du fichier
    $liencourrier = null;

    $verification = verifierCourrierDansModification($idCourrier);

    // Gestion du fichier : on vérifie si un fichier a été envoyé
    if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === UPLOAD_ERR_OK) {
        // Traitement du fichier uniquement si un fichier est fourni
        $fichier = gererFormat($_FILES['fichier']);

        // Prise en compte du fichier choisi pour remplacer le courrier
        $liendossier = creerListeDossiersCourrierDepart($origine_courrier, $destinataire);
        $liencourrier = deposerFichierDansDossier($liendossier, $fichier);

        // Gestion des fichiers joints (si applicable)
        $nom_balise_fichiers_join = "fichiers_joints"; // Cette variable récupère la valeur de l'attribut 'name' spécifié dans la balise HTML qui envoie les fichiers annexes
        $chemin_fichiers_joins = $liendossier . "/FichierAnnexes"; // Cette variable représente le lien du dossier où on doit stocker les fichiers annexes

        // Obtenir les liens des fichiers joints
        $liens_fichiers_joins = get_uploaded_files_paths($chemin_fichiers_joins, $nom_balise_fichiers_join);
        $liens_fichiers_joins_arrives = get_uploaded_files_pathsarrive($chemin_fichiers_joins, $nom_balise_fichiers_join);
    }

    // Vérifier les données (simple exemple de validation)
    if (empty($objet) || empty($categorie) || empty($destinataire) || empty($etat_courrier) || empty($typeDocument)) {
        die("Tous les champs sont obligatoires.");
    }

    // Vérification pour le courrier arrivé interne
    if ($typeCourrier === "courrier arrivé" && $origine_courrier ==="courrier interne") {
        
            echo "Vous n'êtes pas autorisé à modifier les courriers arrivés internes. <br>";
            echo "Envoyez une notification au près de l'entité éméttrice de ce courrier <br>";
            exit; // Bloquer la modification des courriers arrivés internes
       
    }

    // Traitement des courriers départ
    if ($typeCourrier === "courrier départ") {
        // Requête SQL pour mettre à jour les informations du courrier départ
        $sqlCourrierDepart = "UPDATE courrierdepart 
                              SET Objet_du_courrier = :objet, 
                                  destinataire = :destinataire, 
                                  etat_courrier = :etat_courrier, 
                                  Type_document = :Type_document,
                                  categorie = :categorie, 
                                  date_derniere_modification = NOW(), 
                                  signature_gouverneur = :signature_gouverneur,
                                  numero_ordre = :numero_ordre";

        // Si un fichier a été fourni, on ajoute la mise à jour du lien du fichier
        if ($liencourrier) {
            $sqlCourrierDepart .= ", lien_courrier = :lien_fichier";
        }

        // Si une date de mise en circulation a été fournie, on ajoute la mise à jour de la date de mise en circulation
        if ($date_mise_circulation) {
            $sqlCourrierDepart .= ", date_mise_circulation = :date_mise_circulation";
        }

        // Terminer la requête SQL
        $sqlCourrierDepart .= " WHERE idCourrier = :idCourrier";

        // Paramètres pour la mise à jour du courrier départ
        $paramsCourrierDepart = [
            ':objet' => $objet,
            ':destinataire' => $destinataire,
            ':etat_courrier' => $etat_courrier,
            ':numero_ordre' => $numero_ordre,
            ':idCourrier' => $idCourrier,
            ':Type_document' => $typeDocument,
            ':categorie' => $categorie,
            ':signature_gouverneur' => $signature_gouverneur
        ];

        // Si un fichier a été fourni, on ajoute son lien aux paramètres
        if ($liencourrier) {
            $paramsCourrierDepart[':lien_fichier'] = $liencourrier;
        }

        // Si une date a été fournie, on l'ajoute aux paramètres
        if ($date_mise_circulation) {
            $paramsCourrierDepart[':date_mise_circulation'] = $date_mise_circulation;
        }

        // Appel de la fonction pour mettre à jour le courrier départ
        updateCourrier($sqlCourrierDepart, $paramsCourrierDepart);

        // Si le courrier modifié est un courrier départ interne, mettre à jour aussi le courrier arrivé interne avec le même numero_ordre
        if ($origine_courrier === "courrier interne") {
            // Requête SQL pour mettre à jour le courrier arrivé interne avec les mêmes informations
            $sqlCourrierArrive = "UPDATE courrierarrive 
                                  SET Objet_du_courrier = :objet,
                                      destinataire = :destinataire,
                                      etat_courrier = :etat_courrier,
                                      Type_document = :Type_document,
                                      categorie = :categorie, 
                                      date_derniere_modification = NOW(),
                                      signature_gouverneur = :signature_gouverneur,
                                      numero_ordre = :numero_ordre";

            // Si un fichier a été fourni, on ajoute la mise à jour du lien du fichier pour le courrier arrivé
            if ($liencourrier) {
                $sqlCourrierArrive .= ", lien_courrier = :lien_fichier";
            }

            // Terminer la requête SQL
            $sqlCourrierArrive .= " WHERE numero_ordre = :numero_ordre";

            // Paramètres pour la mise à jour du courrier arrivé
            $paramsCourrierArrive = [
                ':objet' => $objet,
                ':destinataire' => $destinataire,
                ':etat_courrier' => $etat_courrier,
                ':numero_ordre' => $numero_ordre,
                ':Type_document' => $typeDocument,
                ':categorie' => $categorie,
                ':signature_gouverneur' => $signature_gouverneur
            ];

            // Si un fichier a été fourni, on ajoute son lien aux paramètres
            if ($liencourrier) {
                $paramsCourrierArrive[':lien_fichier'] = $liencourrier;
            }

            // Appel de la fonction pour mettre à jour le courrier arrivé
            updateCourrier($sqlCourrierArrive, $paramsCourrierArrive);
        }

        // Affichage du message de succès pour le courrier départ
        echo "Courrier départ mis à jour avec succès.";
    }

    // Traitement du courrier arrivé externe
    if ($typeCourrier === "courrier arrivé" && $origine_courrier ==="courrier externe") {
        
        // Vérifier que l'utilisateur est du bureau d'ordre avant de modifier un courrier arrivé
        if ($nom_entite === "BO" ) {
                if (!$verification) {
                    echo "Vous n'avez pas reçu la main pour modifier ce courrier";
                    exit;
                }
            // Requête SQL pour mettre à jour le courrier arrivé externe
            $sqlCourrierArriveExterne = "UPDATE courrierarrive 
                                         SET Objet_du_courrier = :objet,
                                             destinataire = :destinataire,
                                             etat_courrier = :etat_courrier,
                                             Type_document = :Type_document,
                                             categorie = :categorie, 
                                             date_derniere_modification = NOW(),
                                             signature_gouverneur = :signature_gouverneur,
                                             numero_ordre = :numero_ordre";

            // Si un fichier a été fourni, on ajoute la mise à jour du lien du fichier
            if ($liencourrier) {
                $sqlCourrierArriveExterne .= ", lien_courrier = :lien_fichier";
            }

            // Terminer la requête SQL
            $sqlCourrierArriveExterne .= " WHERE idCourrier = :idCourrier";

            // Paramètres pour la mise à jour du courrier arrivé externe
            $paramsCourrierArriveExterne = [
                ':objet' => $objet,
            ':destinataire' => $destinataire,
            ':etat_courrier' => $etat_courrier,
            ':numero_ordre' => $numero_ordre,
            ':idCourrier' => $idCourrier,
            ':Type_document' => $typeDocument,
            ':categorie' => $categorie,
            ':signature_gouverneur' => $signature_gouverneur
            ];

            // Si un fichier a été fourni, on ajoute son lien aux paramètres
            if ($liencourrier) {
                $paramsCourrierArriveExterne[':lien_fichier'] = $liencourrier;
            }

            // Appel de la fonction pour mettre à jour le courrier arrivé externe
            updateCourrier($sqlCourrierArriveExterne, $paramsCourrierArriveExterne);

            // Affichage du message de succès pour le courrier arrivé externe
            echo "Courrier arrivé externe mis à jour avec succès.";
        } else {
            echo "Vous n'avez pas l'autorisation de modifier un courrier arrivé externe.";
        }
    }
}
?>
