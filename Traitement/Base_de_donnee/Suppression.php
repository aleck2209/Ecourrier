<?php 

function supprimerCourrier($idCourrier, $typeCourrier, $matricule) {
    try {
        // Connexion à la base de données
        $objet_connexion = connectToDb('localhost','ecourrierdb2','Dba','EcourrierDba');
        
        // Démarrer une transaction pour garantir que toutes les opérations se font atomiquement
        $objet_connexion->beginTransaction();

        // Déterminer le type de table (courrierdepart ou courrierarrive)
        if ($typeCourrier == 'courrier départ') {
            $tableCourrier = 'courrierdepart';
            $idCourrierDepart = $idCourrier;
            $idCourrierArrive = null;  // Pas de courrier arrivé pour un courrier départ
        } elseif ($typeCourrier == 'courrier arrivé') {
            $tableCourrier = 'courrierarrive';
            $idCourrierDepart = null;  // Pas de courrier départ pour un courrier arrivé
            $idCourrierArrive = $idCourrier;
        } else {
            throw new Exception("Type de courrier invalide");
        }

        // Préparer la requête pour récupérer les données du courrier
        $query = "SELECT * FROM $tableCourrier WHERE idCourrier = :idCourrier";
        $stmt = $objet_connexion->prepare($query);
        $stmt->bindParam(':idCourrier', $idCourrier, PDO::PARAM_INT);
        $stmt->execute();
        
        $courrier = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$courrier) {
            throw new Exception("Courrier non trouvé");
        }

        // Générer un identifiant pour la corbeille
        $idCorbeille = incrementerClePrimaireNumerique('corbeille');

        // Préparer les données pour l'insertion dans la table corbeille
        $dataCorbeille = [
            ':idCorbeille' => $idCorbeille,
            ':idCourrierDepart' => $idCourrierDepart,
            ':idCourrierArrive' => $idCourrierArrive,
            ':Type_document' => $courrier['Type_document'],
            ':Etat_interne_externe' => $courrier['Etat_interne_externe'],
            ':etat_courrier' => $courrier['etat_courrier'],
            ':etat_plis_ferme' => $courrier['etat_plis_ferme'],
            ':dateEnregistrement' => $courrier['dateEnregistrement'],
            ':date_mise_circulation' => $courrier['date_mise_circulation'],
            ':Reference' => $courrier['Reference'],
            ':lien_courrier' => $courrier['lien_courrier'],
            ':Matricule_initiateur' => $courrier['Matricule_initiateur'],
            ':idFichierReponse' => isset($courrier['idFichierReponse']) ? $courrier['idFichierReponse'] : null,
            ':etat_expedition' => ($typeCourrier == 'courrier départ') ? $courrier['etat_expedition'] : null,
            ':expediteur' => isset($courrier['expediteur']) ? $courrier['expediteur'] : null,
            ':destinataire' => isset($courrier['destinataire']) ? $courrier['destinataire'] : null,
            ':entite_dest' => isset($courrier['entite_dest']) ? $courrier['entite_dest'] : null,
            ':pole_destinataire' => ($typeCourrier == 'courrier arrivé') ? $courrier['pole_destinataire'] : null,
            ':categorie' => isset($courrier['categorie']) ? $courrier['categorie'] : null,
            ':numero_ordre' => isset($courrier['numero_ordre']) ? $courrier['numero_ordre'] : null,
            ':nombre_fichiers_joins' => isset($courrier['nombre_fichiers_joins']) ? $courrier['nombre_fichiers_joins'] : 0,
            ':date_derniere_modification' => isset($courrier['date_derniere_modification']) ? $courrier['date_derniere_modification'] : null,
            ':signature_gouverneur' => isset($courrier['signature_gouverneur']) ? $courrier['signature_gouverneur'] : null,
            ':date_suppression' => date('Y-m-d H:i:s'),
            ':Matricule_agent' => $matricule,  // Remplacer par le matricule de l'agent qui effectue l'action
            ':objet_du_courrier' => isset($courrier['objet_du_courrier']) ? $courrier['objet_du_courrier'] : null // Objet du courrier pour les deux types
        ];

        // Insérer le courrier dans la table corbeille
        $queryCorbeille = "INSERT INTO corbeille (idCorbeille, idCourrierDepart, idCourrierArrive, Type_document, Etat_interne_externe, 
                           etat_courrier, etat_plis_ferme, dateEnregistrement, date_mise_circulation, Reference, lien_courrier, 
                           objet_du_courrier, Matricule_initiateur, idFichierReponse, etat_expedition, expediteur, destinataire, 
                           entite_dest, pole_destinataire, categorie, numero_ordre, nombre_fichiers_joins, 
                           date_derniere_modification, signature_gouverneur, date_suppression, Matricule_agent)
                           VALUES (:idCorbeille, :idCourrierDepart, :idCourrierArrive, :Type_document, :Etat_interne_externe, :etat_courrier,
                                   :etat_plis_ferme, :dateEnregistrement, :date_mise_circulation, :Reference, :lien_courrier,
                                   :objet_du_courrier, :Matricule_initiateur, :idFichierReponse, :etat_expedition, :expediteur,
                                   :destinataire, :entite_dest, :pole_destinataire, :categorie, :numero_ordre, 
                                   :nombre_fichiers_joins, :date_derniere_modification, :signature_gouverneur, 
                                   :date_suppression, :Matricule_agent)";

        $stmtCorbeille = $objet_connexion->prepare($queryCorbeille);
        $stmtCorbeille->execute($dataCorbeille);

        // Vérifier s'il existe des fichiers annexes associés à ce courrier et les ajouter dans la corbeille_fichier_annexe
        $queryFichierAnnexe = "SELECT * FROM fichier_annexe WHERE idCourrierDep = :idCourrier OR idCourrierArv = :idCourrier";
        $stmtFichier = $objet_connexion->prepare($queryFichierAnnexe);
        $stmtFichier->bindParam(':idCourrier', $idCourrier, PDO::PARAM_INT);
        $stmtFichier->execute();

        while ($fichier = $stmtFichier->fetch(PDO::FETCH_ASSOC)) {
            $idFichierAnnexeCorbeille = incrementerClePrimaireNumerique('corbeille_fichier_annexe');
            $queryCorbeilleFichier = "INSERT INTO corbeille_fichier_annexe (idFichier_annexe_corbeille, idFichier, lien_fichier_annexe, idCourrierDep, idCourrierArv, idCorbeille)
                                      VALUES (:idFichier_annexe_corbeille, :idFichier, :lien_fichier_annexe, :idCourrierDep, :idCourrierArv, :idCorbeille)";
            $stmtCorbeilleFichier = $objet_connexion->prepare($queryCorbeilleFichier);
            $stmtCorbeilleFichier->execute([
                ':idFichier_annexe_corbeille' => $idFichierAnnexeCorbeille,
                ':idFichier' => $fichier['idFichier'],
                ':lien_fichier_annexe' => $fichier['lien_fichier_annexe'],
                ':idCourrierDep' => $fichier['idCourrierDep'],
                ':idCourrierArv' => $fichier['idCourrierArv'],
                ':idCorbeille' => $idCorbeille
            ]);
        }

        // Supprimer les références dans les autres tables, y compris historique
        $tablesToDelete = [
            'copie_courrier' => 'id_courrierDepart',
            'fichier_annexe' => 'idCourrierDep',
            'notification' => 'idCourrierDepart',
            'fichierreponse' => 'idCourrierDepart',
            'historique' => 'idCourrierDepart'  // Ajout de la table historique
        ];

        foreach ($tablesToDelete as $table => $column) {
            $queryCheck = "SELECT COUNT(*) FROM $table WHERE $column = :idCourrier";
            $stmtCheck = $objet_connexion->prepare($queryCheck);
            $stmtCheck->bindParam(':idCourrier', $idCourrier, PDO::PARAM_INT);
            $stmtCheck->execute();
            $count = $stmtCheck->fetchColumn();

            if ($count > 0) {
                $queryDelete = "DELETE FROM $table WHERE $column = :idCourrier";
                $stmtDelete = $objet_connexion->prepare($queryDelete);
                $stmtDelete->bindParam(':idCourrier', $idCourrier, PDO::PARAM_INT);
                $stmtDelete->execute();
            }
        }

        // Supprimer le courrier de la table principale (courrierdepart ou courrierarrive)
        $queryDeleteCourrier = "DELETE FROM $tableCourrier WHERE idCourrier = :idCourrier";
        $stmtDeleteCourrier = $objet_connexion->prepare($queryDeleteCourrier);
        $stmtDeleteCourrier->bindParam(':idCourrier', $idCourrier, PDO::PARAM_INT);
        $stmtDeleteCourrier->execute();

        // Commit de la transaction
        $objet_connexion->commit();
         
        echo $tableCourrier;
    } catch (Exception $e) {
        // En cas d'erreur, annuler la transaction
        $objet_connexion->rollBack();
        echo "Erreur: " . $e->getMessage();
    }
}



function supprimerCourrierDepart($idCourrier, $matricule) {
    try {
        // Connexion à la base de données
        $objet_connexion = connectToDb('localhost', 'ecourrierdb2', 'Dba', 'EcourrierDba');
        
        // Démarrer une transaction pour garantir que toutes les opérations se font atomiquement
        $objet_connexion->beginTransaction();

        // Préparer la requête pour récupérer les données du courrier départ
        $query = "SELECT * FROM courrierdepart WHERE idCourrier = :idCourrier";
        $stmt = $objet_connexion->prepare($query);
        $stmt->bindParam(':idCourrier', $idCourrier, PDO::PARAM_INT);
        $stmt->execute();
        
        $courrier = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$courrier) {
            throw new Exception("Courrier départ non trouvé");
        }

        // Générer un identifiant pour la corbeille
        $idCorbeille = incrementerClePrimaireNumerique('corbeille');

        // Préparer les données pour l'insertion dans la table corbeille
        $dataCorbeille = [
            ':idCorbeille' => $idCorbeille,
            ':idCourrierDepart' => $idCourrier,
            ':idCourrierArrive' => null,
            ':Type_document' => $courrier['Type_document'],
            ':Etat_interne_externe' => $courrier['Etat_interne_externe'],
            ':etat_courrier' => $courrier['etat_courrier'],
            ':etat_plis_ferme' => $courrier['etat_plis_ferme'],
            ':dateEnregistrement' => $courrier['dateEnregistrement'],
            ':date_mise_circulation' => $courrier['date_mise_circulation'],
            ':Reference' => $courrier['Reference'],
            ':lien_courrier' => $courrier['lien_courrier'],
            ':Matricule_initiateur' => $courrier['Matricule_initiateur'],
            ':idFichierReponse' => isset($courrier['idFichierReponse']) ? $courrier['idFichierReponse'] : null,
            ':etat_expedition' => $courrier['etat_expedition'],
            ':expediteur' => $courrier['expediteur'],
            ':destinataire' => $courrier['destinataire'],
            ':entite_dest' => $courrier['entite_dest'],
            ':pole_destinataire' => null, // Pas de pole pour les courriers départ
            ':categorie' => $courrier['categorie'],
            ':numero_ordre' => $courrier['numero_ordre'],
            ':nombre_fichiers_joins' => $courrier['nombre_fichiers_joins'],
            ':date_derniere_modification' => $courrier['date_derniere_modification'],
            ':signature_gouverneur' => $courrier['signature_gouverneur'],
            ':date_suppression' => date('Y-m-d H:i:s'),
            ':Matricule_agent' => $matricule,
            ':objet_du_courrier' => $courrier['objet_du_courrier']
        ];

        // Insérer le courrier dans la table corbeille
        $queryCorbeille = "INSERT INTO corbeille (idCorbeille, idCourrierDepart, idCourrierArrive, Type_document, Etat_interne_externe, 
                           etat_courrier, etat_plis_ferme, dateEnregistrement, date_mise_circulation, Reference, lien_courrier, 
                           objet_du_courrier, Matricule_initiateur, idFichierReponse, etat_expedition, expediteur, destinataire, 
                           entite_dest, pole_destinataire, categorie, numero_ordre, nombre_fichiers_joins, 
                           date_derniere_modification, signature_gouverneur, date_suppression, Matricule_agent)
                           VALUES (:idCorbeille, :idCourrierDepart, :idCourrierArrive, :Type_document, :Etat_interne_externe, :etat_courrier,
                                   :etat_plis_ferme, :dateEnregistrement, :date_mise_circulation, :Reference, :lien_courrier,
                                   :objet_du_courrier, :Matricule_initiateur, :idFichierReponse, :etat_expedition, :expediteur,
                                   :destinataire, :entite_dest, :pole_destinataire, :categorie, :numero_ordre, 
                                   :nombre_fichiers_joins, :date_derniere_modification, :signature_gouverneur, 
                                   :date_suppression, :Matricule_agent)";
        $stmtCorbeille = $objet_connexion->prepare($queryCorbeille);
        $stmtCorbeille->execute($dataCorbeille);

        // Vérifier s'il existe des fichiers annexes associés à ce courrier
        $queryFichierAnnexe = "SELECT * FROM fichier_annexe WHERE idCourrierDep = :idCourrier";
        $stmtFichier = $objet_connexion->prepare($queryFichierAnnexe);
        $stmtFichier->bindParam(':idCourrier', $idCourrier, PDO::PARAM_INT);
        $stmtFichier->execute();

        while ($fichier = $stmtFichier->fetch(PDO::FETCH_ASSOC)) {
            $idFichierAnnexeCorbeille = incrementerClePrimaireNumerique('corbeille_fichier_annexe');
            $queryCorbeilleFichier = "INSERT INTO corbeille_fichier_annexe (idFichier_annexe_corbeille, idFichier, lien_fichier_annexe, idCourrierDep, idCourrierArv, idCorbeille)
                                      VALUES (:idFichier_annexe_corbeille, :idFichier, :lien_fichier_annexe, :idCourrierDep, :idCourrierArv, :idCorbeille)";
            $stmtCorbeilleFichier = $objet_connexion->prepare($queryCorbeilleFichier);
            $stmtCorbeilleFichier->execute([
                ':idFichier_annexe_corbeille' => $idFichierAnnexeCorbeille,
                ':idFichier' => $fichier['idFichier'],
                ':lien_fichier_annexe' => $fichier['lien_fichier_annexe'],
                ':idCourrierDep' => $fichier['idCourrierDep'],
                ':idCourrierArv' => $fichier['idCourrierArv'],
                ':idCorbeille' => $idCorbeille
            ]);
        }

        // Supprimer les références dans les autres tables
        $tablesToDelete = [
            'copie_courrier' => 'id_courrierDepart',
            'fichier_annexe' => 'idCourrierDep',
            'notification' => 'idCourrierDepart',
            'fichierreponse' => 'idCourrierDepart',
            'historique' => 'idCourrierDepart'  // Ajout de la table historique
        ];

        foreach ($tablesToDelete as $table => $column) {
            $queryCheck = "SELECT COUNT(*) FROM $table WHERE $column = :idCourrier";
            $stmtCheck = $objet_connexion->prepare($queryCheck);
            $stmtCheck->bindParam(':idCourrier', $idCourrier, PDO::PARAM_INT);
            $stmtCheck->execute();
            $count = $stmtCheck->fetchColumn();

            if ($count > 0) {
                $queryDelete = "DELETE FROM $table WHERE $column = :idCourrier";
                $stmtDelete = $objet_connexion->prepare($queryDelete);
                $stmtDelete->bindParam(':idCourrier', $idCourrier, PDO::PARAM_INT);
                $stmtDelete->execute();
            }
        }

        // Supprimer le courrier de la table principale (courrierdepart)
        $queryDeleteCourrier = "DELETE FROM courrierdepart WHERE idCourrier = :idCourrier";
        $stmtDeleteCourrier = $objet_connexion->prepare($queryDeleteCourrier);
        $stmtDeleteCourrier->bindParam(':idCourrier', $idCourrier, PDO::PARAM_INT);
        $stmtDeleteCourrier->execute();

        // Commit de la transaction
        $objet_connexion->commit();

    } catch (Exception $e) {
        // En cas d'erreur, annuler la transaction
        $objet_connexion->rollBack();
        echo "Erreur: " . $e->getMessage();
    }
}



function supprimerCourrierArrive($idCourrier, $matricule) {
    try {
        // Connexion à la base de données
        $objet_connexion = connectToDb('localhost', 'ecourrierdb2', 'Dba', 'EcourrierDba');
        
        // Démarrer une transaction pour garantir que toutes les opérations se font atomiquement
        $objet_connexion->beginTransaction();

        // Préparer la requête pour récupérer les données du courrier arrivé
        $query = "SELECT * FROM courrierarrive WHERE idCourrier = :idCourrier";
        $stmt = $objet_connexion->prepare($query);
        $stmt->bindParam(':idCourrier', $idCourrier, PDO::PARAM_INT);
        $stmt->execute();
        
        $courrier = $stmt->fetch(PDO::FETCH_ASSOC);

        

        if (!$courrier) {
            throw new Exception("Courrier arrivé non trouvé");
        }

        // Ajouter des vérifications pour 'pole_destinataire' et 'objet_du_courrier'
        $objetDuCourrier = isset($courrier['objet_du_courrier']) ? $courrier['objet_du_courrier'] : 'Objet inconnu';

        // Générer un identifiant pour la corbeille
        $idCorbeille = incrementerClePrimaireNumerique('corbeille');

        // Préparer les données pour l'insertion dans la table corbeille
        $dataCorbeille = [
            ':idCorbeille' => $idCorbeille,
            ':idCourrierDepart' => null,
            ':idCourrierArrive' => $idCourrier,
            ':Type_document' => $courrier['Type_document'],
            ':Etat_interne_externe' => $courrier['Etat_interne_externe'],
            ':etat_courrier' => $courrier['etat_courrier'],
            ':etat_plis_ferme' => $courrier['etat_plis_ferme'],
            ':dateEnregistrement' => $courrier['dateEnregistrement'],
            ':date_mise_circulation' => $courrier['date_mise_circulation'],
            ':Reference' => $courrier['Reference'],
            ':lien_courrier' => $courrier['lien_courrier'],
            ':Matricule_initiateur' => $courrier['Matricule_initiateur'],
            ':idFichierReponse' => isset($courrier['idFichierReponse']) ? $courrier['idFichierReponse'] : null,
            ':etat_expedition' => null,
            ':expediteur' => $courrier['expediteur'],
            ':destinataire' => $courrier['destinataire'],
            ':entite_dest' => $courrier['entite_dest'],
            ':pole_destinataire' => $courrier['pole_dest'],  // Utilisation de la valeur ou null
            ':categorie' => $courrier['categorie'],
            ':numero_ordre' => $courrier['numero_ordre'],
            ':nombre_fichiers_joins' => $courrier['nombre_fichiers_joins'],
            ':date_derniere_modification' => $courrier['date_derniere_modification'],
            ':signature_gouverneur' => $courrier['signature_gouverneur'],
            ':date_suppression' => date('Y-m-d H:i:s'),
            ':Matricule_agent' => $matricule,
            ':objet_du_courrier' => $courrier['Objet_du_courrier']  // Utilisation de la valeur ou valeur par défaut
        ];

        // Insérer le courrier dans la table corbeille
        $queryCorbeille = "INSERT INTO corbeille (idCorbeille, idCourrierDepart, idCourrierArrive, Type_document, Etat_interne_externe, 
                           etat_courrier, etat_plis_ferme, dateEnregistrement, date_mise_circulation, Reference, lien_courrier, 
                           objet_du_courrier, Matricule_initiateur, idFichierReponse, etat_expedition, expediteur, destinataire, 
                           entite_dest, pole_destinataire, categorie, numero_ordre, nombre_fichiers_joins, 
                           date_derniere_modification, signature_gouverneur, date_suppression, Matricule_agent)
                           VALUES (:idCorbeille, :idCourrierDepart, :idCourrierArrive, :Type_document, :Etat_interne_externe, :etat_courrier,
                                   :etat_plis_ferme, :dateEnregistrement, :date_mise_circulation, :Reference, :lien_courrier,
                                   :objet_du_courrier, :Matricule_initiateur, :idFichierReponse, :etat_expedition, :expediteur,
                                   :destinataire, :entite_dest, :pole_destinataire, :categorie, :numero_ordre, 
                                   :nombre_fichiers_joins, :date_derniere_modification, :signature_gouverneur, 
                                   :date_suppression, :Matricule_agent)";
        $stmtCorbeille = $objet_connexion->prepare($queryCorbeille);
        $stmtCorbeille->execute($dataCorbeille);

        // Vérifier s'il existe des fichiers annexes associés à ce courrier
        $queryFichierAnnexe = "SELECT * FROM fichier_annexe WHERE idCourrierArv = :idCourrier";
        $stmtFichier = $objet_connexion->prepare($queryFichierAnnexe);
        $stmtFichier->bindParam(':idCourrier', $idCourrier, PDO::PARAM_INT);
        $stmtFichier->execute();

        while ($fichier = $stmtFichier->fetch(PDO::FETCH_ASSOC)) {
            $idFichierAnnexeCorbeille = incrementerClePrimaireNumerique('corbeille_fichier_annexe');
            $queryCorbeilleFichier = "INSERT INTO corbeille_fichier_annexe (idFichier_annexe_corbeille, idFichier, lien_fichier_annexe, idCourrierDep, idCourrierArv, idCorbeille)
                                      VALUES (:idFichier_annexe_corbeille, :idFichier, :lien_fichier_annexe, :idCourrierDep, :idCourrierArv, :idCorbeille)";
            $stmtCorbeilleFichier = $objet_connexion->prepare($queryCorbeilleFichier);
            $stmtCorbeilleFichier->execute([
                ':idFichier_annexe_corbeille' => $idFichierAnnexeCorbeille,
                ':idFichier' => $fichier['idFichier'],
                ':lien_fichier_annexe' => $fichier['lien_fichier_annexe'],
                ':idCourrierDep' => $fichier['idCourrierDep'],
                ':idCourrierArv' => $fichier['idCourrierArv'],
                ':idCorbeille' => $idCorbeille
            ]);
        }

        // Supprimer les références dans les autres tables
        $tablesToDelete = [
            'copie_courrier' => 'id_courrierArrive',
            'fichier_annexe' => 'idCourrierArv',
            'notification' => 'idCourrierArrive',
            'fichierreponse' => 'idCourrierArrive',
        ];

        foreach ($tablesToDelete as $table => $column) {
            $queryCheck = "SELECT COUNT(*) FROM $table WHERE $column = :idCourrier";
            $stmtCheck = $objet_connexion->prepare($queryCheck);
            $stmtCheck->bindParam(':idCourrier', $idCourrier, PDO::PARAM_INT);
            $stmtCheck->execute();
            $count = $stmtCheck->fetchColumn();

            if ($count > 0) {
                $queryDelete = "DELETE FROM $table WHERE $column = :idCourrier";
                $stmtDelete = $objet_connexion->prepare($queryDelete);
                $stmtDelete->bindParam(':idCourrier', $idCourrier, PDO::PARAM_INT);
                $stmtDelete->execute();
            }
        }

        // Supprimer le courrier de la table principale (courrierarrive)
        $queryDeleteCourrier = "DELETE FROM courrierarrive WHERE idCourrier = :idCourrier";
        $stmtDeleteCourrier = $objet_connexion->prepare($queryDeleteCourrier);
        $stmtDeleteCourrier->bindParam(':idCourrier', $idCourrier, PDO::PARAM_INT);
        $stmtDeleteCourrier->execute();

        // Commit de la transaction
        $objet_connexion->commit();

    } catch (Exception $e) {
        // En cas d'erreur, annuler la transaction
        $objet_connexion->rollBack();
        echo "Erreur: " . $e->getMessage();
    }
}


function supprimerCourrierArriveInterne($numero_ordre, $matricule) {
    try {
        // Connexion à la base de données
        $objet_connexion = connectToDb('localhost', 'ecourrierdb2', 'Dba', 'EcourrierDba');
        
        // Démarrer une transaction pour garantir que toutes les opérations se font atomiquement
        $objet_connexion->beginTransaction();

        // Préparer la requête pour récupérer l'idCourrier à partir du numero_ordre
        $query = "SELECT idCourrier, * FROM courrierarrive WHERE numero_ordre = :numero_ordre";
        $stmt = $objet_connexion->prepare($query);
        $stmt->bindParam(':numero_ordre', $numero_ordre, PDO::PARAM_INT);
        $stmt->execute();
        
        $courrier = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$courrier) {
            throw new Exception("Courrier arrivé non trouvé pour le numéro d'ordre: $numero_ordre");
        }

        // Ajouter des vérifications pour 'pole_destinataire' et 'objet_du_courrier'
        $objetDuCourrier = isset($courrier['objet_du_courrier']) ? $courrier['objet_du_courrier'] : 'Objet inconnu';

        // Générer un identifiant pour la corbeille
        $idCorbeille = incrementerClePrimaireNumerique('corbeille');

        // Préparer les données pour l'insertion dans la table corbeille
        $dataCorbeille = [
            ':idCorbeille' => $idCorbeille,
            ':idCourrierDepart' => null,
            ':idCourrierArrive' => $courrier['idCourrier'],  // Utilisation de l'idCourrier récupéré
            ':Type_document' => $courrier['Type_document'],
            ':Etat_interne_externe' => $courrier['Etat_interne_externe'],
            ':etat_courrier' => $courrier['etat_courrier'],
            ':etat_plis_ferme' => $courrier['etat_plis_ferme'],
            ':dateEnregistrement' => $courrier['dateEnregistrement'],
            ':date_mise_circulation' => $courrier['date_mise_circulation'],
            ':Reference' => $courrier['Reference'],
            ':lien_courrier' => $courrier['lien_courrier'],
            ':Matricule_initiateur' => $courrier['Matricule_initiateur'],
            ':idFichierReponse' => isset($courrier['idFichierReponse']) ? $courrier['idFichierReponse'] : null,
            ':etat_expedition' => null,
            ':expediteur' => $courrier['expediteur'],
            ':destinataire' => $courrier['destinataire'],
            ':entite_dest' => $courrier['entite_dest'],
            ':pole_destinataire' => $courrier['pole_dest'],  // Utilisation de la valeur ou null
            ':categorie' => $courrier['categorie'],
            ':numero_ordre' => $courrier['numero_ordre'],
            ':nombre_fichiers_joins' => $courrier['nombre_fichiers_joins'],
            ':date_derniere_modification' => $courrier['date_derniere_modification'],
            ':signature_gouverneur' => $courrier['signature_gouverneur'],
            ':date_suppression' => date('Y-m-d H:i:s'),
            ':Matricule_agent' => $matricule,
            ':objet_du_courrier' => $courrier['Objet_du_courrier']  // Utilisation de la valeur ou valeur par défaut
        ];

        // Insérer le courrier dans la table corbeille
        $queryCorbeille = "INSERT INTO corbeille (idCorbeille, idCourrierDepart, idCourrierArrive, Type_document, Etat_interne_externe, 
                           etat_courrier, etat_plis_ferme, dateEnregistrement, date_mise_circulation, Reference, lien_courrier, 
                           objet_du_courrier, Matricule_initiateur, idFichierReponse, etat_expedition, expediteur, destinataire, 
                           entite_dest, pole_destinataire, categorie, numero_ordre, nombre_fichiers_joins, 
                           date_derniere_modification, signature_gouverneur, date_suppression, Matricule_agent)
                           VALUES (:idCorbeille, :idCourrierDepart, :idCourrierArrive, :Type_document, :Etat_interne_externe, :etat_courrier,
                                   :etat_plis_ferme, :dateEnregistrement, :date_mise_circulation, :Reference, :lien_courrier,
                                   :objet_du_courrier, :Matricule_initiateur, :idFichierReponse, :etat_expedition, :expediteur,
                                   :destinataire, :entite_dest, :pole_destinataire, :categorie, :numero_ordre, 
                                   :nombre_fichiers_joins, :date_derniere_modification, :signature_gouverneur, 
                                   :date_suppression, :Matricule_agent)";
        $stmtCorbeille = $objet_connexion->prepare($queryCorbeille);
        $stmtCorbeille->execute($dataCorbeille);

        // Vérifier s'il existe des fichiers annexes associés à ce courrier
        $queryFichierAnnexe = "SELECT * FROM fichier_annexe WHERE idCourrierArv = :idCourrier";
        $stmtFichier = $objet_connexion->prepare($queryFichierAnnexe);
        $stmtFichier->bindParam(':idCourrier', $courrier['idCourrier'], PDO::PARAM_INT);
        $stmtFichier->execute();

        while ($fichier = $stmtFichier->fetch(PDO::FETCH_ASSOC)) {
            $idFichierAnnexeCorbeille = incrementerClePrimaireNumerique('corbeille_fichier_annexe');
            $queryCorbeilleFichier = "INSERT INTO corbeille_fichier_annexe (idFichier_annexe_corbeille, idFichier, lien_fichier_annexe, idCourrierDep, idCourrierArv, idCorbeille)
                                      VALUES (:idFichier_annexe_corbeille, :idFichier, :lien_fichier_annexe, :idCourrierDep, :idCourrierArv, :idCorbeille)";
            $stmtCorbeilleFichier = $objet_connexion->prepare($queryCorbeilleFichier);
            $stmtCorbeilleFichier->execute([
                ':idFichier_annexe_corbeille' => $idFichierAnnexeCorbeille,
                ':idFichier' => $fichier['idFichier'],
                ':lien_fichier_annexe' => $fichier['lien_fichier_annexe'],
                ':idCourrierDep' => $fichier['idCourrierDep'],
                ':idCourrierArv' => $fichier['idCourrierArv'],
                ':idCorbeille' => $idCorbeille
            ]);
        }

        // Supprimer les références dans les autres tables
        $tablesToDelete = [
            'copie_courrier' => 'id_courrierArrive',
            'fichier_annexe' => 'idCourrierArv',
            'notification' => 'idCourrierArrive',
            'fichierreponse' => 'idCourrierArrive',
        ];

        foreach ($tablesToDelete as $table => $column) {
            $queryCheck = "SELECT COUNT(*) FROM $table WHERE $column = :idCourrier";
            $stmtCheck = $objet_connexion->prepare($queryCheck);
            $stmtCheck->bindParam(':idCourrier', $courrier['idCourrier'], PDO::PARAM_INT);
            $stmtCheck->execute();
            $count = $stmtCheck->fetchColumn();

            if ($count > 0) {
                $queryDelete = "DELETE FROM $table WHERE $column = :idCourrier";
                $stmtDelete = $objet_connexion->prepare($queryDelete);
                $stmtDelete->bindParam(':idCourrier', $courrier['idCourrier'], PDO::PARAM_INT);
                $stmtDelete->execute();
            }
        }

        // Supprimer le courrier de la table principale (courrierarrive)
        $queryDeleteCourrier = "DELETE FROM courrierarrive WHERE idCourrier = :idCourrier";
        $stmtDeleteCourrier = $objet_connexion->prepare($queryDeleteCourrier);
        $stmtDeleteCourrier->bindParam(':idCourrier', $courrier['idCourrier'], PDO::PARAM_INT);
        $stmtDeleteCourrier->execute();

        // Commit de la transaction
        $objet_connexion->commit();

    } catch (Exception $e) {
        // En cas d'erreur, annuler la transaction
        $objet_connexion->rollBack();
        echo "Erreur: " . $e->getMessage();
    }
}



?>















