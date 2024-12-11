<?php

// Inclusion de la fonction de mise à jour
require('../../Traitement/Base_de_donnee/Update.php');

// Vérifier que les données ont été envoyées via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $idCourrier = $_POST['idCourrier'];
    $typeCourrier = $_POST['typeCourrier'];
    $objet = $_POST['objet'];
    $expediteur = $_POST['expediteur'];
    $destinataire = $_POST['destinataire'];
    $etat_courrier = $_POST['etat_courrier'];
    $dateEnregistrement = $_POST['dateEnregistrement'];
    $numero_ordre = $_POST['numero_ordre'];

    // Vérifier les données (simple exemple de validation)
    if (empty($objet) || empty($expediteur) || empty($destinataire) || empty($etat_courrier)) {
        die("Tous les champs sont obligatoires.");
    }

    // Mettre à jour les informations dans la base de données en fonction du type de courrier
    if ($typeCourrier === "courrier arrivé") {
        $sql = "UPDATE courrierarrive SET Objet_du_courrier = :objet, expediteur = :expediteur, 
                destinataire = :destinataire, etat_courrier = :etat_courrier, 
                dateEnregistrement = :dateEnregistrement, numero_ordre = :numero_ordre 
                WHERE idCourrier = :idCourrier";
    } elseif ($typeCourrier === "courrier départ") {
        $sql = "UPDATE courrierdepart SET Objet_du_courrier = :objet, expediteur = :expediteur, 
                destinataire = :destinataire, etat_courrier = :etat_courrier, 
                dateEnregistrement = :dateEnregistrement, numero_ordre = :numero_ordre 
                WHERE idCourrier = :idCourrier";
    } else {
        die("Type de courrier invalide");
    }

    // Exécution de la requête de mise à jour
    $params = [
        ':objet' => $objet,
        ':expediteur' => $expediteur,
        ':destinataire' => $destinataire,
        ':etat_courrier' => $etat_courrier,
        ':dateEnregistrement' => $dateEnregistrement,
        ':numero_ordre' => $numero_ordre,
        ':idCourrier' => $idCourrier
    ];

    // Appel de la fonction pour mettre à jour la base de données
    updateCourrier($sql, $params);

    echo "Courrier modifié avec succès.";
}

// Fonction de mise à jour (à adapter selon ta logique d'accès à la base de données)
function updateCourrier($sql, $params) {
    // Exemple d'une requête préparée (utiliser PDO ou autre méthode selon ta configuration)
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=ma_base_de_donnees', 'utilisateur', 'motdepasse');
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

?>





