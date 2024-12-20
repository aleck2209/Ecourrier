<?php
require('../../Traitement/Base_de_donnee/Recuperation.php');

$matricule = 'user04';
$sql1 = " select p.id_pole, p.nom_pole
from pole p inner join utilisateur u on 
p.id_pole = u.id_pole
where u.Matricule = ?;";

$sql2=" select e.id_entite, e.nom_entite
from entite_banque e inner join utilisateur u on 
e.id_entite = u.id_entite
where u.Matricule = ?;";


$infos_entite_utilisateur = recupererIdEntiteOuIdPolePourUnUtilisateur($sql2,$matricule);

$infos_pole_utilisateur = recupererIdEntiteOuIdPolePourUnUtilisateur($sql1,$matricule);



if (isset($infos_pole_utilisateur['id_pole'])){
    $nom_entite = recupererNomEntiteParIdUtilisateur($sql1,$matricule);

}
elseif ( (isset($infos_entite_utilisateur['id_entite']))) {
    $nom_entite = recupererNomEntiteParIdUtilisateur($sql2,$matricule);
 }




$nom_fichier = ""; // Nom du fichier 

if ($nom_entite ==="BO") {
    $courriers=getCourriersBO($nom_entite); 
    $searchKeyword = ''; 
$startDate = ''; 
$endDate = '';
$sortType = '';
$sortOrder= '';
$origine = '';
$priority = '';
$typeCourrier = '';



if (isset($_POST['form_type1'])) {
    $sortType = $_POST['sortType'] ?? '';
    $sortOrder= $_POST['sortOrder']?? '';
 
}elseif (isset($_POST['form_type2'])) {
    $priority = $_POST['priority']?? '';
    $typeCourrier = $_POST['typeCourrier']?? '';
    $origine = $_POST['Origine']?? '';
} 
  
if (isset($_POST['form_type3'])) {
$startDate = $_POST['startDate']?? '';
$endDate = $_POST['endDate']?? '';
}


if (isset($_POST['form_type4'])) {
    $searchKeyword = $_POST['searchKeyword']?? '';
    
    }

    $courriers = getCourriersBO($nom_entite,$searchKeyword,$startDate,$endDate, $sortType,$sortOrder,$origine,$priority,$typeCourrier);

    if (isset($courriers['lien_courrier'])) {
        $nom_fichier = recupererNomFichiers($courriers); // Récupération du nom du fichier
    }
}



else {
    $courriers=getCourriers($nom_entite);
    
$searchKeyword = ''; 
$startDate = ''; 
$endDate = '';
$sortType = '';
$sortOrder= '';
$origine = '';
$priority = '';
$typeCourrier = '';



if (isset($_POST['form_type1'])) {
    $sortType = $_POST['sortType'] ?? '';
    $sortOrder= $_POST['sortOrder']?? '';
 
}elseif (isset($_POST['form_type2'])) {
    $priority = $_POST['priority']?? '';
    $typeCourrier = $_POST['typeCourrier']?? '';
    $origine = $_POST['Origine']?? '';
} 
  
if (isset($_POST['form_type3'])) {
$startDate = $_POST['startDate']?? '';
$endDate = $_POST['endDate']?? '';
}

if (isset($_POST['form_type4'])) {
    $searchKeyword = $_POST['searchKeyword']?? '';
    
    }

    $courriers = getCourriers($nom_entite,$searchKeyword,$startDate,$endDate, $sortType,$sortOrder,$origine,$priority,$typeCourrier);

    
    if (isset($courriers['lien_courrier'])) {
        $nom_fichier = recupererNomFichiers($courriers); // Récupération du nom du fichier 
    }
}

















?>
