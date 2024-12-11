<?php
function connectToDb($nomServuer,$nomBaseDonnee,$nomUtilisateur,$motDePasse){
    $dsn = "mysql:host=$nomServuer;dbname=$nomBaseDonnee";
    
    try {
        $objet_bd = new PDO($dsn,$nomUtilisateur,$motDePasse
        );
    //Gestion des erreurs hormis la connexion
      $objet_bd ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    // echo 'connexion au serveur mysql réussie...';
    

    } catch (PDOException $e) {
        die('erreur de connection '.$e->getMessage());
    }
    
    return $objet_bd
;

}

define("nomServeur","localhost");
define('nomBaseDonnee', 'ecourrierdb2');
define('nomUtilisateur', 'Dba');
define('motDePasse','EcourrierDba');
$dbConnection = connectToDb(nomServeur,nomBaseDonnee,nomUtilisateur,motDePasse) ;

?>
