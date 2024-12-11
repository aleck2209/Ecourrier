<?php
require('../../Traitement/Base_de_donnee/connexion.php');
$serveur = 'localhost';
$bd='ecourrierdb2';
$utilisateur = 'Dba';
$mot_de_passe = 'EcourrierDba';
function creerObjetconnexion($a,$b,$c,$d){
    
    return $connexiondb = connectToDb($a,$b,$c,$d);
}

$objet_connexion_db = creerObjetconnexion($serveur,$bd,$utilisateur,$mot_de_passe)

?>






