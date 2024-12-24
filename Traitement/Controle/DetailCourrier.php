<?php
require('../../Traitement/Base_de_donnee/Recuperation.php');

$idCourrier= $_GET['$idCourrier'];
$typeCourrier= $_GET['$typeCourrier'];
$noms_copies = ""; // noms des destinataires de copies
$tableau_lien = []; //tableaux des liens de fichiers
$date_mise_circulation = '';// date de mise en circulation du courrier
$nom_fichier = "";// date mise en circulaton du currier
$tableau_des_noms_des_fichiers_joints = [];

$matricule ='user04' ;

$sql1 = " select p.id_pole, p.nom_pole
from pole p inner join utilisateur u on 
p.id_pole = u.id_pole
where u.Matricule = ?;";

$sql2=" select e.id_entite, e.nom_entite
from entite_banque e inner join utilisateur u on 
e.id_entite = u.id_entite
where u.Matricule = ?;";


// Récupération du nom de l'entité ou du pole à laquelle appartient un utilisateur 
$infos_entite_utilisateur = recupererIdEntiteOuIdPolePourUnUtilisateur($sql2,$matricule);

$infos_pole_utilisateur = recupererIdEntiteOuIdPolePourUnUtilisateur($sql1,$matricule);

if (isset($infos_pole_utilisateur['id_pole'])){

    $nom_entite = recupererNomEntiteParIdUtilisateur($sql1,$matricule);
}

elseif (isset($infos_entite_utilisateur['id_entite'])){

    $nom_entite = recupererNomEntiteParIdUtilisateur($sql2,$matricule);

}


// Récupération des détails pour un courrier arrivé
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

}




// Récupération des détails pour un courrier départ 

elseif ($typeCourrier ==="courrier départ") {


    $sql1 = "SELECT Type_document,Etat_interne_externe as origine_courrier,etat_courrier,
             etat_plis_ferme as plis_ferme,dateEnregistrement,date_mise_circulation,Reference,lien_courrier,Objet_du_courrier,Matricule,destinataire,
             nom_utilisateur as nom_enregistreur,prenom_utilisateur as prenom_enregistreur,nombre_fichiers_joins as nombre_de_fichiers_joins,
             'courrier départ' AS type_courrier,expediteur,numero_ordre, categorie, date_derniere_modification , signature_gouverneur, etat_expedition,
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


    foreach ($T3 as $lien) {
        
        // Ajout du nom à la chaîne, en vérifiant si la chaîne n'est pas vide pour éviter une virgule en début de chaîne
        $tableau_lien []= $lien['lien_fichier_annexe'];
    }
   
    if (count($tableau_lien)>0) {
        foreach ($tableau_lien as $lien_fichier_annexe) {
            $tableau_des_noms_des_fichiers_joints[]= recupererNomFichiers($lien_fichier_annexe);
        }
    }
    
    $sql4 = "SELECT lienFichierReponse, dateReponse
    FROM fichierreponse
    WHERE idCourrierDepart = :idCourrier;";

    $T4 = getInfosForCourrier($sql4,$idCourrier);

}


// Récupération des détails pour un courrier mis en copie 

elseif ($typeCourrier ==="copie courrier") {

    $sql1 = "SELECT
    COALESCE(cd.idCourrier, ca.idCourrier) AS idCourrier,
    COALESCE(cd.Type_document, ca.Type_document) AS Type_document,
    COALESCE(cd.Etat_interne_externe, ca.Etat_interne_externe) AS origine_courrier,
    COALESCE(cd.etat_courrier, ca.etat_courrier) AS etat_courrier,
    COALESCE(cd.etat_plis_ferme, ca.etat_plis_ferme) AS plis_ferme,
    COALESCE(cd.dateEnregistrement, ca.dateEnregistrement) AS dateEnregistrement,
    COALESCE(cd.date_mise_circulation, ca.date_mise_circulation) AS date_mise_circulation,
    COALESCE(cd.Reference, ca.Reference) AS Reference,
    COALESCE(cd.lien_courrier, ca.lien_courrier) AS lien_courrier,
    COALESCE(cd.objet_du_courrier, ca.objet_du_courrier) AS Objet_du_courrier,
    COALESCE(cd.Matricule_initiateur, ca.Matricule_initiateur) AS Matricule,
    COALESCE(cd.destinataire, ca.destinataire) AS destinataire,
    u.nom_utilisateur as nom_enregistreur,
    u.prenom_utilisateur as prenom_enregistreur,
    COALESCE(cd.nombre_fichiers_joins, ca.nombre_fichiers_joins) AS nombre_de_fichiers_joins,

    CASE
        WHEN cd.idCourrier IS NOT NULL THEN 'courrier départ'
        ELSE 'courrier arrivé'
    END AS type_courrier,
    COALESCE(cd.expediteur, ca.expediteur) AS expediteur,
    COALESCE(cd.numero_ordre, ca.numero_ordre) AS numero_ordre,
    COALESCE(cd.categorie, ca.categorie) AS categorie,
    COALESCE(cd.date_derniere_modification, ca.date_derniere_modification) AS date_derniere_modification,
    COALESCE(cd.signature_gouverneur, ca.signature_gouverneur) AS signature_gouverneur
FROM
    copie_courrier cc
    LEFT JOIN courrierdepart cd ON cc.id_courrierDepart = cd.idCourrier
    LEFT JOIN courrierarrive ca ON cc.id_courrierArrive = ca.idCourrier
    INNER JOIN utilisateur u ON COALESCE(cd.Matricule_initiateur, ca.Matricule_initiateur) = u.Matricule
WHERE
    COALESCE(cd.idCourrier, ca.idCourrier) =:idCourrier and cc.nom_destinataire = :nom_entite ;";
    
    $T1 = recupererInfosCopieCourrier($sql1,$idCourrier,$nom_entite);
    
  
    
    
    //Récupérer la date de mise en circulation 

    if (isset($T1[0]['date_mise_circulation'])) {
        $date_mise_circulation = new DateTime($T1[0]['date_mise_circulation']);
        $date_mise_circulation = $date_mise_circulation->format('Y-m-d\TH:i');
    }

     //récupérer le nom du fichier
    if (isset($T1[0]['lien_courrier'])) {
        $nom_fichier = recupererNomFichiers($T1[0]['lien_courrier']);
    }

 
    

    $type_courrier_origine = $T1[0]['type_courrier']; //Cette variable permet de récupérer le type original d'une copie pour pouvoir aller récupérer les fichiers joins ,
    // les copies et le fichier réponse puisque cela n'est pas possible depuis la table copie_courrier
    
    if ($type_courrier_origine ==="courrier arrivé") {
        
        
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
        WHERE idCourrierArrive = :idCourrier;";

        $T4 = getInfosForCourrier($sql4,$idCourrier);




    }

    
    elseif ($type_courrier_origine ==="courrier départ") {
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


        foreach ($T3 as $lien) {
            
            // Ajout du nom à la chaîne, en vérifiant si la chaîne n'est pas vide pour éviter une virgule en début de chaîne
            $tableau_lien []= $lien['lien_fichier_annexe'];
        }

        if (count($tableau_lien)>0) {
            foreach ($tableau_lien as $lien_fichier_annexe) {
                $tableau_des_noms_des_fichiers_joints[]= recupererNomFichiers($lien_fichier_annexe);
            }
        }

        $sql4 = "SELECT lienFichierReponse, dateReponse
        FROM fichierreponse
        WHERE idCourrierDepart = :idCourrier;";

        $T4 = getInfosForCourrier($sql4,$idCourrier);

    }
    
    
    
    
    






}
















?>