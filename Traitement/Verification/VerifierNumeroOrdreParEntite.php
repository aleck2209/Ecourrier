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
        // Requête SQL pour récupérer les numéros d'ordre, les entités, et les dates d'enregistrement
        $requeteRecuperation = "SELECT e.nom_entite, cd.numero_ordre, cd.dateEnregistrement
                                FROM entite_banque e 
                                INNER JOIN utilisateur u ON e.id_entite = u.id_entite
                                INNER JOIN courrierdepart cd ON cd.Matricule_initiateur = u.Matricule
                                WHERE e.nom_entite = '$entite' 
                                GROUP BY cd.numero_ordre, e.nom_entite, cd.dateEnregistrement
                                ORDER BY cd.dateEnregistrement DESC";  // Trier par date d'enregistrement décroissante

        // Appel de la fonction pour récupérer les enregistrements sous forme de tableau
        $tableaudesenregistrements = recupererContenuParRequeteDansTableauNumeique($requeteRecuperation);

        // Liste des numéros d'ordre extraits
        $liste_numero_ordre = [];
        $max = 0;
        $annee_actuelle = date("Y");
        $dernier_numero_ordre_annee = null; // Pour stocker l'année du dernier numéro d'ordre
        $max_numero_ordre = 0;  // Pour suivre le plus grand numéro d'ordre de l'année en cours

        // Parcours des enregistrements pour trouver les numéros d'ordre pour l'entité donnée
        foreach ($tableaudesenregistrements as $enregistrement) {
            // Si l'entité dans la ligne correspond à l'entité passée en paramètre
            if ($enregistrement[0] === $entite) {
                // Récupérer le numéro d'ordre et la date d'enregistrement
                $numero_ordre = $enregistrement[1];
                $date_enregistrement = $enregistrement[2];

                // Extraire l'année du numéro d'ordre (dernière partie après le dernier '/')
                $numero_ordre_complet = explode('/', $numero_ordre);

                // Vérifier que le numéro d'ordre est valide (au moins 2 parties)
                if (count($numero_ordre_complet) >= 2) {
                    $annee_numero_ordre = (int)$numero_ordre_complet[count($numero_ordre_complet) - 1];  // Année
                } else {
                    $annee_numero_ordre = 0;  // Si la structure du numéro est incorrecte, on met l'année à 0
                }

                // Si l'année actuelle est différente de l'année du dernier numéro d'ordre enregistré
                if ($annee_numero_ordre == $annee_actuelle) {
                    // Récupérer le plus grand numéro d'ordre pour l'année actuelle
                    $numero_ordre_extrait = (int)$numero_ordre_complet[0];  // Numéro d'ordre proprement dit
                    if ($numero_ordre_extrait > $max_numero_ordre) {
                        $max_numero_ordre = $numero_ordre_extrait;
                    }
                }

                // Mettre à jour l'année du dernier numéro d'ordre
                $dernier_numero_ordre_annee = $annee_numero_ordre;
            }
        }

        // Si l'année actuelle est supérieure à l'année du dernier courrier enregistré pour cette entité
        if ($dernier_numero_ordre_annee !== null && $annee_actuelle > $dernier_numero_ordre_annee) {
            // Réinitialiser le numéro d'ordre à 1 si l'année a changé
            $max = 1;
        } else {
            // Si l'année est la même, incrémenter le max_numero_ordre pour donner le prochain numéro
            $max = $max_numero_ordre + 1;
        }

        // Retourner uniquement le numéro d'ordre à entrer (sans l'année)
        return $max; // Exemple : 1 pour une nouvelle année, ou un numéro incrémenté pour l'année actuelle
    } else {
        // Si l'entité n'est pas trouvée ou mal définie
        die('Entité non trouvée et numéro d\'ordre non trouvé');
    }
}








function verifierNumeoOrdreParPole($entite) {
    if (isset($entite)) {
        // Requête SQL pour récupérer les numéros d'ordre et les entités
        $requeteRecuperation = "SELECT p.nom_pole, cd.numero_ordre, cd.dateEnregistrement
                                FROM pole p
                                INNER JOIN utilisateur u ON p.id_pole = u.id_pole
                                INNER JOIN courrierdepart cd ON cd.Matricule_initiateur = u.Matricule
                                WHERE p.nom_pole = '$entite' 
                                GROUP BY cd.numero_ordre, p.nom_pole, cd.dateEnregistrement
                                ORDER BY cd.dateEnregistrement DESC;";
                                

        // Appel de la fonction pour récupérer les enregistrements sous forme de tableau
        $tableaudesenregistrements = recupererContenuParRequeteDansTableauNumeique($requeteRecuperation);

        // Liste des numéros d'ordre extraits
        $liste_numero_ordre = [];
        $max = 0;
        $annee_actuelle = date("Y");
        $dernier_numero_ordre_annee = null; // Pour stocker l'année du dernier numéro d'ordre

        // Parcours des enregistrements pour trouver les numéros d'ordre pour l'entité donnée
        for ($i = 0; $i < count($tableaudesenregistrements); $i++) {
            // Si l'entité dans la ligne correspond à l'entité passée en paramètre
            if ($tableaudesenregistrements[$i][0] === $entite) {
                // Récupérer le numéro d'ordre et la date d'enregistrement
                $numero_ordre = $tableaudesenregistrements[$i][1];
                $date_enregistrement = $tableaudesenregistrements[$i][2];

                // Extraire l'année du numéro d'ordre (dernière partie après le dernier '/')
                $numero_ordre_complet = explode('/', $numero_ordre);
                $annee_numero_ordre = (int)$numero_ordre_complet[count($numero_ordre_complet) - 1];

                // Si c'est le premier numéro d'ordre, on enregistre l'année du dernier numéro d'ordre
                $dernier_numero_ordre_annee = $annee_numero_ordre;

                // Extraire la partie avant l'année (le numéro d'ordre proprement dit)
                $numero_ordre_extrait = (int)$numero_ordre_complet[0];

                // Ajouter à la liste des numéros d'ordre
                $liste_numero_ordre[] = $numero_ordre_extrait;
            }
        }

        // Vérifier si l'année actuelle est supérieure à l'année du dernier courrier enregistré pour cette entité
        if ($dernier_numero_ordre_annee !== null && $annee_actuelle > $dernier_numero_ordre_annee) {
            // Si l'année actuelle est plus grande, réinitialiser le numéro d'ordre à 1
            $max = 1;
        } else {
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
        }

        // Retourner uniquement le numéro d'ordre à entrer (sans l'année)
        return $max; // Par exemple : 4
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