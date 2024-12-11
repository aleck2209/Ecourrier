<?php
require('../../Traitement/Base_de_donnee/Recuperation.php');

$idCourrier= $_GET['$idCourrier'];
$typeCourrier= $_GET['$typeCourrier'];
var_dump( $_GET['$typeCourrier']);
$noms_copies = "";
$tableau_lien = [];


// Récupération des détails pour un courrier départ et un courrier arrivé
if ($typeCourrier ==="courrier arrivé") {

    $sql1 = " SELECT Type_document,Etat_interne_externe,etat_courrier,
              etat_plis_ferme as plis_ferme,dateEnregistrement,date_mise_circulation,Reference,lien_courrier,Objet_du_courrier,
              numero_ordre,categorie,nombre_fichiers_joins as nombre_de_fichiers_joins,expediteur,destinataire,Matricule,
              nom_utilisateur as nom_enregistreur,prenom_utilisateur as prenom_enregistreur,
              'courrier arrivé' AS type_courrier
              from courrierarrive inner join utilisateur
              on  courrierarrive.Matricule_initiateur = utilisateur.Matricule
              where idCourrier =:idCourrier";
    $T1 = getInfosForCourrier($sql1,$idCourrier);

    $sql2= "SELECT nom_destinataire
            FROM copie_courrier
            WHERE id_courrierArrive = :idCourrier;";

    $T2 = getInfosForCourrier($sql2,$idCourrier);

    $noms_destinataires = "";

    // Parcours du tableau pour extraire les noms
    foreach ($T2 as $destinataire) {
        echo$destinataire['nom_destinataire'];
        // Ajout du nom à la chaîne, en vérifiant si la chaîne n'est pas vide pour éviter une virgule en début de chaîne
        $noms_destinataires .= ($noms_destinataires != "" ? ", " : "") . $destinataire['nom_destinataire'];
    }

    // Affichage du résultat
    // Affichera : DGE, DSI
    print_r($T1);
    print_r($T2);
    echo $noms_destinataires;
    $sql3= "SELECT lien_fichier_annexe
            FROM fichier_annexe
            WHERE idCourrierArv = :idCourrier";

    $T3 = getInfosForCourrier($sql3,$idCourrier);

    //Récupération du tableau contenant les liens des fichiers courriers 
    if (count($T3)==0) {
        foreach ($T3 as $lien) {
            // Ajout du lien dans le tableau des liens
            $tableau_lien []= $lien['lien_fichier_annexe'];
        }
    }
    
    print_r($T3);
    print_r($tableau_lien);

}




// Récupération des détails pour un courrier départ et un courrier arrivé

elseif ($typeCourrier ==="courrier départ") {


    $sql1 = "SELECT Type_document,Etat_interne_externe as origine_courrier,etat_courrier,
             etat_plis_ferme as plis_ferme,dateEnregistrement,Reference,lien_courrier,Objet_du_courrier,Matricule,destinataire,
             nom_utilisateur as nom_enregistreur,prenom_utilisateur as prenom_enregistreur,nombre_fichiers_joins as nombre_de_fichiers_joins,
             'courrier départ' AS type_courrier
             from courrierdepart inner join  utilisateur
             on  courrierdepart.Matricule_initiateur = utilisateur.Matricule
             where idCourrier =:idCourrier";
    
    $T1 = getInfosForCourrier($sql1,$idCourrier);
    $sql2= "SELECT nom_destinataire
            FROM copie_courrier
            WHERE id_courrierDepart = :idCourrier";

    $T2 = getInfosForCourrier($sql2,$idCourrier);



    // Parcours du tableau pour extraire les noms
    foreach ($T2 as $copie) {
        
        // Ajout du nom à la chaîne, en vérifiant si la chaîne n'est pas vide pour éviter une virgule en début de chaîne
        $noms_copies .= ($noms_copies != "" ? ", " : "") . $copie['nom_destinataire'];
    }
    $sql3= "SELECT lien_fichier_annexe
            FROM fichier_annexe
            WHERE idCourrierDep = :idCourrier";

    $T3 = getInfosForCourrier($sql3,$idCourrier);


    

    print_r($T1);
    print_r($T2);

    foreach ($T3 as $lien) {
        
        // Ajout du nom à la chaîne, en vérifiant si la chaîne n'est pas vide pour éviter une virgule en début de chaîne
        $tableau_lien []= $lien['lien_fichier_annexe'];
    }
   
    print_r($T3);
    
    print_r($tableau_lien);
}



?>







