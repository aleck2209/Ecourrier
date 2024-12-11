<?php
require_once('../../Traitement/Base_de_donnee/connexion.php');

function updateCourrier($sql, $params) {
    // Exemple d'une requête préparée (utiliser PDO ou autre méthode selon ta configuration)
    try {
        $pdo = connectToDb('localhost','ecourrierdb2','Dba','EcourrierDba');
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}


?>