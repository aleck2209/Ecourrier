
<?php

// Démarrer la session
session_start();


require('../../Traitement/Base_de_donnee/Recuperation.php');

$idCourrier= $_GET['$idCourrier'];
$typeCourrier= $_GET['$typeCourrier'];

$matricule = $_SESSION['matricule']  ;

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


if ($typeCourrier ==="copie courrier") {
    $liste_des_infos_historique = recupererHistoriqueCopieCourrier($idCourrier,$nom_entite);
} else {
    $liste_des_infos_historique = recupererHistoriqueParIdCourrierEtType($idCourrier,$typeCourrier);
  
}




?>