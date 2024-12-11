<?php
require('../../Traitement/Base_de_donnee/Recuperation.php');
    
$requete = " select e.id_entite, e.nom_entite
from entite_banque e inner join utilisateur u on 
e.id_entite = u.id_entite
where u.Matricule = ?;";
$matricule = 'user01';
$nom_entite = recupererNomEntiteParIdUtilisateur($requete,$matricule);

ini_set('display_errors', 0); // Désactive l'affichage des erreurs
error_reporting(E_ALL); 
// var_dump($_POST);


// Vérifie si le formulaire a été soumis et récupère les valeurs
if (isset($_POST['form_type1'])) {
    $formType = $_POST['form_type1'];

    // Récupère les valeurs de $_POST pour chaque formulaire
    // Exemple, tu dois définir le nom de l'entité selon l'utilisateur
   
    $searchKeyword = $_POST['searchKeyword'] ?? ''; 
    $startDate = $_POST['startDate'] ?? '';
    $endDate = $_POST['endDate'] ?? '';
    $sortType = $_POST['sortType'] ?? '';
    $sortOrder = $_POST['ordre'] ?? '';
    $origine = $_POST['origine'] ?? '';
    $priority = $_POST['priorite'] ?? '';
    $typeCourrier = $_POST['typeCourrier'] ?? '';

    // Appel de la fonction pour récupérer les courriers
    $courriers = getCourriers($nom_entite, $searchKeyword, $startDate, $endDate, $sortType, $sortOrder, $origine, $priority, $typeCourrier);
    
     print_r($courriers);
    // Maintenant, tu peux afficher les résultats dans ton tableau HTML
    foreach ($courriers as $courrier) {
        echo "<div class='element-dashboard-mail'>";
        echo "<output name=''>{$courrier['numero_ordre']}</output>";
        echo "<output name=''>{$courrier['objet_du_courrier']}</output>";
        echo "<output name=''>{$courrier['lien_courrier']}</output>";
        echo "<output name=''>{$courrier['destinataire']}</output>";
        echo "<output name=''>{$courrier['dateEnregistrement']}</output>";
        echo "<output name=''>{$courrier['etat_courrier']}</output>";
        echo "</div>";
    }
}




// print_r($courriers);
// echo (count($courriers));

?>