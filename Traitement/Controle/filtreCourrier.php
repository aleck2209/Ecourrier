<?php
require('../../Traitement/Base_de_donnee/Recuperation.php');


$requete = " select e.id_entite, e.nom_entite
from entite_banque e inner join utilisateur u on 
e.id_entite = u.id_entite
where u.Matricule = ?;";
$matricule = 'user01';

$nom_entite = recupererNomEntiteParIdUtilisateur($requete,$matricule);



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
    $startDate = $_POST['searchKeyword']?? '';
    
    }

    $courriers = getCourriersBO($nom_entite,$searchKeyword,$startDate,$endDate, $sortType,$sortOrder,$origine,$priority,$typeCourrier);


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

}

















?>
