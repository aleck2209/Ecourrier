
<?php

require('../../Traitement/Base_de_donnee/Recuperation.php');

$idCourrier= $_GET['$idCourrier'];
$typeCourrier= $_GET['$typeCourrier'];

$liste_des_infos_historique = recupererHistoriqueParIdCourrierEtType($idCourrier,$typeCourrier);

?>