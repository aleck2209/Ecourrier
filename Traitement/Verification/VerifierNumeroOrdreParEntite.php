<?php
//Cette fonction va récupérer tout les numéro d'ordres et les regrouper par entite pour chaque entite ses numéros d'ordres doivent se suivre 

function verifierNumeoOrdreParEntite($entite){
    if (isset($entite)) {
        $requeteRecuperation = "select e.nom_entite,cd.numero_ordre
                                from entite_banque e inner join utilisateur u on 
                                e.id_entite = u.id_entite
                                inner join courrierdepart cd on cd.Matricule_initiateur = u.Matricule
                                group by  cd.numero_ordre,e.nom_entite;";

        $tableaudesenregistrements = recupererContenuParRequeteDansTableauNumeique($requeteRecuperation);
        
        $liste_numero_ordre=[];

    $max=0;
    for ($i=0; $i <count($tableaudesenregistrements) ; $i++) { 
    if (in_array($entite,$tableaudesenregistrements[$i])) {
        $liste_numero_ordre []=$tableaudesenregistrements[$i][1];
    }
}
 
    if (count($liste_numero_ordre)>0) {
        $max = max($liste_numero_ordre);
    } 
    else{
      $max;
    }
    return ++$max;

    } else {
        die('entite non trouvée et numero ordre non trouvé');
    }
    
    
    


}














function verifierNumeoOrdreParEntiteV2($entite) {
    if (isset($entite)) {
        // Requête SQL pour récupérer les numéros d'ordre et les entités
        $requeteRecuperation = "SELECT e.nom_entite, cd.numero_ordre
                                FROM entite_banque e 
                                INNER JOIN utilisateur u ON e.id_entite = u.id_entite
                                INNER JOIN courrierdepart cd ON cd.Matricule_initiateur = u.Matricule
                                GROUP BY cd.numero_ordre, e.nom_entite;";

        // Appel de la fonction pour récupérer les enregistrements sous forme de tableau
        $tableaudesenregistrements = recupererContenuParRequeteDansTableauNumeique($requeteRecuperation);

        // Liste des numéros d'ordre extraits
        $liste_numero_ordre = [];
        $max = 0;

        // Parcours des enregistrements pour trouver les numéros d'ordre pour l'entité donnée
        for ($i = 0; $i < count($tableaudesenregistrements); $i++) {
            // Si l'entité dans la ligne correspond à l'entité passée en paramètre
            if ($tableaudesenregistrements[$i][0] === $entite) {
                // Récupérer le numéro d'ordre
                $numero_ordre = $tableaudesenregistrements[$i][1];
                
                // Extraire la première partie du numéro d'ordre avant le '/'
                $numero_ordre_extrait = (int)explode('/', $numero_ordre)[0];
                
                // Ajouter à la liste des numéros d'ordre
                $liste_numero_ordre[] = $numero_ordre_extrait;
            }
        }

        // Si des numéros d'ordre existent pour cette entité, déterminer le plus grand
        if (count($liste_numero_ordre) > 0) {
            $max = max($liste_numero_ordre); // Trouver le plus grand numéro d'ordre
        }

        // Si aucun numéro d'ordre existant, commencer à 1
        if ($max == 0) {
            $max = 1;
        } else {
            // Incrémenter le max de 1 pour le prochain numéro
            $max++;
        }

        // Retourner uniquement le numéro d'ordre à entrer (sans l'année)
        return $max; // Par exemple : 4
    } else {
        die('Entité non trouvée et numéro d\'ordre non trouvé');
    }
}






function verifierNumeoOrdreParPole($entite) {
    if (isset($entite)) {
        // Requête SQL pour récupérer les numéros d'ordre et les entités
        $requeteRecuperation = "SELECT p.nom_pole, cd.numero_ordre
                                FROM pole p
                                INNER JOIN utilisateur u ON p.id_pole = u.id_pole
                                INNER JOIN courrierdepart cd ON cd.Matricule_initiateur = u.Matricule
                                GROUP BY cd.numero_ordre, p.nom_pole;";

        // Appel de la fonction pour récupérer les enregistrements sous forme de tableau
        $tableaudesenregistrements = recupererContenuParRequeteDansTableauNumeique($requeteRecuperation);

        // Liste des numéros d'ordre extraits
        $liste_numero_ordre = [];
        $max = 0;

        // Parcours des enregistrements pour trouver les numéros d'ordre pour l'entité donnée
        for ($i = 0; $i < count($tableaudesenregistrements); $i++) {
            // Si l'entité dans la ligne correspond à l'entité passée en paramètre
            if ($tableaudesenregistrements[$i][0] === $entite) {
                // Récupérer le numéro d'ordre
                $numero_ordre = $tableaudesenregistrements[$i][1];
                
                // Extraire la première partie du numéro d'ordre avant le '/'
                $numero_ordre_extrait = (int)explode('/', $numero_ordre)[0];
                
                // Ajouter à la liste des numéros d'ordre
                $liste_numero_ordre[] = $numero_ordre_extrait;
            }
        }

        // Si des numéros d'ordre existent pour cette entité, déterminer le plus grand
        if (count($liste_numero_ordre) > 0) {
            $max = max($liste_numero_ordre); // Trouver le plus grand numéro d'ordre
        }

        // Si aucun numéro d'ordre existant, commencer à 1
        if ($max == 0) {
            $max = 1;
        } else {
            // Incrémenter le max de 1 pour le prochain numéro
            $max++;
        }

        // Retourner uniquement le numéro d'ordre à entrer (sans l'année)
        return $max; // Par exemple : 4
    } else {
        die('Entité non trouvée et numéro d\'ordre non trouvé');
    }
}












function verifierNumeoOrdreParEntiteCourrierArv($entite) {
    if (isset($entite)) {
        // Requête SQL pour récupérer les numéros d'ordre et les entités
        $requeteRecuperation = "SELECT e.nom_entite, ca.numero_ordre
                                FROM entite_banque e 
                                INNER JOIN utilisateur u ON e.id_entite = u.id_entite
                                INNER JOIN courrierarrive ca ON ca.Matricule_initiateur = u.Matricule
                                GROUP BY ca.numero_ordre, e.nom_entite;";

        // Appel de la fonction pour récupérer les enregistrements sous forme de tableau
        $tableaudesenregistrements = recupererContenuParRequeteDansTableauNumeique($requeteRecuperation);

        // Liste des numéros d'ordre extraits
        $liste_numero_ordre = [];
        $max = 0;

        // Parcours des enregistrements pour trouver les numéros d'ordre pour l'entité donnée
        for ($i = 0; $i < count($tableaudesenregistrements); $i++) {
            // Si l'entité dans la ligne correspond à l'entité passée en paramètre
            if ($tableaudesenregistrements[$i][0] === $entite) {
                // Récupérer le numéro d'ordre
                $numero_ordre = $tableaudesenregistrements[$i][1];
                
                // Extraire la première partie du numéro d'ordre avant le '/'
                $numero_ordre_extrait = (int)explode('/', $numero_ordre)[0];
                
                // Ajouter à la liste des numéros d'ordre
                $liste_numero_ordre[] = $numero_ordre_extrait;
            }
        }

        // Si des numéros d'ordre existent pour cette entité, déterminer le plus grand
        if (count($liste_numero_ordre) > 0) {
            $max = max($liste_numero_ordre); // Trouver le plus grand numéro d'ordre
        }

        // Si aucun numéro d'ordre existant, commencer à 1
        if ($max == 0) {
            $max = 1;
        } else {
            // Incrémenter le max de 1 pour le prochain numéro
            $max++;
        }

        // Retourner uniquement le numéro d'ordre à entrer (sans l'année)
        return $max; // Par exemple : 4
    } else {
        die('Entité non trouvée et numéro d\'ordre non trouvé');
    }
}






?>