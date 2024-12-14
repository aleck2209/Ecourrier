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
    $idCourrier = $_POST['idCourrier']?? '';
    $typeCourrier = $_POST['typeCourrier']?? '';
    $objet = $_POST['objet'];
    $destinataire = $_POST['destinataire']?? '';
    $etat_courrier = $_POST['etat_courrier']?? '';
    $numero_ordre = $_POST['numero_ordre']?? '';
    $origine_courrier = $_POST['origine_courrier']?? '';
    $typeDocument = $_POST['Type_document']?? '';
    $categorie = $_POST['categorie']?? '';
    $date_mise_circulation = $_POST['date_mise_circulation']?? '';
    $signature_gouverneur = $_POST['signature_gouverneur']?? '';

    //Convertion du format de la date récupérée du formulaire au formulaire accepté dans la base de données:
      // Formater la date en ajoutant les secondes

        // Convertir la date au format base de données (YYYY-MM-DD HH:MM:SS)

        // if (is_null($date_mise_circulation)) {
        //     $date_mise_circulation = null;
        // }else {
        //     $date_mise_circulation = new DateTime($date_mise_circulation);
        //     $date_mise_circulation = $date_mise_circulation->format('YYYY-MM-DD HH:MM:SS');
        // }
      

    // Initialiser la variable du lien du fichier
    $liencourrier = null;

    $verification = verifierCourrierDansModification($idCourrier);


    $actionsModifiees = [];// Ce tableau contiendra les actions à mofier que nous utiliseront dans la fonction de modification
  
    // Récupérer le contenu de la table du courrier pour comparrer et voir qu'est-ce qui à été modifier 
if ($typeCourrier==="courrier départ") {
    $sql1 = " SELECT Objet_du_courrier,destinataire,etat_courrier,
                Type_document,categorie,date_derniere_modification,
                signature_gouverneur,numero_ordre,lien_courrier,date_mise_circulation
                from courrierdepart 
                where idCourrier =:idCourrier";
$Tableau_Infos_ancien_courrier = getInfosForCourrier($sql1,$idCourrier);



}elseif ($typeCourrier==="courrier arrivé") {
    $sql1 = " SELECT Objet_du_courrier,destinataire,etat_courrier,
                Type_document,categorie,date_derniere_modification,
                signature_gouverneur,numero_ordre,lien_courrier,date_mise_circulation
                from courrierarrive 
                where idCourrier =:idCourrier";
$Tableau_Infos_ancien_courrier = getInfosForCourrier($sql1,$idCourrier);
}







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
     if ($_FILES['fichier']['error'] ===1) {
        echo ' Veuillez vérifier la taille et le format du fichier ';
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

    
        // Avant de mettre à jour le courrier il nous faut vérifier quels champs ont été modifiés:


            $objet_base = trim($Tableau_Infos_ancien_courrier[0]["Objet_du_courrier"]);
            $objet_formulaire = trim($objet);
            $objet_base = preg_replace('/\s+/', ' ', $objet_base);  // Remplace les espaces multiples par un seul espace
            $objet_formulaire = preg_replace('/\s+/', ' ', $objet_formulaire);  // Idem pour l'objet du formulaire

// Comparaison après nettoyage
if ($objet_base !== $objet_formulaire) {
    $actionsModifiees[] = "Objet mis à jour";
}

if (preg_replace('/\s+/', ' ', trim($Tableau_Infos_ancien_courrier[0]["destinataire"])) !== preg_replace('/\s+/', ' ', trim($destinataire))) {
    $actionsModifiees[] = "Destinataire mis à jour";
}

if (preg_replace('/\s+/', ' ', trim($Tableau_Infos_ancien_courrier[0]["etat_courrier"])) !== preg_replace('/\s+/', ' ', trim($etat_courrier))) {
    $actionsModifiees[] = "État du courrier mis à jour";
}

if (preg_replace('/\s+/', ' ', trim($Tableau_Infos_ancien_courrier[0]["Type_document"])) !== preg_replace('/\s+/', ' ', trim($typeDocument))) {
    $actionsModifiees[] = "Type de document mis à jour";
}
if (preg_replace('/\s+/', ' ', trim($Tableau_Infos_ancien_courrier[0]["categorie"])) !== preg_replace('/\s+/', ' ', trim($categorie))) {
    $actionsModifiees[] = "Catégorie mise à jour";
}

if (preg_replace('/\s+/', ' ', trim($Tableau_Infos_ancien_courrier[0]["signature_gouverneur"])) !== preg_replace('/\s+/', ' ', trim($signature_gouverneur))) {
    if ($nom_entite ==="Sécrétariat") {
        $actionsModifiees[] = "Signature apportée à ce courrier";
    } else {
        echo " Vous n'êtes pas habilité à changer la case signature du gouverneur";
        exit;
    }
    
}
if (preg_replace('/\s+/', ' ', trim($Tableau_Infos_ancien_courrier[0]["numero_ordre"])) !== preg_replace('/\s+/', ' ', trim($numero_ordre))) {
    $actionsModifiees[] = "Numéro d'ordre mis à jour";
}
// Vérification si le fichier a changé (en supposant que lien_courrier soit null si aucun fichier n'a été ajouté)
// Vérification si le fichier a changé (en supposant que lien_courrier soit null si aucun fichier n'a été ajouté)
if ((preg_replace('/\s+/', ' ', trim($Tableau_Infos_ancien_courrier[0]["lien_courrier"])) !== preg_replace('/\s+/', ' ', trim($liencourrier))) && $liencourrier !== null) {
    $actionsModifiees[] = "Fichier du courrier mis à jour";
}
//Vérification si la date de mise en circulation a changé
// if (preg_replace('/\s+/', ' ', trim($Tableau_Infos_ancien_courrier[0]["date_mise_circulation"])) !== preg_replace('/\s+/', ' ', trim($date_mise_circulation)) && $date_mise_circulation !=null) {
   
//     echo $Tableau_Infos_ancien_courrier[0]["date_mise_circulation"].'<br>';
//     echo $date_mise_circulation;

//     $actionsModifiees[] = "Date de mise en circulation mise à jour";
// }


// Vérifier s'il y a des actions modifiées à enregistrer
if (count($actionsModifiees) > 0) {
    // Afficher le tableau des actions modifiées
    print_r($actionsModifiees);
    // Appel de la fonction pour mettre à jour le courrier départ
    updateCourrier($sqlCourrierDepart, $paramsCourrierDepart);

    foreach ($actionsModifiees as $action) {
        insertHistorique($action,$idCourrier,$nom_entite,$typeCourrier); 
    }
    
 

}else {
    die( '<script>
alert("Aucune modification apportée à ce courrier.");
setTimeout(function(){
    window.location.href = "../../public/page/tableau-bord.php";
}, 500); 
</script>'
);
}
        

        // // Si le courrier modifié est un courrier départ interne, mettre à jour aussi le courrier arrivé interne avec le même numero_ordre
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

            //Vérification des données 
            foreach ($actionsModifiees as $action) {
                insertHistorique($action,$idCourrier,$nom_entite,$typeCourrier); 
            }
            // Il faut d'abord trouver le l'id du courrier arrivé correspondant à ce numéro d'ordre afin d'utiliser son id pour l'historique
            $ligneCourrierArrive = recupererLigneSpecifique("courrierArrive","numero_ordre",$numero_ordre);
            if (!$ligneCourrierArrive) {
                //Si le tableau est vide, on ne fait rien

            } else{
                // Si contient des données on récupère l'id et insère l'actio
                $idCourrierArv = $ligneCourrierArrive[0]->idCourrier; 
                echo "L'idCourrier est : " . $idCourrier;
                insertHistorique("mis à jour du courrier",$idCourrierArv,$nom_entite,"courrier arrivé");

            }
            

        }

        die( '<script>
        alert("courrier départ mis à jour.");
        setTimeout(function(){
            window.location.href = "../../public/page/tableau-bord.php";
        }, 500); 
        </script>'
        );
    }

    // Traitement du courrier arrivé externe
    if ($typeCourrier === "courrier arrivé" && $origine_courrier ==="courrier externe") {
        
        // Vérifier que l'utilisateur est du bureau d'ordre avant de modifier un courrier arrivé
        if ($nom_entite === "BO" ) {
                if (!$verification) {
                    die( '<script>
                    alert("Vous n\'avez pas reçu la main pour mettre à jour ce courrier.");
                    setTimeout(function(){
                        window.location.href = "../../public/page/tableau-bord.php";
                    }, 500); 
                    </script>'
                    );
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


            // Avant de mettre à jour le courrier il nous faut vérifier quels champs ont été modifiés:


                $objet_base = trim($Tableau_Infos_ancien_courrier[0]["Objet_du_courrier"]);
                $objet_formulaire = trim($objet);
                $objet_base = preg_replace('/\s+/', ' ', $objet_base);  // Remplace les espaces multiples par un seul espace
                $objet_formulaire = preg_replace('/\s+/', ' ', $objet_formulaire);  // Idem pour l'objet du formulaire
    
    // Comparaison après nettoyage
    if ($objet_base !== $objet_formulaire) {
        $actionsModifiees[] = "Objet mis à jour";
    }
    
    if (preg_replace('/\s+/', ' ', trim($Tableau_Infos_ancien_courrier[0]["destinataire"])) !== preg_replace('/\s+/', ' ', trim($destinataire))) {
        $actionsModifiees[] = "Destinataire mis à jour";
    }
    
    if (preg_replace('/\s+/', ' ', trim($Tableau_Infos_ancien_courrier[0]["etat_courrier"])) !== preg_replace('/\s+/', ' ', trim($etat_courrier))) {
        $actionsModifiees[] = "État du courrier mis à jour";
    }
    
    if (preg_replace('/\s+/', ' ', trim($Tableau_Infos_ancien_courrier[0]["Type_document"])) !== preg_replace('/\s+/', ' ', trim($typeDocument))) {
        $actionsModifiees[] = "Type de document mis à jour";
    }
    if (preg_replace('/\s+/', ' ', trim($Tableau_Infos_ancien_courrier[0]["categorie"])) !== preg_replace('/\s+/', ' ', trim($categorie))) {
        $actionsModifiees[] = "Catégorie mise à jour";
    }
    
    if (preg_replace('/\s+/', ' ', trim($Tableau_Infos_ancien_courrier[0]["signature_gouverneur"])) !== preg_replace('/\s+/', ' ', trim($signature_gouverneur))) {
        if ($nom_entite ==="Sécrétariat") {
            $actionsModifiees[] = "Signature apportée à ce courrier";
        } else {
            echo " Vous n'êtes pas habilité à changer la case signature du gouverneur";
            exit;
        }
        
    }
    if (preg_replace('/\s+/', ' ', trim($Tableau_Infos_ancien_courrier[0]["numero_ordre"])) !== preg_replace('/\s+/', ' ', trim($numero_ordre))) {
        $actionsModifiees[] = "Numéro d'ordre mis à jour";
    }
    // Vérification si le fichier a changé (en supposant que lien_courrier soit null si aucun fichier n'a été ajouté)
    // Vérification si le fichier a changé (en supposant que lien_courrier soit null si aucun fichier n'a été ajouté)
    if ((preg_replace('/\s+/', ' ', trim($Tableau_Infos_ancien_courrier[0]["lien_courrier"])) !== preg_replace('/\s+/', ' ', trim($liencourrier))) && $liencourrier !== null) {
        $actionsModifiees[] = "Fichier du courrier mis à jour";
    }
    //Vérification si la date de mise en circulation a changé
    // if (preg_replace('/\s+/', ' ', trim($Tableau_Infos_ancien_courrier[0]["date_mise_circulation"])) !== preg_replace('/\s+/', ' ', trim($date_mise_circulation)) && $date_mise_circulation !=null) {
       
    //     echo $Tableau_Infos_ancien_courrier[0]["date_mise_circulation"].'<br>';
    //     echo $date_mise_circulation;
    
    //     $actionsModifiees[] = "Date de mise en circulation mise à jour";
    // }
    
    
    // Vérifier s'il y a des actions modifiées à enregistrer
    if (count($actionsModifiees) > 0) {
        // Afficher le tableau des actions modifiées
        print_r($actionsModifiees);
        // Appel de la fonction pour mettre à jour le courrier arrivé externe
        updateCourrier($sqlCourrierArriveExterne, $paramsCourrierArriveExterne);
    
        foreach ($actionsModifiees as $action) {
            insertHistorique($action,$idCourrier,$nom_entite,$typeCourrier); 
        }
        
    }else {
        die( '<script>
alert("Aucune modification apportée à ce courrier.");
setTimeout(function(){
    window.location.href = "../../public/page/tableau-bord.php";
}, 500); 
</script>'
);
    }

    die( '<script>
    alert("courrier arrivé extere mis à jour.");
    setTimeout(function(){
        window.location.href = "../../public/page/tableau-bord.php";
    }, 500); 
    </script>'
    );        } else {
        die( '<script>
        alert("Vous n\'êtes pas habilité à mettre à jour un courrier arrivé externe ");
        setTimeout(function(){
            window.location.href = "../../public/page/tableau-bord.php";
        }, 500); 
        </script>'
        ); }
    }
}







?>
