<?php

//Vérifie si une clé appartientient à une table et retourne un booléen
function verifierClePrimaire($cle,$table){
$test = true;
$liste_cle= recupererTouteLesClePrimaires($table);

for ($i=0; $i <count($liste_cle) ; $i++) { 
    if($cle == $liste_cle[$i]){
       $test=false;
    }
    
}

echo $test;
return $test;
}


function verifierHabilitationUtilisateur($matricule, $libelle) {
    // Utilisation de l'objet pour la connexion à la base de données
    $conn = connectToDb('localhost', 'ecourrierdb2', 'Dba', 'EcourrierDba');

    try {
        // Étape 1 : Vérifier si l'utilisateur existe
        $stmt1 = $conn->prepare("SELECT code_profile FROM utilisateur WHERE Matricule = :matricule");
        $stmt1->bindParam(':matricule', $matricule, PDO::PARAM_STR);
        $stmt1->execute();

        // Vérifier si un utilisateur existe pour ce matricule
        if ($stmt1->rowCount() == 0) {
            echo "Aucun utilisateur trouvé pour ce matricule.";
            return false; // L'utilisateur n'existe pas
        }

        // Récupérer le code du profil de l'utilisateur
        $user = $stmt1->fetch(PDO::FETCH_ASSOC);
        $codeProfil = $user['code_profile'];

        // Étape 2 : Vérifier si le libellé d'habilitation existe
        $stmt2 = $conn->prepare("SELECT idHabilitation FROM habilitation WHERE libelle = :libelle");
        $stmt2->bindParam(':libelle', $libelle, PDO::PARAM_STR);
        $stmt2->execute();

        // Vérifier si l'habilitation avec ce libellé existe
        if ($stmt2->rowCount() == 0) {
            echo "Aucune habilitation trouvée pour le libellé: " . $libelle;
            return false; // L'habilitation n'existe pas
        }

        // Récupérer l'idHabilitation
        $habilitation = $stmt2->fetch(PDO::FETCH_ASSOC);
        $idHabilitation = $habilitation['idHabilitation'];

        // Étape 3 : Vérifier si le profil est lié à cette habilitation
        $stmt3 = $conn->prepare("
            SELECT 1
            FROM habilitation_profile hp
            WHERE hp.code = :codeProfile
            AND hp.idHabilitation = :idHabilitation
        ");
        $stmt3->bindParam(':codeProfile', $codeProfil, PDO::PARAM_STR);
        $stmt3->bindParam(':idHabilitation', $idHabilitation, PDO::PARAM_INT);
        $stmt3->execute();

        // Vérifier si le profil est bien lié à l'habilitation
        if ($stmt3->rowCount() > 0) {
            // echo "L'utilisateur a l'habilitation: " . $libelle;
            return true; // L'utilisateur a l'habilitation
        } else {
            // echo "L'utilisateur n'a pas l'habilitation: " . $libelle;
            return false; // L'utilisateur n'a pas l'habilitation
        }
    } catch (PDOException $e) {
        // Gestion des erreurs de connexion ou de requêtes
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}


?>