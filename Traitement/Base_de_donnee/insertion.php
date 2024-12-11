<?php

require('../../Traitement/Base_de_donnee/Recuperation.php');

function insererCourrierDepart($numeroOrdre,$TypeDoc,$etat_inter_exter,
$etat_plis_ferme,$categorie,$dateEnreg,$datemiseCirculation,$reference,
$liencourrier,$formatCourrier,$objet,$matricule,$idReponse,$etatExpedition,$expediteur,$destinataire,$identite_dest,$pole_dest,
$nbre_fichiers_joins
){
    $test = false;
    try {
            // Initialisation de la connexion à la base de données
    $objet_connection = connectToDb('localhost', 'ecourrierdb2', 'Dba', 'EcourrierDba');
    $objet_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $idCourrier = incrementerClePrimaireNumerique('courrierdepart');

    $etatCourrier = 'envoyé';
    $sql = "INSERT into courrierdepart(`idCourrier`,`Type_document`,`Etat_interne_externe`,`etat_courrier`,`etat_plis_ferme`, `dateEnregistrement`,`date_mise_circulation`,`Reference`,
    `lien_courrier`,`format_fichier_courrier`,`Objet_du_courrier`,`Matricule_initiateur`,`idFichierReponse`,`etat_expedition`,`expediteur`,`destinataire`,`entite_dest`,`pole_destinataire`,`categorie`,
    `numero_ordre`,`nombre_fichiers_joins`) values (:idCourrier,:Type_document,:Etat_interne_externe,:etat_courrier,:etat_plis_ferme,
    :dateEnregistrement,:date_mise_circulation,:Reference,:lien_courrier,:format_fichier_courrier,:Objet_du_courrier,:Matricule_initiateur,:idFichierReponse,:etat_expedition,:expediteur,
    :destinataire,:entite_dest,:pole_destinataire,:categorie,:numero_ordre,:nombre_fichiers_joins);
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
    $resulats->bindValue(":etat_expedition",$etatExpedition);
    $resulats->bindValue(":expediteur",$expediteur);
    $resulats->bindValue(":destinataire",$destinataire);
    $resulats->bindValue(":entite_dest",$identite_dest);
    $resulats->bindValue(":pole_destinataire",$pole_dest);
    $resulats->bindValue(":categorie",$categorie);
    $resulats->bindValue(":numero_ordre",$numeroOrdre);
    $resulats->bindValue(":nombre_fichiers_joins",$nbre_fichiers_joins);
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
        'EtatExpedition' => $etatExpedition,
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
    
    $test = true;
    
    return $idCourrier;
    } catch (PDOException $e) {
        die('erreur de connexion'.$e->getMessage());
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
$liencourrier,$formatCourrier,$objet,$matricule,$idReponse,$expediteur,$destinataire,$identite_dest,$pole_dest,
$nbre_fichiers_joins){

    $test = false;
    try {
        // Initialisation de la connexion à la base de données
$objet_connection = connectToDb('localhost', 'ecourrierdb2', 'Dba', 'EcourrierDba');
$objet_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$idCourrier = incrementerClePrimaireNumerique('courrierarrive');

$etatCourrier = 'reçu';
$sql = "INSERT into courrierarrive  
values 
(?,?,?,?,?,
 ?,?,?,?,?,
 ?,?,?,?,?,
?,?,?,?,?);
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
$resulats->bindValue(10,$formatCourrier);
$resulats->bindValue(11,$objet);
$resulats->bindValue(12,$matricule);
$resulats->bindValue(13,$idReponse);
$resulats->bindValue(14,$numeroOrdre);
$resulats->bindValue(15,$categorie);
$resulats->bindValue(16,$identite_dest);
$resulats->bindValue(17,$pole_dest);
$resulats->bindValue(18,$nbre_fichiers_joins);
$resulats->bindValue(19,$expediteur);
$resulats->bindValue(20,$destinataire);





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
print_r($tableauAssociatif);
$resulats->execute();
echo'Excécution réussie';
$test = true;

return $idCourrier;
} catch (PDOException $e) {
    die('erreur de connexion'.$e->getMessage());
}




}

















?>