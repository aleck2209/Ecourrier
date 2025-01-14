
<?php

require('../../Traitement/Base_de_donnee/Recuperation.php');
require('../../Traitement/Base_de_donnee/Suppression.php');
require('../../Traitement/Base_de_donnee/verificationDonneeBd.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET'){

$idCourrier= $_GET['$idCourrier'];
$typeCourrier= $_GET['$typeCourrier'];
$numero_ordre = $_GET['$numero_ordre'];


$matricule ='user01' ;

$sql1 = " select p.id_pole, p.nom_pole
from pole p inner join utilisateur u on 
p.id_pole = u.id_pole
where u.Matricule = ?;";

$sql2=" select e.id_entite, e.nom_entite
from entite_banque e inner join utilisateur u on 
e.id_entite = u.id_entite
where u.Matricule = ?;";

$habilitation = "supprimer courrier";

// Récupération du nom de l'entité ou du pole à laquelle appartient un utilisateur 
$infos_entite_utilisateur = recupererIdEntiteOuIdPolePourUnUtilisateur($sql2,$matricule);

$infos_pole_utilisateur = recupererIdEntiteOuIdPolePourUnUtilisateur($sql1,$matricule);



if (isset($infos_pole_utilisateur['id_pole'])){
    
    $nom_entite = recupererNomEntiteParIdUtilisateur($sql1,$matricule);
}

elseif (isset($infos_entite_utilisateur['id_entite'])){

    $nom_entite = recupererNomEntiteParIdUtilisateur($sql2,$matricule);

}


// Vérification de l'ahabilitation 
$test = verifierHabilitationUtilisateur($matricule,$habilitation);
 $lien = "";
 $message= "" ;

 if (!$test) {
    $lien = "../../public/page/tableau-bord";
    $message= "Vous n\'êtes pas habilité à supprimer un courrier.";
   

    
} else {
    if ($typeCourrier === "courrier départ") {
        supprimerCourrierDepart($idCourrier,$matricule);
        supprimerCourrierArriveInterne($numero_ordre,$matricule);
        
     $lien = "../../public/page/tableau-bord";
     $message = "Suppression effectuée avec succès";
     
    }
    elseif ($typeCourrier === "courrier arrivé") {
        
        // supprimerCourrierArrive($idCourrier,$matricule);
        $lien = "../../public/page/tableau-bord";
        $message = " Vous n'avez pas d'habilitation pour supprimer ce courrier ";
        
        
     
    }
}



}



 
 


 





?>