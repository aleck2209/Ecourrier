<?php
require('../../Traitement/Base_de_donnee/insertion.php');
require('../../Traitement/Verification/verifierValeursNullesCourrier.php');
require('../../Traitement/Controle/gestionFichierCourrierDepart.php');
require('../../Traitement/Controle/insertionCopieCourrier.php');
require('../../Traitement/Verification/VerifierNumeroOrdreParEntite.php');
require('../../Traitement/Verification/verifierFormat.php');
require('../../Traitement/Verification/verifierValeurEnum.php');
require('../../Traitement/Controle/gestionFichiesCourrierArrive.php');


// On commence par désactiver l'affichage des erreurs PHP en production
ini_set('display_errors', 0); // Désactive l'affichage des erreurs
error_reporting(E_ALL); // Active l'enregistrement des erreurs pour le débogage (peut être modifié en production)

        //récupération des données provenant du formulaire et vérification des données
$numeroOrdre = verifierValeurNulle(trim($_POST['numero_ordre']));
$TypeDoc = verifierValeurNulle(trim($_POST['Type_document']));
//$etat_inter_exter= verifierValeurNulle(trim($_POST['Etat_interne_externe'])) ;
$etat_plis_ferme = verifierValeurNulle(trim($_POST['etat_plis_ferme']));
$categorie =verifierValeurNulle(trim($_POST['categorie']));
$dateEnreg =verifierValeurNulle(trim($_POST['dateEnregistrement']));
$reference =verifierValeurNulle(trim($_POST['Reference']));
$fichier = gererFormat($_FILES['fichier']);
$objet = verifierValeurNulle(trim($_POST['Objet_du_courrier']));
$matricule ='user02' ;
$etatExpedition =  NULL ;
$expediteur = null ;
$destinataire  = verifierValeurNulle($_POST['destinataire']) ;
$liste_copie_courrier = verifierValeurNulle(trim($_POST['copie_courrier'])) ;
$test_etat_interne_externe =trim($_POST['etat_interne_externe']);
$etat_inter_exter;


$identite_dest ;
$idpole_dest;
$idReponse = null;

//-----------------------------------------Test des valeurs de l'état interne externe et de l'état expédition

$etat_inter_exter = ($test_etat_interne_externe =="interne") ? "courrier interne": "courrier externe" ;

$etatExpedition = null;
//récupération du tableau des entite destinataires ayant le nom entré 










//---------------------------------------Controle des nom destinataires----------------------------------

if (!is_null($destinataire)) {
    $Liste_entite_destinataire = recupererLigneSpecifique('entite_banque','nom_entite',$destinataire);
$Liste_pole_destinataire = recupererLigneSpecifique('pole','nom_pole',$destinataire);
//on récupère le nom et le format du fichier dans un tableau
$TableauNomDestinataireCopie = explode(",",$liste_copie_courrier) ;
if (isset($Liste_entite_destinataire)) {
    $objet_entite_banque = $Liste_entite_destinataire[0];
    $identite_dest = $objet_entite_banque->id_entite;
} else {
    $identite_dest = null;
}


if (isset($Liste_pole_destinataire)) {
    $objet_pole_dest = $Liste_pole_destinataire[0];
    $idpole_dest = $objet_pole_dest->id_pole;
}else {
    $idpole_dest = null;
} 
} else {
    // die('veuillez entrer un destinataire');
    die('<script>
            alert("Veuillez entrer un destinataire.");
           setTimeout(function(){
                window.location.href = "../../public/page/courrier-interne.php";
            }, 500); 
      </script>');
    
}


//------------------------------------fin controle des nom destinataires---------------------------------------








$liendossier = creerListeDossiersCourrierDepart($etat_inter_exter,$destinataire);
$liencourrier = deposerFichierDansDossier($liendossier,$fichier);
$nom_balise_fichiers_join ="fichiers_joints"; //Cette variable récupère la valeur de l'attribut name spécifié dans la balise html qui envoi les fichiers annexes
   
$chemin_fichiers_joins = $liendossier."/FichierAnnexes";//Cette variable repésente le lien du dossier où on doit stocker les fichiers annexes

$liens_fichiers_joins = get_uploaded_files_paths($chemin_fichiers_joins,$nom_balise_fichiers_join);

// print_r($liens_fichiers_joins);

$liens_fichiers_joins_arrives = get_uploaded_files_pathsarrive($chemin_fichiers_joins,$nom_balise_fichiers_join);

$formatCourrier = pathinfo($fichier['name'],PATHINFO_EXTENSION);



//-------------------------------------------Controle du numero d'ordre--------------------------------
#Récupération du nom de l'entité à laquelle est relié un utilisateur
$requete = " select e.id_entite, e.nom_entite
from entite_banque e inner join utilisateur u on 
e.id_entite = u.id_entite
where u.Matricule = ?;";
$nom_entite = recupererNomEntiteParIdUtilisateur($requete,$matricule);
$expediteur = $nom_entite;
#On récupère le numéro d'ordre qu'on doit entré en fonction de l'entité
$num_a_entrer = verifierNumeoOrdreParEntiteV2($nom_entite);
$numeroOrdrePrefix = explode('/', $numeroOrdre)[0];  // On récupère juste la partie avant le "/"

// On compare le numéro d'ordre entré à celui qui est attendu en fonction de l'entité
if ($numeroOrdrePrefix != $num_a_entrer) {
    die("Le numéro d'ordre pour l'entité $nom_entite attendu est : $num_a_entrer");
}
//-------------------------------------fin controle numero d'ordre-------------------------------------





//---------------------------------------Controle des destinataires internes--------------------------------

if (is_null($destinataire)) {
    echo 'destinataire nul';
    die("Vous n'avez pas saisi de destinataire");
}else{
    if ($etat_inter_exter==="courrier interne") {
        if (is_null($identite_dest) && is_null($idpole_dest)) {
            # Si on entre ici cela veut dire qu'il n'a pas entrer un destinataire interne à la banque
            die('<script>alert("erreur  destinataire incorrecte")</script>');
        }
        
    }elseif ($etat_inter_exter==="courrier externe") {
        if (!is_null($identite_dest) || !is_null($idpole_dest)) {
            die("Vous avez défini une entité interieure à la banque comme destinataire d'un courrier externe ");
        }
    }
    
}





//-------------------------------------Fin controle des destinataires internes------------------------------


//------------------------------------Controle des valeurs énum------------------------------------------------

#Ici on vérifie si une valeure entrée correspond à celle attendue dans la base de données
$etat_inter_exter = verifierValeurEnum($etat_inter_exter,['courrier interne','courrier externe'],'etat interne externe');
$etat_plis_ferme = verifierValeurEnum($etat_plis_ferme,['oui','non'],'etat plis fermé');
//$etatExpedition = verifierValeurEnum($etatExpedition,['oui','non'],'etat expédition');
$categorie = verifierValeurEnum($categorie,['urgent','normal'],'catégorie');



//---------------------------------------Controle du nombre de fichiers joins par rapport----------------------------

try {
    // Récupérer et nettoyer le champ 'nombre_joins'
    $nombre_fichiers_joins = trim($_POST['nombre_joins']);

    // Cas où 'nombre_joins' est vide et aucun fichier n'est joint
    if (empty($nombre_fichiers_joins) && count($liens_fichiers_joins) == 0) {
        // Aucun fichier joint et pas de nombre renseigné => Le programme continue normalement.
        $nombre_fichiers_joins = 0;
        
    }
    // Cas où 'nombre_joins' est renseigné
    elseif (!empty($nombre_fichiers_joins)) {
        // Vérifier que 'nombre_joins' est un entier
        if (!ctype_digit($nombre_fichiers_joins)) {
            die("Le nombre de fichiers joints est impérativement un nombre entier positif, veuillez entrer un nombre entier.");
        }

        // Convertir en entier
        $nombre_fichiers_joins = (int)$nombre_fichiers_joins;
        
        // Si le nombre renseigné ne correspond pas au nombre de fichiers joints
        if ($nombre_fichiers_joins != count($liens_fichiers_joins)) {
            die("Le nombre de fichiers joints renseigné ($nombre_fichiers_joins) ne correspond pas au nombre de fichiers sélectionnés (" . count($liens_fichiers_joins) . "). Veuillez entrer le bon nombre de fichiers.");
        }
    }
    // Cas où 'nombre_joins' n'est pas renseigné mais des fichiers sont joints
    elseif (empty($nombre_fichiers_joins) && count($liens_fichiers_joins) > 0) {
        die("Le nombre de fichiers joints doit être précisé, veuillez entrer un nombre.");
    }
    // Cas où 'nombre_joins' est renseigné mais aucun fichier n'est joint
    elseif ($nombre_fichiers_joins > 0 && count($liens_fichiers_joins) == 0) {
        die("Vous avez précisé un nombre de fichiers joints, mais aucun fichier n'a été sélectionné.");
    }

    // Si les deux sont renseignés et sont égaux, ou si aucun des deux n'est renseigné (et pas de fichiers joints), le programme continue normalement

} catch (Exception $e) {
    die("Une erreur s'est produite : " . $e->getMessage());
}


if (is_null($_POST['Objet_du_courrier'])) {
    die('<script>alert("erreur  Objet post non renseigné")</script>');
}
if (strlen($objet)==0) {
    die('<script>alert("erreur  Objet non renseigné")</script>');
} elseif (strlen($numeroOrdre)==0) {
    die("Vous n'avez pas renseigné un numéro d'ordre pour votre courrier");
} elseif (strlen($numeroOrdre)==0) {
    die("Vous n'avez pas renseigné une date d'enregistrement d'ordre pour votre courrier");
}elseif (strlen($TypeDoc)==0) {
    die("Vous n'avez pas renseigné un type de document pour votre courrier");
}










//----------------------------------------------Fin controle-----------------------------------------------
$etatCourrier = 'envoyé';
$idcourrierdepart = insererCourrierDepart($numeroOrdre,$TypeDoc,$etat_inter_exter,
$etat_plis_ferme,$categorie,$dateEnreg,null,$reference,
$liencourrier,$formatCourrier,$objet,$matricule,$idReponse,$etatExpedition,$expediteur,$destinataire,$identite_dest,$idpole_dest,
$nombre_fichiers_joins,$etatCourrier
);

//---------------------------------------------Insertion des copies de courriers dans la base de données----------------------------- 

if (!in_array(null,$TableauNomDestinataireCopie)) {
     entrerLesCopies($TableauNomDestinataireCopie,$liencourrier,$idcourrierdepart,null); 
    
}


//---------------------------------------------Insérer les fichiers joins - ----------------------------------------

if ($nombre_fichiers_joins ===count($liens_fichiers_joins)) {
    foreach ($liens_fichiers_joins as $lien) {
        insererFichierJoin($lien,$idcourrierdepart,null);
    }
    
}






//--------------------------------------------insertion automatique du courrier arrivé de ce destinataire----------------------------
$etatCourrier = 'reçu';
$idcourrierArrive = insererCourrierArriveV2($numeroOrdre,$TypeDoc,$etat_inter_exter,
$etat_plis_ferme,$categorie,$dateEnreg,null,$reference,
$liencourrier,$formatCourrier,$objet,$matricule,$idReponse,$expediteur,$destinataire,$identite_dest,$idpole_dest,
$nombre_fichiers_joins,$etatCourrier
);

//--------------------------------------------insertion automatique du courrier arrivé de ce destinataire----------------------------

if ($nombre_fichiers_joins ===count($liens_fichiers_joins_arrives )) {
    foreach ($liens_fichiers_joins_arrives  as $lien) {
        insererFichierJoin($lien,null,$idcourrierArrive);
    }
    
}






die( '<script>
alert("Votre action a été effectuée avec succès.");
setTimeout(function(){
    window.location.href = "../../public/page/courrier-interne.php";
}, 500); 
</script>'
);






?>



