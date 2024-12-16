
<?php

require('../../Traitement/Base_de_donnee/Recuperation.php');

$idCourrier= $_GET['$idCourrier'];
$typeCourrier= $_GET['$typeCourrier'];
var_dump( $_GET);

$liste_des_infos_historique = recupererHistoriqueParIdCourrierEtType($idCourrier,$typeCourrier);
print_r($liste_des_infos_historique)

?>