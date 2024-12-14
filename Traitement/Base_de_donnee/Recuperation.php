<?php
require_once('../../Traitement/Base_de_donnee/connexion.php');

//Cette foncion récupère les données et les met dans un tableau associatif
function recupererTouteLaTableDataV1($table){
    
    try {
        //création de l'objet de connexion à la base de donnée 
        $objet_connexion = connectToDb('localhost','ecourrierdb2','Dba','EcourrierDba');
        //Gestion des erreurs hormis la connexion
        $objet_connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //préparation de la requête
        $requete = "select * from $table";
        $resultats = $objet_connexion->query($requete);
        $tableau_associatif_resultats = $resultats->fetchAll(PDO::FETCH_ASSOC);
        foreach ($tableau_associatif_resultats as $ligne) {
            echo $ligne['nom_utilisateur'].' - '.$ligne['prenom_utilisateur'].
            ' - '.$ligne['login'].' - '.$ligne['code_profile'].'<br>';
        }
        
    } catch (PDOException $e) {
        die('erreur survenue'.$e->getMessage());
    }
}


//Cette fonction récupère le contenu d'une table et le met dans un tableau d'objets
function recupererTouteLaTableDataV2($table){
    
    try {
        //création de l'objet de connexion à la base de donnée 
        $objet_connexion = connectToDb('localhost','ecourrierdb2','Dba','EcourrierDba');
        //Gestion des erreurs hormis la connexion
        $objet_connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //préparation de la requête
        $requete = "select * from $table";
        $resultats = $objet_connexion->query($requete);
        $tableau_associatif_resultats = $resultats->fetchAll(PDO::FETCH_OBJ);
        foreach ($tableau_associatif_resultats as $ligne) {
           echo  $ligne->Nom_utilisateur.' - '.$ligne->prenom_utilisateur.' - '.$ligne->email.'<br>';
        }

        
        
    } catch (PDOException $e) {
        die('erreur survenue'.$e->getMessage());
    }
}

// cette fonction récupère uniquement quelques colonnes d'une table

function recupererTouteLaTableDataV3($table,$colonne1,$colonne2){

try {
    $objet_connexion = connectToDb('localhost','ecourrierdb','Dba','EcourrierDba');
$requete = " select * from $table";
$resultats = $objet_connexion->query($requete);
$tableau_associatif_resultats = $resultats->fetchAll(PDO::FETCH_OBJ);
foreach ($tableau_associatif_resultats as $ligne) {
          echo $colonne1.' - '.$colonne2 .'<br>';
   echo  $ligne->$colonne1.' - '.$ligne->$colonne2.'<br>';
}
} catch (PDOException $e) {
    die(" erreur survenue".$e->getMessage());
}


}





//Cette fonction permet de récupérer une ligne spécifique dans une table. La fonction reourne u tableau contenant l'objet rechercher 

function recupererLigneSpecifique($table,$colonne,$valeur){

    try {
        $objet_connexion = connectToDb('localhost','ecourrierdb2','Dba','EcourrierDba');
    $requete = " select * from $table where $colonne = ?";
    $resultats = $objet_connexion->prepare($requete);

    $resultats->bindValue(1,$valeur);

    $resultats->execute();
    $tableau_associatif_resultats = $resultats->fetchAll(PDO::FETCH_OBJ);
    //tester la longueur du tableau 
    $test_tableau = (count($tableau_associatif_resultats)>0) ? 1 : 0 ;

    if ($test_tableau) {
        return $tableau_associatif_resultats;
    }else {
    //   echo 'objet non trouvé dans la table '.$table;
        return;
    }
    
    } catch (PDOException $e) {
        die(" erreur survenue".$e->getMessage());
    }
    
    }
    

//Cette fonction récupère les données et les met dans un tableau d'incice numérique

    function recupererTouteLesClePrimaires($table){
      $liste_cle_primaire = [];
        try {
            //création de l'objet de connexion à la base de donnée 
            $objet_connexion = connectToDb('localhost','ecourrierdb2','Dba','EcourrierDba');
            //Gestion des erreurs hormis la connexion
            $objet_connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //préparation de la requête
            $requete = "select * from $table";
            $resultats = $objet_connexion->query($requete);
            $tableau_associatif_resultats = $resultats->fetchAll(PDO::FETCH_NUM);
            
            foreach($tableau_associatif_resultats as $ligne){
                // echo $ligne[0].'<br>';
                $liste_cle_primaire []=$ligne[0];

            }
            return $liste_cle_primaire;
            
        } catch (PDOException $e) {
            die('erreur survenue'.$e->getMessage());
        }
    }

    
    function incrementerClePrimaireNumerique($table){
        $liste_des_cles = recupererTouteLesClePrimaires($table);
        $max=0;
    for ($i=0; $i <count($liste_des_cles) ; $i++) { 
        if ($liste_des_cles[$i]>$max) {
        $max=$liste_des_cles[$i];
    }
    }

    // echo '<br>'.$max;
    return $max+1;

    }


    //Requete de Récupération liées à des jointures 

    //Requetes de recupération du contenu d'une table en ne fournissant que la requête
    function recupererContenuParRequeteDansTableauNumeique($requete){
        try {
            //création de l'objet de connexion à la base de donnée 
            $objet_connexion = connectToDb('localhost','ecourrierdb2','Dba','EcourrierDba');
            //Gestion des erreurs hormis la connexion
            $objet_connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //préparation de la requête
            $resultats = $objet_connexion->query($requete);
            $tableau_associatif_resultats = $resultats->fetchAll(PDO::FETCH_NUM);
            // foreach ($tableau_associatif_resultats as $ligne) {
            //    echo  $ligne->Nom_utilisateur.' - '.$ligne->prenom_utilisateur.' - '.$ligne->email.'<br>';
            // }
            return $tableau_associatif_resultats;
            
            
        } catch (PDOException $e) {
            die('erreur survenue'.$e->getMessage());
        }

    }

    // Requetes de recupération du contenu d'une table en  fournissant que la requête et un paamètre

    
    function recupererNomEntiteParIdUtilisateur($requete,$idUtilisateur){
        
        try {
            $objet_connexion = connectToDb('localhost','ecourrierdb2','Dba','EcourrierDba');
       
        $resultats = $objet_connexion->prepare($requete);
    
        $resultats->bindValue(1,$idUtilisateur);
    
        $resultats->execute();
        $tableau_associatif_resultats = $resultats->fetchAll(PDO::FETCH_OBJ);
        //tester la longueur du tableau 
        $test_tableau = (count($tableau_associatif_resultats)>0) ? 1 : 0 ;
    
        if ($test_tableau) {
            foreach ($tableau_associatif_resultats as $entite) {
                return $entite->nom_entite;
            }
            
        }else {
          echo "entité non liée à l'utilisateur non trouvée ";
            return;
        }
        
        } catch (PDOException $e) {
            die(" erreur survenue".$e->getMessage());
        }
        

    }

   
  

    "SELECT cd.numero_ordre,cd.Etat_interne_externe, cd.lien_courrier,cd.destinataire,cd.dateEnregistrement, cd.etat_courrier
    FROM courrierdepart cd
    JOIN utilisateur u ON cd.Matricule_initiateur = u.Matricule
    JOIN entite_banque eb ON u.id_entite = eb.id_entite
    WHERE eb.nom_entite = 'DSI';";

function getCourriers($nom_entite, $searchKeyword = '', $startDate = '', $endDate = '', $sortType = '', $sortOrder = '', $origine = '', $priority = '', $typeCourrier = '') {
    // Connexion à la base de données (tu peux ajuster cela selon ton contexte)
    $objet_connexion = connectToDb('localhost','ecourrierdb2','Dba','EcourrierDba');

    // Requête de base pour récupérer courriers départs et arrivés
    $query = "SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               dateEnregistrement, 
               etat_courrier,
               'courrier départ' AS type_courrier
        FROM courrierdepart
        JOIN utilisateur u ON courrierdepart.Matricule_initiateur = u.Matricule
        JOIN entite_banque eb ON u.id_entite = eb.id_entite
        WHERE eb.nom_entite = :nom_entite 
       

        UNION ALL

        -- Requête pour les courriers arrivés
        SELECT idCourrier, numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        JOIN utilisateur u ON courrierarrive.Matricule_initiateur = u.Matricule
        JOIN entite_banque eb ON u.id_entite = eb.id_entite
        WHERE courrierarrive.destinataire = :nom_entite
       
    ";
   

    // Ajouter des conditions de filtre (si présentes)

    $params = [':nom_entite' => $nom_entite];

    // Recherche par mot-clé
    if ($searchKeyword) {
        // $query .= " AND (objet_du_courrier LIKE :searchKeyword OR destinataire LIKE :searchKeyword)";
        $query = "SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               dateEnregistrement, 
               etat_courrier,
               'courrier départ' AS type_courrier
        FROM courrierdepart
        JOIN utilisateur u ON courrierdepart.Matricule_initiateur = u.Matricule
        JOIN entite_banque eb ON u.id_entite = eb.id_entite
        WHERE eb.nom_entite = :nom_entite AND (objet_du_courrier LIKE :searchKeyword OR destinataire LIKE :searchKeyword)

        UNION ALL

        -- Requête pour les courriers arrivés
        SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        JOIN utilisateur u ON courrierarrive.Matricule_initiateur = u.Matricule
        JOIN entite_banque eb ON u.id_entite = eb.id_entite
        WHERE courrierarrive.destinataire = :nom_entite AND (objet_du_courrier LIKE :searchKeyword OR destinataire LIKE :searchKeyword)
    ";
   
        $params[':searchKeyword'] = "%$searchKeyword%";
    }

    // Filtrage par période (date de début et de fin)
    if ($startDate && $endDate) {
        $query = "SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               dateEnregistrement, 
               etat_courrier,
               'courrier départ' AS type_courrier
        FROM courrierdepart
        JOIN utilisateur u ON courrierdepart.Matricule_initiateur = u.Matricule
        JOIN entite_banque eb ON u.id_entite = eb.id_entite
        WHERE eb.nom_entite = :nom_entite and courrierdepart.dateEnregistrement  BETWEEN :startDate AND :endDate

        UNION ALL

        -- Requête pour les courriers arrivés
        SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        JOIN utilisateur u ON courrierarrive.Matricule_initiateur = u.Matricule
        JOIN entite_banque eb ON u.id_entite = eb.id_entite
        WHERE courrierarrive.destinataire = :nom_entite and courrierarrive.dateEnregistrement  BETWEEN :startDate AND :endDate
     ";
        $params[':startDate'] = $startDate;
        $params[':endDate'] = $endDate;
    }

    // Filtrage par origine (externe ou interne)
    if ($origine) {

        
        $query =" SELECT 
        idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               dateEnregistrement, 
               etat_courrier,
               'courrier départ' AS type_courrier
        FROM courrierdepart
        JOIN utilisateur u ON courrierdepart.Matricule_initiateur = u.Matricule
        JOIN entite_banque eb ON u.id_entite = eb.id_entite
        WHERE eb.nom_entite = :nom_entite  and courrierdepart.Etat_interne_externe= :origine

        UNION ALL

        -- Requête pour les courriers arrivés
        SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        JOIN utilisateur u ON courrierarrive.Matricule_initiateur = u.Matricule
        JOIN entite_banque eb ON u.id_entite = eb.id_entite
        WHERE courrierarrive.destinataire = :nom_entite and courrierarrive.Etat_interne_externe = :origine
 
            ";
        $params[':origine'] = $origine;
    }

    // Filtrage par priorité
    if ($priority) {
        
        $query = " SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               dateEnregistrement, 
               etat_courrier,
               'courrier départ' AS type_courrier
        FROM courrierdepart
        JOIN utilisateur u ON courrierdepart.Matricule_initiateur = u.Matricule
        JOIN entite_banque eb ON u.id_entite = eb.id_entite
        WHERE eb.nom_entite = :nom_entite  and courrierdepart.categorie= :priority

        UNION ALL

        -- Requête pour les courriers arrivés
        SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        JOIN utilisateur u ON courrierarrive.Matricule_initiateur = u.Matricule
        JOIN entite_banque eb ON u.id_entite = eb.id_entite
        WHERE courrierarrive.destinataire = :nom_entite and courrierarrive.categorie = :priority
    ";
        $params[':priority'] = $priority;
    }

    // Filtrage par type de courrier (arrivé ou départ)
    if ($typeCourrier) {
        if ($typeCourrier === 'courrier arrive') {
            $query = " SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        JOIN utilisateur u ON courrierarrive.Matricule_initiateur = u.Matricule
        JOIN entite_banque eb ON u.id_entite = eb.id_entite
        WHERE courrierarrive.destinataire = :nom_entite
 ";
        } elseif ($typeCourrier === 'courrier départ') {
            $query = " SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               dateEnregistrement, 
               etat_courrier,
               'courrier départ' AS type_courrier
        FROM courrierdepart
        JOIN utilisateur u ON courrierdepart.Matricule_initiateur = u.Matricule
        JOIN entite_banque eb ON u.id_entite = eb.id_entite
        WHERE eb.nom_entite = :nom_entite ";
        }
    }

    // Ajouter les conditions de tri (par défaut, on trie par date)
    if ($sortType) {
        $query .= " ORDER BY ";
        if ($sortType === 'date') {
            $query .= "dateEnregistrement";
        } elseif ($sortType === 'objet') {
            $query .= "objet_du_courrier";
        } elseif ($sortType === 'Numéro') {
            $query .= "numero_ordre";
        }
        
        // Définir l'ordre (croissant ou décroissant)
        $query .= ($sortOrder === 'decroissant') ? " DESC" : " ASC";
    } else {
        $query .= " ORDER BY dateEnregistrement DESC";
    }

    // Préparation de la requête SQL
    $stmt = $objet_connexion->prepare($query);

    // Liaison des paramètres
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    // Exécution de la requête
    $stmt->execute();

    // Récupération des résultats
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return $results;
}


function recupererContenuParRequeteDansTableObjets($nom_entite){
    try {
        //création de l'objet de connexion à la base de donnée 
        $objet_connexion = connectToDb('localhost','ecourrierdb2','Dba','EcourrierDba');
        //Gestion des erreurs hormis la connexion
        $objet_connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //préparation de la requête
        $requete = "";
        $resultats = $objet_connexion->query($requete);
        $tableau_associatif_resultats = $resultats->fetchAll(PDO::FETCH_ASSOC);
        // foreach ($tableau_associatif_resultats as $ligne) {
        //    echo  $ligne->Nom_utilisateur.' - '.$ligne->prenom_utilisateur.' - '.$ligne->email.'<br>';
        // }
        return $tableau_associatif_resultats;
        
        
    } catch (PDOException $e) {
        die('erreur survenue'.$e->getMessage());
    }

}




function getResultsFromQuery($nom_entite, $query) {
    // Connexion à la base de données
    $objet_connexion = connectToDb('localhost', 'ecourrierdb2', 'Dba', 'EcourrierDba');

    // Préparer la requête SQL
    $stmt = $objet_connexion->prepare($query);

    // Lier le paramètre :nom_entite à la valeur du nom de l'entité
    $stmt->bindValue(':nom_entite', $nom_entite);

    // Exécuter la requête
    $stmt->execute();

    // Récupérer les résultats sous forme de tableau associatif
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retourner les résultats
    return $results;
}




function getCourriersBO($nom_entite, $searchKeyword = '', $startDate = '', $endDate = '', $sortType = '', $sortOrder = '', $origine = '', $priority = '', $typeCourrier = '') {
    // Connexion à la base de données (tu peux ajuster cela selon ton contexte)
    $objet_connexion = connectToDb('localhost','ecourrierdb2','Dba','EcourrierDba');

    // Requête de base pour récupérer courriers départs et arrivés
    $query = " SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        JOIN utilisateur u ON courrierarrive.Matricule_initiateur = u.Matricule
        JOIN entite_banque eb ON u.id_entite = eb.id_entite
        WHERE eb.nom_entite = :nom_entite
       
    ";
   

    // Ajouter des conditions de filtre (si présentes)

    $params = [':nom_entite' => $nom_entite];

    // Recherche par mot-clé
    if ($searchKeyword) {
        $query .= " AND (objet_du_courrier LIKE :searchKeyword OR destinataire LIKE :searchKeyword OR numero_ordre LIKE :searchKeyword )";
        $params[':searchKeyword'] = "%$searchKeyword%";
    }

    // Filtrage par période (date de début et de fin)
    if ($startDate && $endDate) {
        $query = "SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        JOIN utilisateur u ON courrierarrive.Matricule_initiateur = u.Matricule
        JOIN entite_banque eb ON u.id_entite = eb.id_entite
        WHERE eb.nom_entite = :nom_entite and courrierarrive.dateEnregistrement  BETWEEN :startDate AND :endDate
     ";
        $params[':startDate'] = $startDate;
        $params[':endDate'] = $endDate;
    }

    // Filtrage par origine (externe ou interne)
    if ($origine) {

        
        $query =" SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        JOIN utilisateur u ON courrierarrive.Matricule_initiateur = u.Matricule
        JOIN entite_banque eb ON u.id_entite = eb.id_entite
        WHERE eb.nom_entite = :nom_entite and courrierarrive.Etat_interne_externe = :origine
 
            ";
        $params[':origine'] = $origine;
    }

    // Filtrage par priorité
    if ($priority) {
        
        $query = " SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        JOIN utilisateur u ON courrierarrive.Matricule_initiateur = u.Matricule
        JOIN entite_banque eb ON u.id_entite = eb.id_entite
        WHERE eb.nom_entite = :nom_entite and courrierarrive.categorie = :priority
    ";
        $params[':priority'] = $priority;
    }

    // Filtrage par type de courrier (arrivé ou départ)
    if ($typeCourrier) {
        if ($typeCourrier === 'courrier arrive') {
            $query = " SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        JOIN utilisateur u ON courrierarrive.Matricule_initiateur = u.Matricule
        JOIN entite_banque eb ON u.id_entite = eb.id_entite
        WHERE courrierarrive.destinataire = :nom_entite
 ";
        } elseif ($typeCourrier === 'courrier départ') {
            $query = " SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               dateEnregistrement, 
               etat_courrier,
               'courrier départ' AS type_courrier
        FROM courrierdepart
        JOIN utilisateur u ON courrierdepart.Matricule_initiateur = u.Matricule
        JOIN entite_banque eb ON u.id_entite = eb.id_entite
        WHERE eb.nom_entite = :nom_entite ";
        }
    }

    // Ajouter les conditions de tri (par défaut, on trie par date)
    if ($sortType) {
        $query .= " ORDER BY ";
        if ($sortType === 'date') {
            $query .= "dateEnregistrement";
        } elseif ($sortType === 'objet') {
            $query .= "objet_du_courrier";
        } elseif ($sortType === 'Numéro') {
            $query .= "numero_ordre";
        }
        
        // Définir l'ordre (croissant ou décroissant)
        $query .= ($sortOrder === 'decroissant') ? " DESC" : " ASC";
    } else {
        $query .= " ORDER BY dateEnregistrement DESC";
    }

    // Préparation de la requête SQL
    $stmt = $objet_connexion->prepare($query);

    // Liaison des paramètres
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    // Exécution de la requête
    $stmt->execute();

    // Récupération des résultats
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return $results;
}




function getInfosForCourrier($requete,$idCourrier){
       // Connexion à la base de données
       $objet_connexion = connectToDb('localhost', 'ecourrierdb2', 'Dba', 'EcourrierDba');

       // Préparer la requête SQL
       $stmt = $objet_connexion->prepare($requete);
   
       // Lier les paramètres :idCourrier et :type_courrier et à la valeur du nom de l'entité
       $stmt->bindValue(':idCourrier', $idCourrier);
       
   
       // Exécuter la requête
       $stmt->execute();
   
       // Récupérer les résultats sous forme de tableau associatif
       $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
       // Retourner les résultats
       return $results; 

}



function verifierCourrierDansModification($idCourrier) {
    // Requête pour vérifier si l'idCourrier existe dans main_modification_courrier
    $objet_connexion = connectToDb('localhost','ecourrierdb2','Dba','EcourrierDba');
    $sql = "SELECT COUNT(*) FROM main_modification_courrier WHERE idCourrier = :idCourrier";
    
    // Préparer la requête
    $stmt = $objet_connexion->prepare($sql);
    
    // Lier le paramètre
    $stmt->bindParam(':idCourrier', $idCourrier, PDO::PARAM_INT);
    
    // Exécution de la requête
    $stmt->execute();
    
    // Récupérer le résultat
    $result = $stmt->fetchColumn();
    
    // Si le résultat est supérieur à 0, l'idCourrier existe
    return $result > 0;
}



function recupererHistoriqueParIdCourrierEtType($idCourrier, $typeCourrier) {
    try {
        $objet_connexion = connectToDb('localhost','ecourrierdb2','Dba','EcourrierDba');
        // Vérifier le type de courrier (départ ou arrivée)
        if ($typeCourrier === 'courrier départ') {
            // Requête SQL pour récupérer les historiques liés à un courrier de départ
            $sql = "SELECT  h.action_effectuee, h.date_operation, h.entite_resoinsable
                FROM historique h
                WHERE h.idCourrierdepart = :idCourrier
                group by h.action_effectuee, h.date_operation, h.entite_resoinsable
                ORDER BY h.date_operation DESC
            ";
        } elseif ($typeCourrier === 'courrier arrivé') {
            // Requête SQL pour récupérer les historiques liés à un courrier d'arrivée
            $sql = "SELECT h.action_effectuee, h.date_operation, h.entite_resoinsable
                FROM historique h
                WHERE h.idCourrierArrive = :idCourrier
                group by h.action_effectuee, h.date_operation, h.entite_resoinsable
                ORDER BY h.date_operation DESC
            ";
        } else {
            throw new Exception("Type de courrier invalide. Utilisez 'depart' ou 'arrive'.");
        }

        // Préparer la requête
        $stmt = $objet_connexion->prepare($sql);

        // Lier le paramètre :idCourrier
        $stmt->bindParam(':idCourrier', $idCourrier, PDO::PARAM_INT);
        
        // Exécuter la requête
        $stmt->execute();

        // Récupérer les résultats
        $historiques = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Vérifier si des résultats ont été trouvés
        if ($historiques) {
            return $historiques; // Retourner le tableau des historiques
        } else {
            return []; // Si aucun historique n'est trouvé, retourner un tableau vide
        }
    } catch (PDOException $e) {
        // Gestion des erreurs de la base de données
        echo "Erreur de récupération des historiques: " . $e->getMessage();
        return [];
    }
}














?>







