<?php
require('../../Traitement/Base_de_donnee/insertion.php');
require('../../Traitement/Verification/verifierValeursNullesCourrier.php');
require('../../Traitement/Controle/gestionFichiesCourrierArrive.php');
require('../../Traitement/Verification/VerifierNumeroOrdreParEntite.php');
require('../../Traitement/Verification/verifierFormat.php');
require('../../Traitement/Verification/verifierValeurEnum.php');

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
$fichier = $_FILES['fichier'];
$objet = verifierValeurNulle(trim($_POST['Objet_du_courrier']));
$matricule ='user03' ;
$etatExpedition = null;
$expediteur = verifierValeurNulle(trim($_POST['expediteur'])) ; 
$destinataire  = verifierValeurNulle($_POST['destinataire']) ;
// $liste_copie_courrier = verifierValeurNulle(trim($_POST['copie_courrier'])) ;
$test_etat_interne_externe =trim($_POST['etat_interne_externe']);
$etat_inter_exter;

$identite_dest ;
$idpole_dest;
$idReponse = null;


//-----------------------------------------Test des valeurs de l'état interne externe et de l'état expédition

$etat_inter_exter = ($test_etat_interne_externe =="externe") ? "courrier externe": "courrier interne" ;

$etatExpedition = null;
//récupération du tableau des entite destinataires ayant le nom entré 

// var_dump($_POST);
// var_dump($_FILES);


//---------------------------------------Controle des nom destinataires----------------------------------
$Liste_entite_destinataire = recupererLigneSpecifique('entite_banque','nom_entite',$destinataire);
$Liste_pole_destinataire = recupererLigneSpecifique('pole','nom_pole',$destinataire);
//on récupère le nom et le format du fichier dans un tableau
// $TableauNomDestinataireCopie = explode(",",$liste_copie_courrier) ;
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
//------------------------------------fin controle des nom destinataires---------------------------------------



if ($etat_plis_ferme==="non") {


$liendossier = creerListeDossiersCourrierArrive($etat_inter_exter,$destinataire);
$liencourrier =deposerFichierDansDossierCourrierArrive($liendossier,$fichier);
$nom_balise_fichiers_join ="fichiers_joints"; //Cette variable récupère la valeur de l'attribut name spécifié dans la balise html qui envoi les fichiers annexes
   
$chemin_fichiers_joins = $liendossier."/FichierAnnexes";//Cette variable repésente le lien du dossier où on doit stocker les fichiers annexes

$liens_fichiers_joins = get_uploaded_files_pathsarrive($chemin_fichiers_joins,$nom_balise_fichiers_join);

print_r($liens_fichiers_joins);


if (isset($fichier)) {
    $formatCourrier = pathinfo($fichier['name'],PATHINFO_FILENAME); # code...
}else{
    $formatCourrier = null;
}

} else  {
    $liendossier = '';
    $liencourrier = "";
}


//-------------------------------------------Controle du numero d'ordre--------------------------------
#Récupération du nom de l'entité à laquelle est relié un utilisateur
$requete = " select e.id_entite, e.nom_entite
from entite_banque e inner join utilisateur u on 
e.id_entite = u.id_entite
where u.Matricule = ?;";
$nom_entite = recupererNomEntiteParIdUtilisateur($requete,$matricule);

#On récupère le numéro d'ordre qu'on doit entré en fonction de l'entité
$num_a_entrer = verifierNumeoOrdreParEntiteCourrierArv($nom_entite);
$numeroOrdrePrefix = explode('/', $numeroOrdre)[0];  // On récupère juste la partie avant le "/"

// On compare le numéro d'ordre entré à celui qui est attendu en fonction de l'entité
if ($numeroOrdrePrefix != $num_a_entrer) {
    die("Le numéro d'ordre pour l'entité $nom_entite attendu est : $num_a_entrer");
}
//-------------------------------------fin controle numero d'ordre-------------------------------------

// ---------------------------------------Ici nous vérifions si le fichier à été envoyé  

if ($etat_plis_ferme==="non") {
    if (strlen($_FILES['fichier']['name'])==0) {
        die("Vous n'avez pas choisi un fichier");
    }
   
}




//---------------------------------------Controle des destinataires internes--------------------------------

if (is_null($destinataire)) {
    echo 'destinataire nul';
    die("Vous n'avez pas saisi de destinataire");
}else{
    
        if (is_null($identite_dest) && is_null($idpole_dest)) {
            # Si on entre ici cela veut dire qu'il n'a pas entrer un destinataire interne à la banque
            die("Vous n'avez pas entré comme destinataire un service de la banque");
        }
        
  
}
//-------------------------------------Fin controle des destinataires internes------------------------------



//--------------------------------------------Contrôl des valeurs des expéditeurs ---------------------------------------------------

// ------------------------------------L'expéditeur ne doit pas être une entité dans le 
$Liste_entite_expediteur = recupererLigneSpecifique('entite_banque','nom_entite',$expediteur);
$Liste_pole_expediteur = recupererLigneSpecifique('pole','nom_pole',$expediteur);


if (isset($Liste_entite_expediteur)) {
    $objet_entite_banque_expediteur = $Liste_entite_expediteur[0];
    $identite_expediteur = $objet_entite_banque_expediteur->id_entite;
} else {
    $identite_dest_expediteur = null;
}


if (isset($Liste_pole_expediteur)) {
    $objet_pole_expediteur = $Liste_pole_expediteur[0];
    $idpole_expediteur = $objet_pole_expediteur->id_pole;
}else {
    $idpole_expediteur = null;
} 

if (isset($expediteur)) {
    if (!is_null($identite_expediteur) || !is_null($idpole_expediteur)) {
        die('<script>alert("erreur  l\'entité '. $expediteur .' mentionné comme expéditeur d\'un courrier arrivé externe est interne à banque. Veuillez bien renseigner ce champs")</script>');

    }
}

//---------------------------------------------------Fin du contrôle expéditeurs----------------------------------



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


if (is_null($objet)) {
    die("Vous n'avez pas renseigné un objet pour votre courrier");
} elseif (strlen($numeroOrdre)==0) {
    die("Vous n'avez pas renseigné un numéro d'ordre pour votre courrier");
} elseif (strlen($dateEnreg)==0) {
    die("Vous n'avez pas renseigné une date d'enregistrement d'ordre pour votre courrier");
}elseif (strlen($TypeDoc)==0 && $etat_plis_ferme==="non") {
    die("Vous n'avez pas renseigné un type de document pour votre courrier");
} elseif (!isset($fichier) && $etat_plis_ferme==="non" ) {
    die("Vous n'avez pas renseigné un expéditeur pour votre courrier");
}






//----------------------------------------------Fin controle-----------------------------------------------
$etatCourrier = 'reçu';


$idcourrierArrive = insererCourrierArriveV2($numeroOrdre,$TypeDoc,$etat_inter_exter,
$etat_plis_ferme,$categorie,$dateEnreg,null,$reference,
$liencourrier,$objet,$matricule,$idReponse,$expediteur,$destinataire,$identite_dest,$idpole_dest,
$nombre_fichiers_joins,$etatCourrier
);

insertHistorique("enregistrement du courrier",$idcourrierArrive,$nom_entite,"courrier arrivé");


//---------------------------------------------Insérer les fichiers joins - ----------------------------------------

if ($nombre_fichiers_joins ===count($liens_fichiers_joins)) {
    foreach ($liens_fichiers_joins as $lien) {
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



