
<?php

require('../../Traitement/Base_de_donnee/Recuperation.php');
require('../../Traitement/Base_de_donnee/Suppression.php');
require('../../Traitement/Base_de_donnee/verificationDonneeBd.php');

$idCourrier= $_GET['$idCourrier'];
$typeCourrier= $_GET['$typeCourrier'];


$matricule ='user04' ;

$sql1 = " select p.id_pole, p.nom_pole
from pole p inner join utilisateur u on 
p.id_pole = u.id_pole
where u.Matricule = ?;";

$sql2=" select e.id_entite, e.nom_entite
from entite_banque e inner join utilisateur u on 
e.id_entite = u.id_entite
where u.Matricule = ?;";

$habilitation = "supprimer courrier";

// Vérification de l'ahabilitation 
$test = verifierHabilitationUtilisateur($matricule,$habilitation);


// Récupération du nom de l'entité ou du pole à laquelle appartient un utilisateur 
$infos_entite_utilisateur = recupererIdEntiteOuIdPolePourUnUtilisateur($sql2,$matricule);

$infos_pole_utilisateur = recupererIdEntiteOuIdPolePourUnUtilisateur($sql1,$matricule);

if (isset($infos_pole_utilisateur['id_pole'])){
    
    $nom_entite = recupererNomEntiteParIdUtilisateur($sql1,$matricule);
}

elseif (isset($infos_entite_utilisateur['id_entite'])){

    $nom_entite = recupererNomEntiteParIdUtilisateur($sql2,$matricule);

}



if (!$test) {
    die( '<script>
    alert("Vous n\'êtes pas habilité à supprimer un courrier.");
    setTimeout(function(){
        window.location.href = "../../public/page/tableau-bord.php";
    }, 500); 
    </script>'
    );
}


if ($typeCourrier === "courrier départ") {
    supprimerCourrierDepart($idCourrier,$matricule);
    
    die( '<script>
alert("Suppression effectuée.");
setTimeout(function(){
    window.location.href = "../../public/page/tableau-bord.php";
}, 500); 
</script>'
);
} 
 
elseif ($typeCourrier === "courrier arrivé") {
    supprimerCourrierArrive($idCourrier,$matricule);

    die( '<script>
 alert("Suppression effectuée.");
 setTimeout(function(){
     window.location.href = "../../public/page/tableau-bord.php";
 }, 500); 
 </script>'
 );

 }

 





?>