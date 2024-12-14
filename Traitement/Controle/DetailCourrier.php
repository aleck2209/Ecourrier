<?php
require('../../Traitement/Base_de_donnee/Recuperation.php');

$idCourrier= $_GET['$idCourrier'];
$typeCourrier= $_GET['$typeCourrier'];
var_dump( $_GET['$typeCourrier']);
$noms_copies = ""; // noms des destinataires de copies
$tableau_lien = []; //tableaux des liens de fichiers
$date_mise_circulation = '';// date de mise en circulation du courrier
$nom_fichier = "";// date mise en circulaton du currier
$tableau_des_noms_des_fichiers_joints = [];


// Récupération des détails pour un courrier départ et un courrier arrivé
if ($typeCourrier ==="courrier arrivé") {

    $sql1 = " SELECT Type_document,Etat_interne_externe as origine_courrier,etat_courrier,
              etat_plis_ferme as plis_ferme,dateEnregistrement,date_mise_circulation,Reference,lien_courrier,Objet_du_courrier,
              numero_ordre,categorie,nombre_fichiers_joins as nombre_de_fichiers_joins,expediteur,destinataire,Matricule,
              nom_utilisateur as nom_enregistreur,prenom_utilisateur as prenom_enregistreur, categorie, date_derniere_modification,signature_gouverneur,
              'courrier arrivé' AS type_courrier
              from courrierarrive inner join utilisateur
              on  courrierarrive.Matricule_initiateur = utilisateur.Matricule
              where idCourrier =:idCourrier";
    $T1 = getInfosForCourrier($sql1,$idCourrier);


     


    //Récupérer la date de mise en circulation 
    if (isset($T1[0]['date_mise_circulation'])) {
        $date_mise_circulation = new DateTime($T1[0]['date_mise_circulation']);
        $date_mise_circulation = $date_mise_circulation->format('Y-m-d\TH:i');
    }


    //récupérer le nom du fichier

    if (isset($T1[0]['lien_courrier'])) {
        $nom_fichier = recupererNomFichiers($T1[0]['lien_courrier']);
        echo $nom_fichier;
    }


    $sql2= "SELECT nom_destinataire
            FROM copie_courrier
            WHERE id_courrierArrive = :idCourrier;";

    $T2 = getInfosForCourrier($sql2,$idCourrier);

    $noms_destinataires = "";

    // Parcours du tableau pour extraire les noms
    foreach ($T2 as $destinataire) {
        
        // Ajout du nom à la chaîne, en vérifiant si la chaîne n'est pas vide pour éviter une virgule en début de chaîne
        $noms_copies .= ($noms_copies != "" ? ", " : "") . $destinataire['nom_destinataire'];
    }

    // Affichage du résultat
    // Affichera : DGE, DSI
    print_r($T1);
    print_r($T2);
    echo $noms_copies;

    $sql3= "SELECT lien_fichier_annexe
            FROM fichier_annexe
            WHERE idCourrierArv = :idCourrier";

    $T3 = getInfosForCourrier($sql3,$idCourrier);

  

    //Récupération du tableau contenant les liens des fichiers courriers 
    if (count($T3)>0) {
        foreach ($T3 as $lien) {
            // Ajout du lien dans le tableau des liens
            $tableau_lien []= $lien['lien_fichier_annexe'];
        }
    }
    
      // Récupération des noms des fichiers 
      if (count($tableau_lien)>0) {
        foreach ($tableau_lien as $lien_fichier_annexe) {
            $tableau_des_noms_des_fichiers_joints[]= recupererNomFichiers($lien_fichier_annexe);
        }
    }




    $sql4 = "SELECT lienFichierReponse, dateReponse
    FROM fichierreponse
    WHERE idCourrierDepart = :idCourrier;";

    $T4 = getInfosForCourrier($sql4,$idCourrier);

    print_r($T3);
    print_r($tableau_lien);
    print_r($T4);


}




// Récupération des détails pour un courrier départ et un courrier arrivé

elseif ($typeCourrier ==="courrier départ") {


    $sql1 = "SELECT Type_document,Etat_interne_externe as origine_courrier,etat_courrier,
             etat_plis_ferme as plis_ferme,dateEnregistrement,Reference,lien_courrier,Objet_du_courrier,Matricule,destinataire,
             nom_utilisateur as nom_enregistreur,prenom_utilisateur as prenom_enregistreur,nombre_fichiers_joins as nombre_de_fichiers_joins,
             'courrier départ' AS type_courrier,expediteur,numero_ordre, categorie, date_derniere_modification , signature_gouverneur
             from courrierdepart inner join  utilisateur
             on  courrierdepart.Matricule_initiateur = utilisateur.Matricule
             where idCourrier =:idCourrier";
    
    $T1 = getInfosForCourrier($sql1,$idCourrier);

    
    //Récupérer la date de mise en circulation 
    if (isset($T1[0]['date_mise_circulation'])) {
        $date_mise_circulation = new DateTime($T1[0]['date_mise_circulation']);
        $date_mise_circulation = $date_mise_circulation->format('Y-m-d\TH:i');
    }

     //récupérer le nom du fichier
    if (isset($T1[0]['lien_courrier'])) {
        $nom_fichier = recupererNomFichiers($T1[0]['lien_courrier']);
    }


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
   
    if (count($tableau_lien)>0) {
        foreach ($tableau_lien as $lien_fichier_annexe) {
            $tableau_des_noms_des_fichiers_joints[]= recupererNomFichiers($lien_fichier_annexe);
        }
    }

    print_r($T3);
    
    print_r($tableau_lien);
    
    $sql4 = "SELECT lienFichierReponse, dateReponse
    FROM fichierreponse
    WHERE idCourrierDepart = :idCourrier;";

    $T4 = getInfosForCourrier($sql4,$idCourrier);
    print_r($T4);


}



?>







