<?php

require('../../Traitement/Base_de_donnee/Recuperation.php');

function insererCourrierDepart($numeroOrdre,$TypeDoc,$etat_inter_exter,
$etat_plis_ferme,$categorie,$dateEnreg,$datemiseCirculation,$reference,
$liencourrier,$objet,$matricule,$idReponse,$etatExpedition,$expediteur,$destinataire,$identite_dest,$pole_dest,
$nbre_fichiers_joins,$etatCourrier
){
    $test = false;
    try {
            // Initialisation de la connexion à la base de données
    $objet_connection = connectToDb('localhost', 'ecourrierdb2', 'Dba', 'EcourrierDba');
    $objet_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $idCourrier = incrementerClePrimaireNumerique('courrierdepart');

    
    $date_derniere_modification=null;
    $signature_gouverneur = "non";

    $sql = "INSERT into courrierdepart(`idCourrier`,`Type_document`,`Etat_interne_externe`,`etat_courrier`,`etat_plis_ferme`, `dateEnregistrement`,`date_mise_circulation`,`Reference`,
    `lien_courrier`,`Objet_du_courrier`,`Matricule_initiateur`,`idFichierReponse`,`etat_expedition`,`expediteur`,`destinataire`,`entite_dest`,`pole_destinataire`,`categorie`,
    `numero_ordre`,`nombre_fichiers_joins`,`date_derniere_modification`,`signature_gouverneur`) values (:idCourrier,:Type_document,:Etat_interne_externe,:etat_courrier,:etat_plis_ferme,
    :dateEnregistrement,:date_mise_circulation,:Reference,:lien_courrier,:Objet_du_courrier,:Matricule_initiateur,:idFichierReponse,:etat_expedition,:expediteur,
    :destinataire,:entite_dest,:pole_destinataire,:categorie,:numero_ordre,:nombre_fichiers_joins,:date_derniere_modification, :signature_gouverneur);
      ";
          
    $resulats = $objet_connection->prepare($sql);
    
    $resulats->bindValue(":idCourrier",$idCourrier);
    $resulats->bindValue(":Type_document",$TypeDoc);
    $resulats->bindValue(":Etat_interne_externe",$etat_inter_exter);
    $resulats->bindValue(":etat_courrier",$etatCourrier);
    $resulats->bindValue(":etat_plis_ferme",$etat_plis_ferme);
    $resulats->bindValue(":dateEnregistrement",$dateEnreg);
    $resulats->bindValue(":date_mise_circulation",$datemiseCirculation);
    $resulats->bindValue(":Reference",$reference);
    $resulats->bindValue(":lien_courrier",$liencourrier);
    $resulats->bindValue(":Objet_du_courrier",$objet);
    $resulats->bindValue(":Matricule_initiateur",$matricule);
    $resulats->bindValue(":idFichierReponse",$idReponse);
    $resulats->bindValue(":etat_expedition",$etatExpedition);
    $resulats->bindValue(":expediteur",$expediteur);
    $resulats->bindValue(":destinataire",$destinataire);
    $resulats->bindValue(":entite_dest",$identite_dest);
    $resulats->bindValue(":pole_destinataire",$pole_dest);
    $resulats->bindValue(":categorie",$categorie);
    $resulats->bindValue(":numero_ordre",$numeroOrdre);
    $resulats->bindValue(":nombre_fichiers_joins",$nbre_fichiers_joins);
    $resulats->bindValue(":date_derniere_modification",$date_derniere_modification);
    $resulats->bindValue(":signature_gouverneur",$signature_gouverneur);
    
    // Tableau associatif avec les noms des colonnes comme clés
    $tableauAssociatif = [
        'idCourrier' => $idCourrier,
        'numeroOrdre' => $numeroOrdre,
        'TypeDoc' => $TypeDoc,
        'Etat_inter_exter' => $etat_inter_exter,
        'EtatCourrier' => $etatCourrier,
        'Etat_plis_ferme' => $etat_plis_ferme,
        'DateEnreg' => $dateEnreg,
        'DateMiseCirculation' => $datemiseCirculation,
        'Reference' => $reference,
        'LienCourrier' => $liencourrier,
        'Objet' => $objet,
        'Matricule' => $matricule,
        'IdReponse' => $idReponse,
        'EtatExpedition' => $etatExpedition,
        'Expediteur' => $expediteur,
        'Destinataire' => $destinataire,
        'Identite_dest' => $identite_dest,
        'Pole_dest' => $pole_dest,
        'Categorie' => $categorie,
        'nombre fichiers joins'=> $nbre_fichiers_joins,
        'date_derniere_modification'=> $date_derniere_modification
    ];

    // Afficher le tableau associatif
    // print_r($tableauAssociatif);
    $resulats->execute();
    $test = true;
    return $idCourrier;
    } catch (PDOException $e) {
        // Afficher la requête SQL et les erreurs détaillées
    echo "Erreur SQL: " . $e->getMessage();
    //print_r($tableauAssociatif); // Afficher les valeurs bindées
    die('Erreur de connexion');
    }    
}




//Cette fonction ajoute un enregistrement dans la table copie courrier
function insererUneCopieCourrier($nomDest,$lienCourrier,$idCourrerdep,$idCourrierArv,$identitedest,$ipoledest){
    $test = false;
    try {
        $objet_connection = connectToDb('localhost', 'ecourrierdb2', 'Dba', 'EcourrierDba');
    $objet_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $idCopie = incrementerClePrimaireNumerique('copie_courrier');
    $sql = "insert into copie_courrier values (?,?,?,?,?,?,?);
          ";
    $resulats = $objet_connection->prepare($sql);
    $resulats->bindValue(1,$idCopie);
    $resulats->bindValue(2,$nomDest);
    $resulats->bindValue(3,$idCourrerdep);
    $resulats->bindValue(4,$idCourrierArv);
    $resulats->bindValue(5,$identitedest);
    $resulats->bindValue(6,$ipoledest);
    $resulats->bindValue(7,$lienCourrier);
    $resulats->execute();
    $test = true;
    echo "enregistrement copie réussi...";
    echo $idCourrerdep;
    return $test;
    } catch (PDOException $e) {
        die('erreur de connexion'.$e->getMessage());

    }

}




function insererFichierJoin($lienFichier,$idCourrierdep,$idCourrierArv){
    try {
        $objet_connection = connectToDb('localhost', 'ecourrierdb2', 'Dba', 'EcourrierDba');
    $objet_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $idFichierjoin = incrementerClePrimaireNumerique('fichier_annexe');
    
    $sql = "insert into fichier_annexe(`idFichier`,`lien_fichier_annexe`,`idCourrierDep`,`idCourrierArv`) values (?,?,?,?);
          ";
          $resulats = $objet_connection->prepare($sql);
          $resulats->bindValue(1,$idFichierjoin);
          $resulats->bindValue(2,$lienFichier);
          $resulats->bindValue(3,$idCourrierdep);
          $resulats->bindValue(4,$idCourrierArv);
          $resulats->execute();
          echo "enregistrement réussi...";
    } catch (PDOException $e) {
        die('erreur de connexion'.$e->getMessage());
    }


}


function insererCourrierArrive($numeroOrdre,$TypeDoc,$etat_inter_exter,
$etat_plis_ferme,$categorie,$dateEnreg,$datemiseCirculation,$reference,
$liencourrier,$formatCourrier,$objet,$matricule,$idReponse,$expediteur,$destinataire,$identite_dest,$pole_dest,
$nbre_fichiers_joins){


    $test = false;
    try {
            // Initialisation de la connexion à la base de données
    $objet_connection = connectToDb('localhost', 'ecourrierdb2', 'Dba', 'EcourrierDba');
    $objet_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $idCourrier = incrementerClePrimaireNumerique('courrierarrive');

    $etatCourrier = 'reçu';
    $sql = "INSERT into courrierarrive (`idCourrier`,`Type_document`,`Etat_interne_externe`,`etat_courrier`,`etat_plis_ferme`, `dateEnregistrement`,`date_mise_circulation`,`Reference`,
    `lien_courrier`,`format_fichier_courrier`,`Objet_du_courrier`,`Matricule_initiateur`,`idFichierReponse`,`numero_ordre`, `categorie`,`entite_dest`,
    `pole_dest`, `nombre_fichiers_joins`,`expediteur`,`destinataire`) 
    values 
    (:idCourrier,:Type_document,:Etat_interne_externe,:etat_courrier,:etat_plis_ferme,
    :dateEnregistrement,:date_mise_circulation,:Reference,:lien_courrier,:format_fichier_courrier,:Objet_du_courrier,:Matricule_initiateur,:idFichierReponse,:numero_ordre,:categorie,
    :entite_dest,:pole_dest,:nombre_fichiers_joins,:expediteur,:destinataire);
      ";
          
    $resulats = $objet_connection->prepare($sql);
    
    $resulats->bindValue(":idCourrier",$idCourrier);
    $resulats->bindValue(":Type_document",$TypeDoc);
    $resulats->bindValue(":Etat_interne_externe",$etat_inter_exter);
    $resulats->bindValue(":etat_courrier",$etatCourrier);
    $resulats->bindValue(":etat_plis_ferme",$etat_plis_ferme);
    $resulats->bindValue(":dateEnregistrement",$dateEnreg);
    $resulats->bindValue(":date_mise_circulation",$datemiseCirculation);
    $resulats->bindValue(":Reference",$reference);
    $resulats->bindValue(":lien_courrier",$liencourrier);
    $resulats->bindValue(":format_fichier_courrier",$formatCourrier);
    $resulats->bindValue(":Objet_du_courrier",$objet);
    $resulats->bindValue(":Matricule_initiateur",$matricule);
    $resulats->bindValue(":idFichierReponse",$idReponse);
    $resulats->bindValue(":numero_ordre",$numeroOrdre);
    $resulats->bindValue(":categorie",$categorie);
    $resulats->bindValue(":entite_dest",$identite_dest);
    $resulats->bindValue(":pole_destinataire",$pole_dest);
    $resulats->bindValue(":nombre_fichiers_joins",$nbre_fichiers_joins);
    $resulats->bindValue(":expediteur",$expediteur);
    $resulats->bindValue(":destinataire",$destinataire);
    
    
    
    
   
    // Tableau associatif avec les noms des colonnes comme clés
    $tableauAssociatif = [
        'idCourrier' => $idCourrier,
        'numeroOrdre' => $numeroOrdre,
        'TypeDoc' => $TypeDoc,
        'Etat_inter_exter' => $etat_inter_exter,
        'EtatCourrier' => $etatCourrier,
        'Etat_plis_ferme' => $etat_plis_ferme,
        'DateEnreg' => $dateEnreg,
        'DateMiseCirculation' => $datemiseCirculation,
        'Reference' => $reference,
        'LienCourrier' => $liencourrier,
        'FormatCourrier' => $formatCourrier,
        'Objet' => $objet,
        'Matricule' => $matricule,
        'IdReponse' => $idReponse,
        'Expediteur' => $expediteur,
        'Destinataire' => $destinataire,
        'Identite_dest' => $identite_dest,
        'Pole_dest' => $pole_dest,
        'Categorie' => $categorie,
        'nombre fichiers joins'=> $nbre_fichiers_joins
    ];

    // Afficher le tableau associatif
    // print_r($tableauAssociatif);
    $resulats->execute();
    echo'Excécution réussie';
    $test = true;
    
    return $idCourrier;
    } catch (PDOException $e) {
        die('erreur de connexion'.$e->getMessage());
    }


}

function insererCourrierArriveV2($numeroOrdre,$TypeDoc,$etat_inter_exter,
$etat_plis_ferme,$categorie,$dateEnreg,$datemiseCirculation,$reference,
$liencourrier,$objet,$matricule,$idReponse,$expediteur,$destinataire,$identite_dest,$pole_dest,
$nbre_fichiers_joins,$etatCourrier){

    $test = false;
    try {
        // Initialisation de la connexion à la base de données
$objet_connection = connectToDb('localhost', 'ecourrierdb2', 'Dba', 'EcourrierDba');
$objet_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$idCourrier = incrementerClePrimaireNumerique('courrierarrive');


$date_derniere_modification=null;
$signature_gouverneur = "non";

$sql = "INSERT into courrierarrive  
values 
(?,?,?,?,?,
 ?,?,?,?,?,
 ?,?,?,?,?,
?,?,?,?,?,
?);
  ";
$resulats = $objet_connection->prepare($sql);

$resulats->bindValue(1,$idCourrier);
$resulats->bindValue(2,$TypeDoc);
$resulats->bindValue(3,$etat_inter_exter);
$resulats->bindValue(4,$etatCourrier);
$resulats->bindValue(5,$etat_plis_ferme);
$resulats->bindValue(6,$dateEnreg);
$resulats->bindValue(7,$datemiseCirculation);
$resulats->bindValue(8,$reference);
$resulats->bindValue(9,$liencourrier);
$resulats->bindValue(10,$objet);
$resulats->bindValue(11,$matricule);
$resulats->bindValue(12,$idReponse);
$resulats->bindValue(13,$numeroOrdre);
$resulats->bindValue(14,$categorie);
$resulats->bindValue(15,$identite_dest);
$resulats->bindValue(16,$pole_dest);
$resulats->bindValue(17,$nbre_fichiers_joins);
$resulats->bindValue(18,$expediteur);
$resulats->bindValue(19,$destinataire);
$resulats->bindValue(20,$date_derniere_modification);
$resulats->bindValue(21,$signature_gouverneur);




// Tableau associatif avec les noms des colonnes comme clés
$tableauAssociatif = [
    'idCourrier' => $idCourrier,
    'numeroOrdre' => $numeroOrdre,
    'TypeDoc' => $TypeDoc,
    'Etat_inter_exter' => $etat_inter_exter,
    'EtatCourrier' => $etatCourrier,
    'Etat_plis_ferme' => $etat_plis_ferme,
    'DateEnreg' => $dateEnreg,
    'DateMiseCirculation' => $datemiseCirculation,
    'Reference' => $reference,
    'LienCourrier' => $liencourrier,
    'Objet' => $objet,
    'Matricule' => $matricule,
    'IdReponse' => $idReponse,
    'Expediteur' => $expediteur,
    'Destinataire' => $destinataire,
    'Identite_dest' => $identite_dest,
    'Pole_dest' => $pole_dest,
    'Categorie' => $categorie,
    'nombre fichiers joins'=> $nbre_fichiers_joins,
    'date_derniere_modification'=> $date_derniere_modification,
    'signature_gouverneur' =>  $signature_gouverneur
];

// Afficher le tableau associatif
print_r($tableauAssociatif);
$resulats->execute();
echo'Excécution réussie';
$test = true;

return $idCourrier;
} catch (PDOException $e) {
    die('erreur de connexion'.$e->getMessage());
}


}




// Fonction pour insérer un idCourrier dans main_modification_courrier avec la date actuelle
function donnerAutorisationPourModificationCourrierArrive($idCourrier) {
    // La date et l'heure actuelles

    $objet_connection = connectToDb('localhost', 'ecourrierdb2', 'Dba', 'EcourrierDba');
    $date_autorisation = date('Y-m-d H:i:s');

    // Préparation de la requête SQL
    $sql = "INSERT INTO main_modification_courrier (idCourrier, date_autorisation) 
            VALUES (:idCourrier, :date_autorisation)";
    
    // Préparer la requête
    $stmt = $objet_connection->prepare($sql);
    
    // Lier les paramètres
    $stmt->bindParam(':idCourrier', $idCourrier, PDO::PARAM_INT);
    $stmt->bindParam(':date_autorisation', $date_autorisation, PDO::PARAM_STR);
    
    // Exécution de la requête
    if ($stmt->execute()) {
        return true;  // L'insertion a réussi
    } else {
        return false; // L'insertion a échoué
    }
}





function insertHistorique($action, $idCourrier, $entiteQuiEnregistre,$typeCourrier,$matricule) {
    try {


        $objet_connection = connectToDb('localhost', 'ecourrierdb2', 'Dba', 'EcourrierDba');
        // Vérification de l'entité et préparation de la requête SQL
        if ($typeCourrier === 'courrier départ') {
            $sql = "INSERT INTO historique (action_effectuee, date_operation, idCourrierdepart, idCourrierArrive,entite_resoinsable,matricule_utilisateur) 
                    VALUES (:action, NOW(), :idCourrier, NULL,:entite_resoinsable, :matricule_utilisateur)";
        } elseif ($typeCourrier === 'courrier arrivé') {
            $sql = "INSERT INTO historique (action_effectuee, date_operation, idCourrierdepart, idCourrierArrive,entite_resoinsable,matricule_utilisateur) 
                    VALUES (:action, NOW(), NULL, :idCourrier,:entite_resoinsable, :matricule_utilisateur)";
        } else {
            throw new Exception("Entité invalide, utilisez 'depart' ou 'arrive'.");
        }

        // Préparer la requête
        $stmt = $objet_connection->prepare($sql);

        // Lier les paramètres
        $stmt->bindParam(':action', $action, PDO::PARAM_STR);
        $stmt->bindParam(':idCourrier', $idCourrier, PDO::PARAM_INT);
        $stmt->bindParam(':entite_resoinsable', $entiteQuiEnregistre, PDO::PARAM_STR);
        $stmt->bindParam(':matricule_utilisateur', $matricule, PDO::PARAM_STR);
        // Exécuter la requête
        $stmt->execute();

        // Affichage d'un message de succès
        echo "L'action a été enregistrée avec succès.";
    } catch (PDOException $e) {
        // Gestion des erreurs de la base de données
        echo "Erreur de connexion ou d'insertion: " . $e->getMessage();
    } catch (Exception $e) {
        // Gestion des autres erreurs (par exemple, entité invalide)
        echo "Erreur: " . $e->getMessage();
    }
}










?>