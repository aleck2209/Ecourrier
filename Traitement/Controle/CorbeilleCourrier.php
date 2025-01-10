<?php 
require('../../Traitement/Base_de_donnee/insertion.php');
require('../../Traitement/Base_de_donnee/verificationDonneeBd.php');


$matricule = 'user01';
$sql1 = " select p.id_pole, p.nom_pole
from pole p inner join utilisateur u on 
p.id_pole = u.id_pole
where u.Matricule = ?;";

$sql2=" select e.id_entite, e.nom_entite
from entite_banque e inner join utilisateur u on 
e.id_entite = u.id_entite
where u.Matricule = ?;";


$habilitation1 = "voir la corbeille par entite";

$habilitation2 = "voir la corbeille par matricule";

$infos_entite_utilisateur = recupererIdEntiteOuIdPolePourUnUtilisateur($sql2,$matricule);

$infos_pole_utilisateur = recupererIdEntiteOuIdPolePourUnUtilisateur($sql1,$matricule);

// Initialisation des tableaux
$tableau_courriers_de_la_Corbeille_matricule = [];
$tableau_courriers_de_la_corbeille_entite_ou_pole = [];


if (isset($infos_pole_utilisateur['id_pole'])){
    $nom_entite = recupererNomEntiteParIdUtilisateur($sql1,$matricule);

}
elseif ( (isset($infos_entite_utilisateur['id_entite']))) {
    $nom_entite = recupererNomEntiteParIdUtilisateur($sql2,$matricule);
 }

$test1 = verifierHabilitationUtilisateur($matricule,$habilitation1);
$test2 = verifierHabilitationUtilisateur($matricule,$habilitation2);


if ($test2) {
   
    $tableau_courriers_de_la_Corbeille_matricule = recupererContenuCorbeilleParMatricule($matricule);

    // Si la requête retourne un tableau vide, on s'assure que le tableau est vide
    if (empty($tableau_courriers_de_la_Corbeille_matricule)) {
        $tableau_courriers_de_la_Corbeille_matricule = [];
    }

}
 elseif ($test1) {
    $tableau_courriers_de_la_corbeille_entite_ou_pole = recupererContenuCorbeilleParEntiteOuPole($nom_entite);
    if (empty($tableau_courriers_de_la_corbeille_entite_ou_pole)) {
        $tableau_courriers_de_la_corbeille_entite_ou_pole = [];
    }

}


?>