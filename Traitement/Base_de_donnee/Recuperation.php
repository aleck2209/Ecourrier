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
                
                if (isset($entite->nom_entite)) {
                    return $entite->nom_entite;
                }elseif (isset($entite->nom_pole)) {
                    return $entite->nom_pole;
                }
                
            }
            
        }else {
          echo "entité ou pole  lié à l'utilisateur non trouvé ";
            return;
        }
        
        } catch (PDOException $e) {
            die(" erreur survenue".$e->getMessage());
        }
        

    }

    // Fonction pour récupérer soit l'id_pole soit l'id_entité pour un utilisateur donné

    function recupererIdEntiteOuIdPolePourUnUtilisateur($requete,$idUtilisateur){
        
        try {
            $objet_connexion = connectToDb('localhost','ecourrierdb2','Dba','EcourrierDba');
       
        $resultats = $objet_connexion->prepare($requete);
    
        $resultats->bindValue(1,$idUtilisateur);
    
        $resultats->execute();
        $tableau_associatif_resultats = $resultats->fetchAll(PDO::FETCH_ASSOC);
        //tester la longueur du tableau 
        $test_tableau = (count($tableau_associatif_resultats)>0) ? 0 : 1 ;
        if (isset($tableau_associatif_resultats[0])) {
            return $tableau_associatif_resultats[0];
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

 //Ici nous allons initialiser les variables 
 $sql1 = " select nom_entite from entite_banque where nom_entite = :nom_entite";
 $requete_entite = getResultsFromQuery($nom_entite,$sql1);

 $sql2 = "select nom_pole from pole where nom_pole = :nom_pole";
 $requete_pole = getPoleNameResultsFromQuery($nom_entite,$sql2);

 // Récupération des courriers par entité 

 if (!empty($requete_entite)) {
    

    // Requête de base pour récupérer courriers départs et arrivés
    $query = "SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier,
               expediteur,
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
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        WHERE courrierarrive.destinataire = :nom_entite

    UNION ALL

    SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire,
               expediteur, 
               dateEnregistrement, 
               etat_courrier,
               'copie courrier' AS type_courrier
        FROM courrierdepart
        JOIN copie_courrier ON courrierdepart.idCourrier = copie_courrier.id_courrierDepart
        
        WHERE copie_courrier.nom_destinataire = :nom_entite
        
        UNION ALL
        
        SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire,
               expediteur, 
               dateEnregistrement, 
               etat_courrier,
               'copie courrier' AS type_courrier
        FROM courrierarrive
        JOIN copie_courrier ON courrierarrive.idCourrier = copie_courrier.id_courrierArrive
        
        WHERE copie_courrier.nom_destinataire = :nom_entite

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
               expediteur,
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
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        WHERE courrierarrive.destinataire = :nom_entite AND (objet_du_courrier LIKE :searchKeyword OR destinataire LIKE :searchKeyword)
    
        UNION ALL

    SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire,
               expediteur, 
               dateEnregistrement, 
               etat_courrier,
               'copie courrier' AS type_courrier
        FROM courrierdepart
        JOIN copie_courrier ON courrierdepart.idCourrier = copie_courrier.id_courrierDepart
        
        WHERE copie_courrier.nom_destinataire = :nom_entite AND (objet_du_courrier LIKE :searchKeyword OR destinataire LIKE :searchKeyword)
        
        UNION ALL
        
        SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire,
               expediteur, 
               dateEnregistrement, 
               etat_courrier,
               'copie courrier' AS type_courrier
        FROM courrierarrive
        JOIN copie_courrier ON courrierarrive.idCourrier = copie_courrier.id_courrierArrive
        
        WHERE copie_courrier.nom_destinataire = :nom_entite AND (objet_du_courrier LIKE :searchKeyword OR destinataire LIKE :searchKeyword)

    
    
    
    
    
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
               expediteur,
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
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        WHERE courrierarrive.destinataire = :nom_entite and courrierarrive.dateEnregistrement  BETWEEN :startDate AND :endDate
        
        UNION ALL

    SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire,
               expediteur, 
               dateEnregistrement, 
               etat_courrier,
               'copie courrier' AS type_courrier
        FROM courrierdepart
        JOIN copie_courrier ON courrierdepart.idCourrier = copie_courrier.id_courrierDepart
        
        WHERE copie_courrier.nom_destinataire = :nom_entite AND courrierdepart.dateEnregistrement  BETWEEN :startDate AND :endDate
        
        UNION ALL
        
        SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire,
               expediteur, 
               dateEnregistrement, 
               etat_courrier,
               'copie courrier' AS type_courrier
        FROM courrierarrive
        JOIN copie_courrier ON courrierarrive.idCourrier = copie_courrier.id_courrierArrive
        
        WHERE copie_courrier.nom_destinataire = :nom_entite AND courrierarrive.dateEnregistrement  BETWEEN :startDate AND :endDate

     
     
     
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
               expediteur, 
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
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        WHERE courrierarrive.destinataire = :nom_entite and courrierarrive.Etat_interne_externe = :origine
        
        UNION ALL

        SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire,
               expediteur, 
               dateEnregistrement, 
               etat_courrier,
               'copie courrier' AS type_courrier
        FROM courrierdepart
        JOIN copie_courrier ON courrierdepart.idCourrier = copie_courrier.id_courrierDepart
        
        WHERE copie_courrier.nom_destinataire = :nom_entite and courrierdepart.Etat_interne_externe = :origine
        
        UNION ALL
        
        SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire,
               expediteur, 
               dateEnregistrement, 
               etat_courrier,
               'copie courrier' AS type_courrier
        FROM courrierarrive
        JOIN copie_courrier ON courrierarrive.idCourrier = copie_courrier.id_courrierArrive
        
        WHERE copie_courrier.nom_destinataire = :nom_entite and courrierarrive.Etat_interne_externe = :origine
     



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
               expediteur,
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
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        WHERE courrierarrive.destinataire = :nom_entite and courrierarrive.categorie = :priority

        UNION ALL


        SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire,
               expediteur, 
               dateEnregistrement, 
               etat_courrier,
               'copie courrier' AS type_courrier
        FROM courrierdepart
        JOIN copie_courrier ON courrierdepart.idCourrier = copie_courrier.id_courrierDepart
        
        WHERE copie_courrier.nom_destinataire = :nom_entite and courrierdepart.categorie = :priority
        
        UNION ALL
        
        SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire,
               expediteur, 
               dateEnregistrement, 
               etat_courrier,
               'copie courrier' AS type_courrier
        FROM courrierarrive
        JOIN copie_courrier ON courrierarrive.idCourrier = copie_courrier.id_courrierArrive
        
        WHERE copie_courrier.nom_destinataire = :nom_entite AND  courrierarrive.categorie = :priority

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
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        WHERE courrierarrive.destinataire = :nom_entite
 ";
        } elseif ($typeCourrier === 'courrier départ') {
            $query = " SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               expediteur,
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

} 

// -------------------------------------------------S'il s'agit d'un pole ---------------------------


elseif (!empty($requete_pole)) {



        // Requête de base pour récupérer courriers départs et arrivés
        $query = "SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'courrier départ' AS type_courrier
        FROM courrierdepart
        JOIN utilisateur u ON courrierdepart.Matricule_initiateur = u.Matricule
        JOIN pole p ON u.id_pole = p.id_pole
        WHERE p.nom_pole = :nom_pole 
    
    UNION ALL

SELECT idCourrier, numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        WHERE courrierarrive.destinataire = :nom_pole   
     UNION ALL

    SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'copie courrier' AS type_courrier
        FROM courrierdepart
        JOIN copie_courrier ON courrierdepart.idCourrier = copie_courrier.id_courrierDepart
        
        WHERE copie_courrier.nom_destinataire = :nom_pole
        
        UNION ALL
        
        SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'copie courrier' AS type_courrier
        FROM courrierarrive
        JOIN copie_courrier ON courrierarrive.idCourrier = copie_courrier.id_courrierArrive
        
        WHERE copie_courrier.nom_destinataire = :nom_pole
    
";


// Ajouter des conditions de filtre (si présentes)

$params = [':nom_pole' => $nom_entite];


    // Recherche par mot-clé
    if ($searchKeyword) {
        $query = "SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'courrier départ' AS type_courrier
        FROM courrierdepart
        JOIN utilisateur u ON courrierdepart.Matricule_initiateur = u.Matricule
        JOIN pole p ON u.id_pole = p.id_pole
        WHERE p.nom_pole = :nom_pole AND (objet_du_courrier LIKE :searchKeyword OR destinataire LIKE :searchKeyword)

        UNION ALL

        -- Requête pour les courriers arrivés
        SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire,
               expediteur, 
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        WHERE courrierarrive.destinataire = :nom_pole AND (objet_du_courrier LIKE :searchKeyword OR destinataire LIKE :searchKeyword)
        
    UNION ALL

    SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'copie courrier' AS type_courrier
        FROM courrierdepart
        JOIN copie_courrier ON courrierdepart.idCourrier = copie_courrier.id_courrierDepart
        
        WHERE copie_courrier.nom_destinataire = :nom_pole AND (objet_du_courrier LIKE :searchKeyword OR destinataire LIKE :searchKeyword)
        
        UNION ALL
        
        SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'copie courrier' AS type_courrier
        FROM courrierarrive
        JOIN copie_courrier ON courrierarrive.idCourrier = copie_courrier.id_courrierArrive
        
        WHERE copie_courrier.nom_destinataire = :nom_pole AND (objet_du_courrier LIKE :searchKeyword OR destinataire LIKE :searchKeyword)
    
    
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
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'courrier départ' AS type_courrier
        FROM courrierdepart
        JOIN utilisateur u ON courrierdepart.Matricule_initiateur = u.Matricule
        JOIN pole p ON u.id_pole = p.id_pole
        WHERE p.nom_pole = :nom_pole and courrierdepart.dateEnregistrement  BETWEEN :startDate AND :endDate

        UNION ALL

        -- Requête pour les courriers arrivés
        SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        WHERE courrierarrive.destinataire = :nom_pole and courrierarrive.dateEnregistrement  BETWEEN :startDate AND :endDate
        
    UNION ALL

    SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'copie courrier' AS type_courrier
        FROM courrierdepart
        JOIN copie_courrier ON courrierdepart.idCourrier = copie_courrier.id_courrierDepart
        
        WHERE copie_courrier.nom_destinataire = :nom_pole AND courrierdepart.dateEnregistrement  BETWEEN :startDate AND :endDate
        
        UNION ALL
        
        SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'copie courrier' AS type_courrier
        FROM courrierarrive
        JOIN copie_courrier ON courrierarrive.idCourrier = copie_courrier.id_courrierArrive
        
        WHERE copie_courrier.nom_destinataire = :nom_pole AND courrierarrive.dateEnregistrement  BETWEEN :startDate AND :endDate
     
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
               expediteur, 
               dateEnregistrement, 
               etat_courrier,
               'courrier départ' AS type_courrier
        FROM courrierdepart
        JOIN utilisateur u ON courrierdepart.Matricule_initiateur = u.Matricule
        JOIN pole p ON u.id_pole = p.id_pole
        WHERE p.nom_pole = :nom_pole  and courrierdepart.Etat_interne_externe= :origine

        UNION ALL

        -- Requête pour les courriers arrivés
        SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        WHERE courrierarrive.destinataire = :nom_pole and courrierarrive.Etat_interne_externe = :origine
    
    UNION ALL

    SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'copie courrier' AS type_courrier
        FROM courrierdepart
        JOIN copie_courrier ON courrierdepart.idCourrier = copie_courrier.id_courrierDepart
        
        WHERE copie_courrier.nom_destinataire = :nom_pole AND courrierdepart.Etat_interne_externe = :origine
    
        UNION ALL    
        SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'copie courrier' AS type_courrier
        FROM courrierarrive
        JOIN copie_courrier ON courrierarrive.idCourrier = copie_courrier.id_courrierArrive
        
        WHERE copie_courrier.nom_destinataire = :nom_pole AND courrierarrive.Etat_interne_externe = :origine
     
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
               expediteur, 
               dateEnregistrement, 
               etat_courrier,
               'courrier départ' AS type_courrier
        FROM courrierdepart
        JOIN utilisateur u ON courrierdepart.Matricule_initiateur = u.Matricule
        JOIN pole p ON u.id_pole = p.id_pole
        WHERE p.nom_pole = :nom_pole  and courrierdepart.categorie= :priority

        UNION ALL

        -- Requête pour les courriers arrivés
        SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        WHERE courrierarrive.destinataire = :nom_pole and courrierarrive.categorie = :priority
        UNION ALL

    SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'copie courrier' AS type_courrier
        FROM courrierdepart
        JOIN copie_courrier ON courrierdepart.idCourrier = copie_courrier.id_courrierDepart
        
        WHERE copie_courrier.nom_destinataire = :nom_pole AND  courrierdepart.categorie = :priority
        UNION ALL
        SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'copie courrier' AS type_courrier
        FROM courrierarrive
        JOIN copie_courrier ON courrierarrive.idCourrier = copie_courrier.id_courrierArrive
        
        WHERE copie_courrier.nom_destinataire = :nom_pole AND courrierarrive.categorie = :priority
    
    
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
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'courrier arrivé' AS type_courrier
        FROM courrierarrive
        WHERE courrierarrive.destinataire = :nom_pole
 ";
        } elseif ($typeCourrier === 'courrier départ') {
            $query = " SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               expediteur,
               dateEnregistrement, 
               etat_courrier,
               'courrier départ' AS type_courrier
        FROM courrierdepart
        JOIN utilisateur u ON courrierdepart.Matricule_initiateur = u.Matricule
        JOIN pole p ON u.id_pole = p.id_pole
        WHERE p.nom_pole = :nom_pole ";
        }
    }


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

function getPoleNameResultsFromQuery($nom_entite, $query) {
    // Connexion à la base de données
    $objet_connexion = connectToDb('localhost', 'ecourrierdb2', 'Dba', 'EcourrierDba');

    // Préparer la requête SQL
    $stmt = $objet_connexion->prepare($query);

    // Lier le paramètre :nom_entite à la valeur du nom de l'entité
    $stmt->bindValue(':nom_pole', $nom_entite);

    // Exécuter la requête
    $stmt->execute();

    // Récupérer les résultats sous forme de tableau associatif
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retourner les résultats
    return $results;
}

function recupererNomFichiers($lien){
    if (strlen($lien)==0) {
        return  ;
    }
    $partiesLien = explode("/",$lien);
    $nom_fichier = $partiesLien[count($partiesLien)-1];
    return $nom_fichier;

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
               expediteur,
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
        echo $searchKeyword;
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
               expediteur,
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
               expediteur,
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
               expediteur, 
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
        
        if ($typeCourrier === 'courrier arrivé') {
            $query = " SELECT idCourrier,numero_ordre, 
               objet_du_courrier,
               Etat_interne_externe,  -- Pas d'alias 'cd' ou 'ca'
               lien_courrier, 
               destinataire, 
               expediteur,
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
               expediteur, 
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
            $sql = "SELECT  h.action_effectuee, h.date_operation, h.entite_resoinsable,u.Matricule, u.nom_utilisateur, u.prenom_utilisateur 
                FROM historique h
                inner join utilisateur u
                on h.matricule_utilisateur = u.Matricule
                WHERE h.idCourrierdepart = :idCourrier
                group by h.action_effectuee, h.date_operation, h.entite_resoinsable, u.Matricule, u.nom_utilisateur, u.prenom_utilisateur
                ORDER BY h.date_operation DESC
            ";
        } elseif ($typeCourrier === 'courrier arrivé') {
            // Requête SQL pour récupérer les historiques liés à un courrier d'arrivée
            $sql = "SELECT h.action_effectuee, h.date_operation, h.entite_resoinsable, u.Matricule, u.nom_utilisateur, u.prenom_utilisateur 
                FROM historique h
                inner join utilisateur u
                on h.matricule_utilisateur = u.Matricule
                WHERE h.idCourrierArrive = :idCourrier
                group by h.action_effectuee, h.date_operation, h.entite_resoinsable, u.Matricule, u.nom_utilisateur, u.prenom_utilisateur
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


function recupererInfosCopieCourrier($requete,$idCourrier,$nom_entite){
    
       // Connexion à la base de données
       $objet_connexion = connectToDb('localhost', 'ecourrierdb2', 'Dba', 'EcourrierDba');

       // Préparer la requête SQL
       $stmt = $objet_connexion->prepare($requete);
   
       // Lier les paramètres :idCourrier et :type_courrier et à la valeur du nom de l'entité
       $stmt->bindValue(':idCourrier', $idCourrier);
       $stmt->bindValue(':nom_entite', $nom_entite);

       // Exécuter la requête
       $stmt->execute();
   
       // Récupérer les résultats sous forme de tableau associatif
       $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
       // Retourner les résultats
        return $results; 

}



// Cette fonction de récupérer l'historique d'une copie de courrier 

function recupererHistoriqueCopieCourrier($idCourrier,$nom_entite){
 $sql = "SELECT
    COALESCE(cd.idCourrier, ca.idCourrier) AS idCourrier,
    COALESCE(cd.Type_document, ca.Type_document) AS Type_document,
    COALESCE(cd.Etat_interne_externe, ca.Etat_interne_externe) AS origine_courrier,
    COALESCE(cd.etat_courrier, ca.etat_courrier) AS etat_courrier,
    COALESCE(cd.etat_plis_ferme, ca.etat_plis_ferme) AS plis_ferme,
    COALESCE(cd.dateEnregistrement, ca.dateEnregistrement) AS dateEnregistrement,
    COALESCE(cd.date_mise_circulation, ca.date_mise_circulation) AS date_mise_circulation,
    COALESCE(cd.Reference, ca.Reference) AS Reference,
    COALESCE(cd.lien_courrier, ca.lien_courrier) AS lien_courrier,
    COALESCE(cd.objet_du_courrier, ca.objet_du_courrier) AS Objet_du_courrier,
    COALESCE(cd.Matricule_initiateur, ca.Matricule_initiateur) AS Matricule,
    COALESCE(cd.destinataire, ca.destinataire) AS destinataire,
    u.nom_utilisateur,
    u.prenom_utilisateur,
    COALESCE(cd.nombre_fichiers_joins, ca.nombre_fichiers_joins) AS nombre_de_fichiers_joins,

    CASE
        WHEN cd.idCourrier IS NOT NULL THEN 'courrier départ'
        ELSE 'courrier arrivé'
    END AS type_courrier,
    COALESCE(cd.expediteur, ca.expediteur) AS expediteur,
    COALESCE(cd.numero_ordre, ca.numero_ordre) AS numero_ordre,
    COALESCE(cd.categorie, ca.categorie) AS categorie,
    COALESCE(cd.date_derniere_modification, ca.date_derniere_modification) AS date_derniere_modification,
    COALESCE(cd.signature_gouverneur, ca.signature_gouverneur) AS signature_gouverneur
FROM
    copie_courrier cc
    LEFT JOIN courrierdepart cd ON cc.id_courrierDepart = cd.idCourrier
    LEFT JOIN courrierarrive ca ON cc.id_courrierArrive = ca.idCourrier
    INNER JOIN utilisateur u ON COALESCE(cd.Matricule_initiateur, ca.Matricule_initiateur) = u.Matricule
WHERE
    COALESCE(cd.idCourrier, ca.idCourrier) =:idCourrier and cc.nom_destinataire = :nom_entite ;";

  $infos_courrier = recupererInfosCopieCourrier($sql,$idCourrier,$nom_entite) ;

    

   $type_courrier_origine = $infos_courrier[0]['type_courrier'];

    return recupererHistoriqueParIdCourrierEtType($idCourrier,$type_courrier_origine);

}







function recupererContenuCorbeilleParMatricule($matricule){

    $requete = "SELECT 
    cor.Type_document,
    cor.Etat_interne_externe AS origine_courrier,
    cor.etat_courrier,
    cor.etat_plis_ferme AS plis_ferme,
    cor.dateEnregistrement,
    cor.date_mise_circulation,
    cor.Reference,
    cor.lien_courrier,
    cor.objet_du_courrier,
    cor.numero_ordre,
    cor.categorie,
    cor.nombre_fichiers_joins AS nombre_de_fichiers_joins,
    cor.expediteur,
    cor.destinataire,
    cor.Matricule_initiateur,
    ut.nom_utilisateur AS nom_enregistreur,
    ut.prenom_utilisateur AS prenom_enregistreur,
    cor.categorie,
    cor.date_derniere_modification,
    cor.signature_gouverneur,
    cor.date_suppression,
    cor.Matricule_agent,
    u.nom_utilisateur AS nom_agent,
    u.prenom_utilisateur AS prenom_agent,
    -- Ajout du type de courrier
    CASE
        WHEN cor.idCourrierDepart IS NOT NULL THEN 'courrier départ'
        WHEN cor.idCourrierArrive IS NOT NULL THEN 'courrier arrivé'
        ELSE 'inconnu' -- Optionnel, si aucune correspondance, vous pouvez aussi mettre NULL
    END AS type_courrier
FROM 
    corbeille cor
INNER JOIN 
    utilisateur ut ON ut.matricule = cor.Matricule_initiateur 
INNER JOIN 
    utilisateur u ON u.matricule = cor.Matricule_agent
WHERE 
    cor.Matricule_agent = :matricule;
" ;;
// Connexion à la base de données
$objet_connexion = connectToDb('localhost', 'ecourrierdb2', 'Dba', 'EcourrierDba');

// Préparer la requête SQL
$stmt = $objet_connexion->prepare($requete);

// Lier les paramètres :idCourrier et :type_courrier et à la valeur du nom de l'entité
$stmt->bindValue(':matricule', $matricule);


// Exécuter la requête
$stmt->execute();

// Récupérer les résultats sous forme de tableau associatif
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retourner les résultats
 return $results; 

}


function recupererContenuCorbeilleParEntiteOuPole($entite_pole){

    $requete = "SELECT 
    cor.Type_document,
    cor.Etat_interne_externe AS origine_courrier,
    cor.etat_courrier,
    cor.etat_plis_ferme AS plis_ferme,
    cor.dateEnregistrement,
    cor.date_mise_circulation,
    cor.Reference,
    cor.lien_courrier,
    cor.objet_du_courrier,
    cor.numero_ordre,
    cor.categorie,
    cor.nombre_fichiers_joins AS nombre_de_fichiers_joins,
    cor.expediteur,
    cor.destinataire,
    cor.Matricule_initiateur,
    ut.nom_utilisateur AS nom_enregistreur,
    ut.prenom_utilisateur AS prenom_enregistreur,
    cor.categorie,
    cor.date_derniere_modification,
    cor.signature_gouverneur,
    cor.date_suppression,
    cor.Matricule_agent,
    u.nom_utilisateur AS nom_agent,
    u.prenom_utilisateur AS prenom_agent,
    -- Ajout du type de courrier
    CASE
        WHEN cor.idCourrierDepart IS NOT NULL THEN 'courrier départ'
        WHEN cor.idCourrierArrive IS NOT NULL THEN 'courrier arrivé'
        ELSE 'inconnu' -- Optionnel, si aucune correspondance, vous pouvez aussi mettre NULL
    END AS type_courrier,
    -- Ajout du nom de l'entité ou du pôle de l'utilisateur
    CASE
        WHEN u.id_pole IS NOT NULL THEN p.nom_pole
        WHEN u.id_entite IS NOT NULL THEN e.nom_entite
        ELSE 'Inconnu' -- Optionnel, si aucune correspondance
    END AS entite_ou_pole
FROM 
    corbeille cor
INNER JOIN 
    utilisateur ut ON ut.matricule = cor.Matricule_initiateur 
INNER JOIN 
    utilisateur u ON u.matricule = cor.Matricule_agent
LEFT JOIN 
    pole p ON u.id_pole = p.id_pole -- Lien entre utilisateur et pôle
LEFT JOIN 
    entite_banque e ON u.id_entite = e.id_entite -- Lien entre utilisateur et entité
WHERE 
    (p.nom_pole = :entite_pole OR e.nom_entite = :entite_pole);  --

" ;
// Connexion à la base de données
$objet_connexion = connectToDb('localhost', 'ecourrierdb2', 'Dba', 'EcourrierDba');

// Préparer la requête SQL
$stmt = $objet_connexion->prepare($requete);

// Lier les paramètres :idCourrier et :type_courrier et à la valeur du nom de l'entité
$stmt->bindValue(':entite_pole', $entite_pole);


// Exécuter la requête
$stmt->execute();

// Récupérer les résultats sous forme de tableau associatif
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retourner les résultats
 return $results; 

}





function getNextNumeroOrdreCourrierArriveInterne($expediteur, $destinataire) {
    // Connexion à la base de données via la fonction connectToDb
    $pdo = connectToDb('localhost', 'ecourrierdb2', 'Dba', 'EcourrierDba');
    
    // Préparer la requête SQL pour récupérer les numéros d'ordre des courriers arrivés internes
    $sql = "
        SELECT numero_ordre
        FROM courrierarrive
        WHERE Etat_interne_externe = 'courrier interne'
          AND expediteur LIKE :expediteur
          AND destinataire LIKE :destinataire
    ";
    
    // Préparer la requête
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':expediteur' => "%$expediteur%",
        ':destinataire' => "%$destinataire%"
    ]);
    
    // Tableau pour stocker les numéros d'ordre
    $numeros = [];
    
    // Récupérer tous les numéros d'ordre
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $numeros[] = $row['numero_ordre'];
    }
    
    // Si aucun numéro n'a été trouvé, retourner "1" comme premier numéro
    if (empty($numeros)) {
        return "1";
    }

    // Variable pour stocker la partie numérique la plus élevée
    $maxNum = 0;

    // Parcourir tous les numéros d'ordre récupérés
    foreach ($numeros as $numero) {
        // Diviser le numéro d'ordre au format [0-9]/[A-Z]/[A-Z]/2024, etc.
        $parts = explode('/', $numero);
        
        // Vérifier que la première partie est bien un nombre
        if (is_numeric($parts[0])) {
            // Récupérer la première partie (numérique) du numéro d'ordre
            $numPart = (int) $parts[0];
            
            // Mettre à jour la valeur maximale
            if ($numPart > $maxNum) {
                $maxNum = $numPart;
            }
        }
    }
    
    // Retourner la valeur maximale + 1 sous forme de chaîne
    return (string) ($maxNum + 1);
}





?>







