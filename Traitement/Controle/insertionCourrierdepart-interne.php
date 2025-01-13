<?php
require('../../Traitement/Base_de_donnee/insertion.php');
require('../../Traitement/Verification/verifierValeursNullesCourrier.php');
require('../../Traitement/Controle/gestionFichierCourrierDepart.php');
require('../../Traitement/Controle/insertionCopieCourrier.php');
require('../../Traitement/Verification/VerifierNumeroOrdreParEntite.php');
require('../../Traitement/Verification/verifierFormat.php');
require('../../Traitement/Verification/verifierValeurEnum.php');
require('../../Traitement/Controle/gestionFichiesCourrierArrive.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // On commence par désactiver l'affichage des erreurs PHP en production
                ini_set('display_errors', 0); // Désactive l'affichage des erreurs
                error_reporting(E_ALL); // Active l'enregistrement des erreurs pour le débogage (peut être modifié en production)

                //récupération des données provenant du formulaire et vérification des données
                $numeroOrdre = verifierValeurNulle(trim($_POST['numero_ordre']));
                $TypeDoc = verifierValeurNulle(trim($_POST['Type_document']));
                //$etat_inter_exter= verifierValeurNulle(trim($_POST['Etat_interne_externe'])) ;
                $etat_plis_ferme = verifierValeurNulle(trim($_POST['etat_plis_ferme']));
                $categorie =verifierValeurNulle(trim($_POST['categorie']));
                $dateEnreg =verifierValeurNulle(trim($_POST['dateEnregistrement']));
                $reference =verifierValeurNulle(trim($_POST['Reference']));
                $fichier = $_FILES['fichier'];
                $objet = verifierValeurNulle(trim($_POST['Objet_du_courrier']));
                $matricule ='user04' ;
                $etatExpedition =  NULL ;
                $expediteur_courrierArv = verifierValeurNulle(trim($_POST['expediteur_courrierArv'])) ;
                $destinataire  = verifierValeurNulle($_POST['destinataire']) ;
                $liste_copie_courrier = verifierValeurNulle(trim($_POST['copie_courrier'])) ;
                $test_etat_interne_externe =trim($_POST['etat_interne_externe']);
                $test_type_courrier = verifierValeurNulle(trim($_POST['type_courrier']));
                $expediteur;


                $etat_inter_exter;


                $identite_dest ;
                $idpole_dest;
                $idReponse = null;

                    // if ($test_type_courrier==='courrier départ') {
                        
                    //     echo $test_type_courrier.'<br>';
                    //     echo $destinataire.'<br>';
                    //     echo $expediteur_courrierArv;
                    //     // die;
                    // } elseif ($test_type_courrier==='courrier arrivé') {
                    //     echo $test_type_courrier.'<br>';
                    //     echo 'expéditeur : '.$expediteur_courrierArv.'<br>';
                    //     echo 'destinataire : '.$destinataire;
                    //     // die;
                    // }



                    //-----------------------------------------Test des valeurs de l'état interne externe et de l'état expédition

                    $etat_inter_exter = ($test_etat_interne_externe =="interne") ? "courrier interne": "courrier externe" ;

                    $etatExpedition = null;

                    $lien = "";
                    $message = ""; // Mis à jour de la variable message d'erreur 

                    //récupération du tableau des entite destinataires ayant le nom entré 
                   $test_nombre_fichiers_joins = "Possible"; // Cette variable nous permet de savoir quand enregistrer un courrier 
                   $test_copie_courrier = "Possible"; // Cette variable permet de savoir quand enregistrer un courrier en fonction des copies
                    //---------------------------------------Contrôle des mtricules ---------------------------------


                        $sql1 = " select p.id_pole, p.nom_pole
                    from pole p inner join utilisateur u on 
                    p.id_pole = u.id_pole
                    where u.Matricule = ?;";

                $sql2=" select e.id_entite, e.nom_entite
                from entite_banque e inner join utilisateur u on 
                e.id_entite = u.id_entite
                where u.Matricule = ?;";

                $infos_entite_utilisateur = recupererIdEntiteOuIdPolePourUnUtilisateur($sql2,$matricule);

                $infos_pole_utilisateur = recupererIdEntiteOuIdPolePourUnUtilisateur($sql1,$matricule);


                
                


                    //--------------------------------------- Fin de controle des matricules ----------------------------------

                    if ($test_type_courrier==='courrier départ') {


                        //-----------------------Gestion de l'objet du courrier ---------------------------------
                        
                        if (strlen($objet)==0) {
                            $lien = "";
                            $message = "erreur  Objet non renseigné ";
                           
                        } else{
                        
                            // --------------ici l'objet est renseigné -----------------

                            if (strlen($numeroOrdre)==0) {
                                $lien = "";
                                $message = "Vous n'avez pas renseigné un numéro d'ordre pour votre courrier";
                                
                            } 

                                else {


                                      // -------------------------------- gestion du Numéro d'ordre --------------------------
                                            
                                      if (isset($infos_pole_utilisateur['id_pole'])) {
                                        
                                        $nom_entite = recupererNomEntiteParIdUtilisateur($sql1,$matricule);
                                        $expediteur = $nom_entite;
                                        
                                        #On récupère le numéro d'ordre qu'on doit entré en fonction de l'entité
                                        $num_a_entrer = verifierNumeoOrdreParPole($nom_entite);
                                        $numeroOrdrePrefix = explode('/', $numeroOrdre)[0];  // On récupère juste la partie avant le "/"
                                        
                                        // On compare le numéro d'ordre entré à celui qui est attendu en fonction de l'entité
                                        if ($numeroOrdrePrefix != $num_a_entrer) {
                                            $lien = ""; 
                                            $message="Le numéro d'ordre pour le pole  $nom_entite attendu est : $num_a_entrer";
                                                
                                        } 
                                            else {
                                            
                                            // ---------------------------

                                            if (strlen($dateEnreg)==0) {
                                                $lien = "";
                                                $message = "Vous n'avez pas renseigné une date d'enregistrement d'ordre pour votre courrier";
                                            } else {
                                                
                                                if ($etat_plis_ferme=="non" && strlen($TypeDoc)==0 ) {
                                                    $lien = "";
                                                    $message = "Entrer un type de document valide ";
                                                  

                                                } elseif ($etat_plis_ferme=="non" && strlen($_FILES['fichier']['name'])==0 ) {
                                                    $lien = ""; 
                                                    $message = " Veuillez entrer un fichier ";
                                              

                                                } elseif ($etat_plis_ferme=="non" && strlen($_FILES['fichier']['name'])!=0 ) {
                                                   
                                                        $liendossier = creerListeDossiersCourrierDepart($etat_inter_exter,$destinataire);
                                                    $liencourrier = deposerFichierDansDossier($liendossier,$fichier);
                                                    $nom_balise_fichiers_join ="fichiers_joints"; //Cette variable récupère la valeur de l'attribut name spécifié dans la balise html qui envoi les fichiers annexes
                                                    
                                                    $chemin_fichiers_joins = $liendossier."/FichierAnnexes";//Cette variable repésente le lien du dossier où on doit stocker les fichiers annexes
                                    
                                                    $liens_fichiers_joins = get_uploaded_files_paths($chemin_fichiers_joins,$nom_balise_fichiers_join);
                                    
                                                    // print_r($liens_fichiers_joins);
                                    
                                                    $liens_fichiers_joins_arrives = get_uploaded_files_pathsarrive($chemin_fichiers_joins,$nom_balise_fichiers_join);
                                    
                                                    if (isset($fichier)) {
                                                    $formatCourrier = pathinfo($fichier['name'],PATHINFO_FILENAME); # code...
                                                    }   
                                                    else{
                                                    $formatCourrier = null;
                                                    }

                                                    if (strlen($destinataire)==0) {
                                                        $lien = "";
                                                        $message = "Veuillez entrer un destinataire.";
                                                       
                                
                                                    } elseif (strlen($destinataire) != 0  ) {
                
                                     
                
                                                        $Liste_entite_destinataire = recupererLigneSpecifique('entite_banque','nom_entite',$destinataire);
                                                        $Liste_pole_destinataire = recupererLigneSpecifique('pole','nom_pole',$destinataire);
                                                        
                                                        
                                
                                
                                
                                                        //on récupère le nom et le format du fichier dans un tableau
                                                        
                                                        
                                                        // Récupération de l'id du destinataire du courrier 
                                                        if (isset($Liste_entite_destinataire)) {
                                                            $objet_entite_banque = $Liste_entite_destinataire[0];
                                                            $identite_dest = $objet_entite_banque->id_entite;
                                                        } else {
                                                            $identite_dest = null;
                                                        }
                                                        
                                                        
                                                        if (isset($Liste_pole_destinataire)) {
                                                            $objet_pole_dest = $Liste_pole_destinataire[0];
                                                            $idpole_dest = $objet_pole_dest->id_pole;
                                                        }else {
                                                            $idpole_dest = null;
                                                        } 
                                
                                
                                
                                                       
                                
                                                         
                                                            if (is_null($identite_dest) && is_null($idpole_dest)) {
                                                                # Si on entre ici cela veut dire qu'il n'a pas entrer un destinataire interne à la banque
                                                                $lien = "";
                                                                $message = "erreur  le destinataire n\'est pas une reconnu comme une entité de la banque";
                                                            
                                                            } else {


                                                                try {
                                                                    // Récupérer et nettoyer le champ 'nombre_joins'
                                                                    $nombre_fichiers_joins = trim($_POST['nombre_joins']);
                                                
                                                                    // Cas où 'nombre_joins' est vide et aucun fichier n'est joint
                                                                    if (empty($nombre_fichiers_joins) && count($liens_fichiers_joins) == 0) {
                                                                        // Aucun fichier joint et pas de nombre renseigné => Le programme continue normalement.
                                                                        $nombre_fichiers_joins = 0;
    
                                                                        
                                                                    }
                                                                    // Cas où 'nombre_joins' est renseigné
                                                                    elseif (!empty($nombre_fichiers_joins)) {
                                                                        // Vérifier que 'nombre_joins' est un entier
    
                                                                        if (!ctype_digit($nombre_fichiers_joins)) {
                                                                            $lien = "";
                                                                            $message =  "Le nombre de fichiers joints est impérativement un nombre entier positif, veuillez entrer un nombre entier.";
                                                                          
                                                                           $test_nombre_fichiers_joins = "pas Possible"; // Cette variable permet de savoir si on peut enregistrer si 
    
                                                                        
                                                                        }
                                                
                                                                        // Convertir en entier
                                                                        $nombre_fichiers_joins = (int)$nombre_fichiers_joins;
                                                                        
                                                                        // Si le nombre renseigné ne correspond pas au nombre de fichiers joints
                                                                        if ($nombre_fichiers_joins != count($liens_fichiers_joins)) {
                                                                            $test_nombre_fichiers_joins = "pas Possible";
                                                                            $lien = "";
                                                                            $message = "Le nombre de fichiers joints renseigné ($nombre_fichiers_joins) ne correspond pas au nombre de fichiers sélectionnés (" . count($liens_fichiers_joins) . "). Veuillez entrer le bon nombre de fichiers.";
                                                                          
                                                                        }
                                                                    }
                                                                    // Cas où 'nombre_joins' n'est pas renseigné mais des fichiers sont joints
                                                                    elseif (empty($nombre_fichiers_joins) && count($liens_fichiers_joins) > 0) {
                                                                        $lien = "";
                                                                        $message = "Le nombre de fichiers joints doit être précisé, veuillez entrer un nombre.";
                                                                      
                                                                        $test_nombre_fichiers_joins = "pas Possible";
                                                                    }
                                                                    // Cas où 'nombre_joins' est renseigné mais aucun fichier n'est joint
                                                                    elseif ($nombre_fichiers_joins > 0 && count($liens_fichiers_joins) == 0) {
                                                                        $lien = "";
                                                                        $message = "Vous avez précisé un nombre de fichiers joints, mais aucun fichier n'a été sélectionné.";
                                                                     
                                                                        $test_nombre_fichiers_joins = "pas Possible";
                                                                    }
                                                
                                                                    // Si les deux sont renseignés et sont égaux, ou si aucun des deux n'est renseigné (et pas de fichiers joints), le programme continue normalement
                                                
                                                                } catch (Exception $e) {
                                                                    die("Une erreur s'est produite : " . $e->getMessage());
                                                                }
                                                                


                                                                if (strlen($liste_copie_courrier)!=0) {
                                                                    $TableauNomDestinataireCopie = explode(",",$liste_copie_courrier) ;
                                                                   
                                                                    //On vérifie que les noms des copies existent bien dans la base de données
                                                                    if (count($TableauNomDestinataireCopie)>0) {

                                                                        foreach ($TableauNomDestinataireCopie as $nom_copie) {

                                                                        $Liste_des_entites_en_copies = recupererLigneSpecifique('entite_banque','nom_entite',$nom_copie);
                                                                        $Liste_des_poles_en_copie = recupererLigneSpecifique('pole','nom_pole',$nom_copie);
                                            
                                                                    if (isset($Liste_des_entites_en_copies)) {
                                                                        $objet_entite_banque_copie = $Liste_des_entites_en_copies[0];
                                                                        $identite_copie = $objet_entite_banque_copie->id_entite;
                                                                    } else {
                                                                        $identite_copie = null;
                                                                    }
                                                                    
                                                
                                                                    if (isset($Liste_des_poles_en_copie)) {
                                                                        $objet_pole_copie = $Liste_des_poles_en_copie[0];
                                                                        $idpole_copie = $objet_pole_copie->id_pole;
                                                                    }else {
                                                                        $idpole_copie = null;
                                                                    } 

                                                                    if (is_null($identite_copie) && is_null($idpole_copie)) {
                                                                        $lien = "";
                                                                        $message="erreur  le destinataire '. $nom_copie .' mentionné en copie n\'est pas reconnu comme une entité de la banque";
                                                                        echo $message;
                                                                    }
                                                                    

                                                                        }

                                                                        
                                                                        

                                                                    }

                                                                }

                                                                                                                                // Enregistrement de courrier 

                                                                                                                                if ($test_nombre_fichiers_joins ==="Possible" && $test_copie_courrier ==="Possible" ) {
                                                                                                                                    # code...
                                                                
                                                                                                                                    $etatCourrier = 'envoyé';
                                                                                                                                    $idcourrierdepart = insererCourrierDepart($numeroOrdre,$TypeDoc,$etat_inter_exter,  
                                                                                                                                    $etat_plis_ferme,$categorie,$dateEnreg,null,$reference,
                                                                                                                                    $liencourrier,$objet,$matricule,$idReponse,$etatExpedition,$expediteur,$destinataire,$identite_dest,$idpole_dest,
                                                                                                                                    $nombre_fichiers_joins,$etatCourrier
                                                                                                                                    );
                                                                                                                    
                                                                                                                                    //Mise à jour de l'historique de ce courrier
                                                                                                                                    insertHistorique("enregistrement du courrier",$idcourrierdepart,$nom_entite,"courrier départ",$matricule);
                                                                                                                                    //---------------------------------------------Insertion des copies de courriers dans la base de données----------------------------- 
                                                                                                                    
                                                                                                                                    if (!in_array(null,$TableauNomDestinataireCopie)) {
                                                                                                                                         entrerLesCopies($TableauNomDestinataireCopie,$liencourrier,$idcourrierdepart,null); 
                                                                                                                                        
                                                                                                                                    }
                                                                                                                    
                                                                                                                    
                                                                                                                                    //---------------------------------------------Insérer les fichiers joins - ----------------------------------------
                                                                                                                    
                                                                                                                                    if ($nombre_fichiers_joins ===count($liens_fichiers_joins)) {
                                                                                                                                        foreach ($liens_fichiers_joins as $lien) {
                                                                                                                                            insererFichierJoin($lien,$idcourrierdepart,null);
                                                                                                                                        }
                                                                                                                                        
                                                                                                                                    }
                                                                                                                    
                                                                
                                                                
                                                                                                                                                        //--------------------------------------------insertion automatique du courrier arrivé de ce destinataire----------------------------
                                                                
                                                                                                                                                                    if ($expediteur !== $destinataire) {
                                                                
                                                                                                                                                                        $expediteur = $nom_entite;
                                                                
                                                                                                                                                                        // echo " expediteur : $expediteur  ddestinataire: $destinataire";
                                                                
                                                                                                                                                                        $etatCourrier = 'reçu';
                                                                                                                                                                    $idcourrierArrive = insererCourrierArriveV2($numeroOrdre,$TypeDoc,$etat_inter_exter,
                                                                                                                                                                    $etat_plis_ferme,$categorie,$dateEnreg,null,$reference,
                                                                                                                                                                    $liencourrier,$objet,$matricule,$idReponse,$expediteur,$destinataire,$identite_dest,$idpole_dest,
                                                                                                                                                                    $nombre_fichiers_joins,$etatCourrier
                                                                                                                                                                    );
                                                                
                                                                
                                                                                                                                                                    //Mise à jour de l'historique de ce courrier
                                                                                                                                                                    insertHistorique("enregistrement du courrier",$idcourrierArrive,$nom_entite,"courrier arrivé",$matricule);
                                                                                                                                                                    //--------------------------------------------insertion automatique du courrier arrivé de ce destinataire----------------------------
                                                                
                                                                                                                                                                    if ($nombre_fichiers_joins ===count($liens_fichiers_joins_arrives )) {
                                                                                                                                                                        foreach ($liens_fichiers_joins_arrives  as $lien) {
                                                                                                                                                                            insererFichierJoin($lien,null,$idcourrierArrive);
                                                                                                                                                                        }
                                                                                                                                                                        
                                                                                                                                                                    }
                                                                
                                                                                                                                                                    }
                                                                                                                                                                    $lien = "../../public/page/tableau-bord";
                                                                                                                                                                    $message = "Courrier enregistré avec succès";
                                                                                                                                                                   
                                                                
                                                                
                                                                
                                                                
                                                                                                                                } 

                                                            }
                                    
                                                        }
                                                    
                                            }

                                                  // ------------------------------- Plis fermés -----------------------------------

                                                  elseif ($etat_plis_ferme === "oui" ) {

                                                    
                                                      
                                                    
                                                    $formatCourrier = null;
                                                   

                                                    if (strlen($destinataire)==0) {
                                                        $message = "Veuillez entrer un destinataire.";
                                                        echo $message;
                                                        var_dump($message);
                                
                                                    }
                                                    elseif (strlen($destinataire) != 0  ) {
                                                        $Liste_entite_destinataire = recupererLigneSpecifique('entite_banque','nom_entite',$destinataire);
                                                        $Liste_pole_destinataire = recupererLigneSpecifique('pole','nom_pole',$destinataire);
                                                        
                                                        
                                
                                
                                
                                                        //on récupère le nom et le format du fichier dans un tableau
                                                        
                                                        
                                                        // Récupération de l'id du destinataire du courrier 
                                                        if (isset($Liste_entite_destinataire)) {
                                                            $objet_entite_banque = $Liste_entite_destinataire[0];
                                                            $identite_dest = $objet_entite_banque->id_entite;
                                                        } else {
                                                            $identite_dest = null;
                                                        }
                                                        
                                                        
                                                        if (isset($Liste_pole_destinataire)) {
                                                            $objet_pole_dest = $Liste_pole_destinataire[0];
                                                            $idpole_dest = $objet_pole_dest->id_pole;
                                                        }else {
                                                            $idpole_dest = null;
                                                        } 

                                                        if (is_null($identite_dest) && is_null($idpole_dest)) {
                                                            # Si on entre ici cela veut dire qu'il n'a pas entrer un destinataire interne à la banque
                                                            $lien = "";
                                                            $message = "erreur  le destinataire n\'est pas une reconnu comme une entité de la banque";
                                                          
                                                        } 
                                                        
                                                        else {

                                                                



                                                            try {
                                                                // Récupérer et nettoyer le champ 'nombre_joins'
                                                                $nombre_fichiers_joins = trim($_POST['nombre_joins']);
                                            
                                                                // Cas où 'nombre_joins' est vide et aucun fichier n'est joint
                                                                if (empty($nombre_fichiers_joins) && count($liens_fichiers_joins) == 0) {
                                                                    // Aucun fichier joint et pas de nombre renseigné => Le programme continue normalement.
                                                                    $nombre_fichiers_joins = 0;

                                                                    
                                                                }
                                                                // Cas où 'nombre_joins' est renseigné
                                                                elseif (!empty($nombre_fichiers_joins)) {
                                                                    // Vérifier que 'nombre_joins' est un entier

                                                                    if (!ctype_digit($nombre_fichiers_joins)) {
                                                                        $lien = "";
                                                                       $message =  "Le nombre de fichiers joints est impérativement un nombre entier positif, veuillez entrer un nombre entier.";
                                                                    
                                                                       $test_nombre_fichiers_joins = "pas Possible"; // Cette variable permet de savoir si on peut enregistrer si 

                                                                    
                                                                    }
                                            
                                                                    // Convertir en entier
                                                                    $nombre_fichiers_joins = (int)$nombre_fichiers_joins;
                                                                    
                                                                    // Si le nombre renseigné ne correspond pas au nombre de fichiers joints
                                                                    if ($nombre_fichiers_joins != count($liens_fichiers_joins)) {
                                                                        $lien = "";
                                                                        $test_nombre_fichiers_joins = "pas Possible";
                                                                        $message = "Le nombre de fichiers joints renseigné ($nombre_fichiers_joins) ne correspond pas au nombre de fichiers sélectionnés (" . count($liens_fichiers_joins) . "). Veuillez entrer le bon nombre de fichiers.";
                                                                        
                                                                        
                                                                    }
                                                                }
                                                                // Cas où 'nombre_joins' n'est pas renseigné mais des fichiers sont joints
                                                                elseif (empty($nombre_fichiers_joins) && count($liens_fichiers_joins) > 0) {
                                                                    $lien = "";
                                                                    $message = "Le nombre de fichiers joints doit être précisé, veuillez entrer un nombre.";
                                                                    echo $message;
                                                                    var_dump($message);
                                                                    $test_nombre_fichiers_joins = "pas Possible";
                                                                }
                                                                // Cas où 'nombre_joins' est renseigné mais aucun fichier n'est joint
                                                                elseif ($nombre_fichiers_joins > 0 && count($liens_fichiers_joins) == 0) {
                                                                    $lien = "";
                                                                    $message = "Vous avez précisé un nombre de fichiers joints, mais aucun fichier n'a été sélectionné.";
                                                                    echo $message;
                                                                    var_dump($message);
                                                                    $test_nombre_fichiers_joins = "pas Possible";
                                                                }
                                            
                                                                // Si les deux sont renseignés et sont égaux, ou si aucun des deux n'est renseigné (et pas de fichiers joints), le programme continue normalement
                                            
                                                            } catch (Exception $e) {
                                                                die("Une erreur s'est produite : " . $e->getMessage());
                                                            }







                                                            if (strlen($liste_copie_courrier)!=0) {
                                                                $TableauNomDestinataireCopie = explode(", ",$liste_copie_courrier) ;
                                                               
                                                                //On vérifie que les noms des copies existent bien dans la base de données
                                                                if (count($TableauNomDestinataireCopie)>0) {

                                                                    foreach ($TableauNomDestinataireCopie as $nom_copie) {

                                                                    $Liste_des_entites_en_copies = recupererLigneSpecifique('entite_banque','nom_entite',$nom_copie);
                                                                    $Liste_des_poles_en_copie = recupererLigneSpecifique('pole','nom_pole',$nom_copie);
                                        
                                                                if (isset($Liste_des_entites_en_copies)) {
                                                                    $objet_entite_banque_copie = $Liste_des_entites_en_copies[0];
                                                                    $identite_copie = $objet_entite_banque_copie->id_entite;
                                                                } else {
                                                                    $identite_copie = null;
                                                                }
                                                                
                                            
                                                                if (isset($Liste_des_poles_en_copie)) {
                                                                    $objet_pole_copie = $Liste_des_poles_en_copie[0];
                                                                    $idpole_copie = $objet_pole_copie->id_pole;
                                                                }else {
                                                                    $idpole_copie = null;
                                                                } 

                                                                if (is_null($identite_copie) && is_null($idpole_copie)) {
                                                                    $lien = "";
                                                                    $test_copie_courrier = "pas possible";
                                                                    $message="erreur  le destinataire '. $nom_copie .' mentionné en copie n\'est pas reconnu comme une entité de la banque";
                                                                    echo $message;
                                                                    var_dump($message);

                                                                } 
                                                                
                                                                

                                                                    }

                                                                    
                                                                    

                                                                }

                                                            }


                                                            // Enregistrement de courrier 

                                                            
                                                                # code...

                                                         

                                                                $etatCourrier = 'envoyé';
                                                                $idcourrierdepart = insererCourrierDepart($numeroOrdre,$TypeDoc,$etat_inter_exter,  
                                                                $etat_plis_ferme,$categorie,$dateEnreg,null,$reference,
                                                                $liencourrier,$objet,$matricule,$idReponse,$etatExpedition,$expediteur,$destinataire,$identite_dest,$idpole_dest,
                                                                $nombre_fichiers_joins,$etatCourrier
                                                                );
                                                
                                                                //Mise à jour de l'historique de ce courrier
                                                                insertHistorique("enregistrement du courrier",$idcourrierdepart,$nom_entite,"courrier départ",$matricule);
                                                                //---------------------------------------------Insertion des copies de courriers dans la base de données----------------------------- 
                                                                
                                                                // if (count($TableauNomDestinataireCopie)==0) {
                                                                //     # code...
                                                                //     if (!in_array(null,$TableauNomDestinataireCopie)) {

                                                                //         entrerLesCopies($TableauNomDestinataireCopie,$liencourrier,$idcourrierdepart,null); 
                                                                       
                                                                //    }
                                                                // }
                                   
                                                
                                                
                                                                //---------------------------------------------Insérer les fichiers joins - ----------------------------------------
                                                
                                                                if ($nombre_fichiers_joins ===count($liens_fichiers_joins)) {
                                                                    foreach ($liens_fichiers_joins as $lien) {
                                                                        insererFichierJoin($lien,$idcourrierdepart,null);
                                                                    }
                                                                    
                                                                }
                                                


                                                                                    //--------------------------------------------insertion automatique du courrier arrivé de ce destinataire----------------------------

                                                                                                if ($expediteur !== $destinataire) {

                                                                                                    $expediteur = $nom_entite;

                                                                                                    // echo " expediteur : $expediteur  ddestinataire: $destinataire";

                                                                                                    $etatCourrier = 'reçu';
                                                                                                $idcourrierArrive = insererCourrierArriveV2($numeroOrdre,$TypeDoc,$etat_inter_exter,
                                                                                                $etat_plis_ferme,$categorie,$dateEnreg,null,$reference,
                                                                                                $liencourrier,$objet,$matricule,$idReponse,$expediteur,$destinataire,$identite_dest,$idpole_dest,
                                                                                                $nombre_fichiers_joins,$etatCourrier
                                                                                                );


                                                                                                //Mise à jour de l'historique de ce courrier
                                                                                                insertHistorique("enregistrement du courrier",$idcourrierArrive,$nom_entite,"courrier arrivé",$matricule);
                                                                                                //--------------------------------------------insertion automatique du courrier arrivé de ce destinataire----------------------------

                                                                                                if ($nombre_fichiers_joins ===count($liens_fichiers_joins_arrives )) {
                                                                                                    foreach ($liens_fichiers_joins_arrives  as $lien) {
                                                                                                        insererFichierJoin($lien,null,$idcourrierArrive);
                                                                                                    }
                                                                                                    
                                                                                                }

                                                                                                }
                                                                                                $lien = "../../public/page/tableau-bord";
                                                                                                $message = "Courrier enregistré avec succès";
                                                                                               




                                                               





                                                         }

                                                    }

                                           
                                                    


                                                   

                                                }
                                                


                                            }



                                        }
                                        
                                        
                                      }
                                    
                                        elseif (isset($infos_entite_utilisateur['id_entite'])) {
                                            $nom_entite = recupererNomEntiteParIdUtilisateur($sql2,$matricule);
                                            $expediteur = $nom_entite;
                                        
                                            #On récupère le numéro d'ordre qu'on doit entré en fonction de l'entité
                                            $num_a_entrer = verifierNumeoOrdreParEntiteV2($nom_entite);
                                            $numeroOrdrePrefix = explode('/', $numeroOrdre)[0];  // On récupère juste la partie avant le "/"
                                            
                                            // On compare le numéro d'ordre entré à celui qui est attendu en fonction de l'entité
                                            if ($numeroOrdrePrefix != $num_a_entrer) {
                                                $lien = "";
                                                $message = "Le numéro d'ordre pour l'entité $nom_entite attendu est : $num_a_entrer";
                                                echo $message;
                                                var_dump($message);
                                            } else {
                                                
                                            if (strlen($dateEnreg)==0) {
                                                $lien = "";
                                                $message = "Vous n'avez pas renseigné une date d'enregistrement d'ordre pour votre courrier";
                                            } else {

                                                if ($etat_plis_ferme=="non" && strlen($TypeDoc)==0 ) {
                                                    $lien = "";
                                                    $message = "Entrer un type de document valide ";
                                                   

                                                } elseif ($etat_plis_ferme=="non" && strlen($_FILES['fichier']['name'])==0 ) {
                                                    $lien = "";
                                                    $message = "Veuillez entrer un fichier ";
                                                   
                                                } 

                                                if ($etat_plis_ferme=="non" && strlen($_FILES['fichier']['name'])!=0) {
                                                    
                                                    $liendossier = creerListeDossiersCourrierDepart($etat_inter_exter,$destinataire);
                                                    $liencourrier = deposerFichierDansDossier($liendossier,$fichier);
                                                    $nom_balise_fichiers_join ="fichiers_joints"; //Cette variable récupère la valeur de l'attribut name spécifié dans la balise html qui envoi les fichiers annexes
                                                    
                                                    $chemin_fichiers_joins = $liendossier."/FichierAnnexes";//Cette variable repésente le lien du dossier où on doit stocker les fichiers annexes
                                    
                                                    $liens_fichiers_joins = get_uploaded_files_paths($chemin_fichiers_joins,$nom_balise_fichiers_join);
                                    
                                                    // print_r($liens_fichiers_joins);
                                    
                                                    $liens_fichiers_joins_arrives = get_uploaded_files_pathsarrive($chemin_fichiers_joins,$nom_balise_fichiers_join);
                                    
                                                    if (isset($fichier)) {
                                                    $formatCourrier = pathinfo($fichier['name'],PATHINFO_FILENAME); # code...
                                                    }   
                                                    else{
                                                    $formatCourrier = null;
                                                    } 

                                                    if (strlen($destinataire)==0) {
                                                        $lien = "";
                                                        $message = "Veuillez entrer un destinataire.";
                                                       
                                
                                                    } elseif (strlen($destinataire) != 0  ) {
                
                                     
                
                                                        $Liste_entite_destinataire = recupererLigneSpecifique('entite_banque','nom_entite',$destinataire);
                                                        $Liste_pole_destinataire = recupererLigneSpecifique('pole','nom_pole',$destinataire);
                                                        
                                                        
                                
                                
                                
                                                        //on récupère le nom et le format du fichier dans un tableau
                                                        
                                                        
                                                        // Récupération de l'id du destinataire du courrier 
                                                        if (isset($Liste_entite_destinataire)) {
                                                            $objet_entite_banque = $Liste_entite_destinataire[0];
                                                            $identite_dest = $objet_entite_banque->id_entite;
                                                        } else {
                                                            $identite_dest = null;
                                                        }
                                                        
                                                        
                                                        if (isset($Liste_pole_destinataire)) {
                                                            $objet_pole_dest = $Liste_pole_destinataire[0];
                                                            $idpole_dest = $objet_pole_dest->id_pole;
                                                        }else {
                                                            $idpole_dest = null;
                                                        } 
                                
                                
                                
                                                       
                                
                                                         
                                                            if (is_null($identite_dest) && is_null($idpole_dest)) {
                                                                # Si on entre ici cela veut dire qu'il n'a pas entrer un destinataire interne à la banque
                                                                $lien = "";
                                                                $message = "erreur  le destinataire n\'est pas une reconnu comme une entité de la banque";
                                                             
                                                            } 
                                                             else {

                                                                



                                                                try {
                                                                    // Récupérer et nettoyer le champ 'nombre_joins'
                                                                    $nombre_fichiers_joins = trim($_POST['nombre_joins']);
                                                
                                                                    // Cas où 'nombre_joins' est vide et aucun fichier n'est joint
                                                                    if (empty($nombre_fichiers_joins) && count($liens_fichiers_joins) == 0) {
                                                                        // Aucun fichier joint et pas de nombre renseigné => Le programme continue normalement.
                                                                        $nombre_fichiers_joins = 0;

                                                                        
                                                                    }
                                                                    // Cas où 'nombre_joins' est renseigné
                                                                    elseif (!empty($nombre_fichiers_joins)) {
                                                                        // Vérifier que 'nombre_joins' est un entier

                                                                        if (!ctype_digit($nombre_fichiers_joins)) {
                                                                            $lien = "";
                                                                           $message =  "Le nombre de fichiers joints est impérativement un nombre entier positif, veuillez entrer un nombre entier.";
                                                                          
                                                                           $test_nombre_fichiers_joins = "pas Possible"; // Cette variable permet de savoir si on peut enregistrer si 

                                                                        
                                                                        }
                                                
                                                                        // Convertir en entier
                                                                        $nombre_fichiers_joins = (int)$nombre_fichiers_joins;
                                                                        
                                                                        // Si le nombre renseigné ne correspond pas au nombre de fichiers joints
                                                                        if ($nombre_fichiers_joins != count($liens_fichiers_joins)) {
                                                                            $lien = "";
                                                                            $test_nombre_fichiers_joins = "pas Possible";
                                                                            $message = "Le nombre de fichiers joints renseigné ($nombre_fichiers_joins) ne correspond pas au nombre de fichiers sélectionnés (" . count($liens_fichiers_joins) . "). Veuillez entrer le bon nombre de fichiers.";
                                                                            
                                                                          
                                                                        }
                                                                    }
                                                                    // Cas où 'nombre_joins' n'est pas renseigné mais des fichiers sont joints
                                                                    elseif (empty($nombre_fichiers_joins) && count($liens_fichiers_joins) > 0) {
                                                                        $lien = "";
                                                                        $message = "Le nombre de fichiers joints doit être précisé, veuillez entrer un nombre.";
                                                                     
                                                                        $test_nombre_fichiers_joins = "pas Possible";
                                                                    }
                                                                    // Cas où 'nombre_joins' est renseigné mais aucun fichier n'est joint
                                                                    elseif ($nombre_fichiers_joins > 0 && count($liens_fichiers_joins) == 0) {
                                                                        $lien = "";
                                                                        $message = "Vous avez précisé un nombre de fichiers joints, mais aucun fichier n'a été sélectionné.";
                                                                       
                                                                        $test_nombre_fichiers_joins = "pas Possible";
                                                                    }
                                                
                                                                    // Si les deux sont renseignés et sont égaux, ou si aucun des deux n'est renseigné (et pas de fichiers joints), le programme continue normalement
                                                
                                                                } catch (Exception $e) {
                                                                    die("Une erreur s'est produite : " . $e->getMessage());
                                                                }







                                                                if (strlen($liste_copie_courrier)!=0) {
                                                                    $TableauNomDestinataireCopie = explode(", ",$liste_copie_courrier) ;
                                                                   
                                                                    //On vérifie que les noms des copies existent bien dans la base de données
                                                                    if (count($TableauNomDestinataireCopie)>0) {

                                                                        foreach ($TableauNomDestinataireCopie as $nom_copie) {

                                                                        $Liste_des_entites_en_copies = recupererLigneSpecifique('entite_banque','nom_entite',$nom_copie);
                                                                        $Liste_des_poles_en_copie = recupererLigneSpecifique('pole','nom_pole',$nom_copie);
                                            
                                                                    if (isset($Liste_des_entites_en_copies)) {
                                                                        $objet_entite_banque_copie = $Liste_des_entites_en_copies[0];
                                                                        $identite_copie = $objet_entite_banque_copie->id_entite;
                                                                    } else {
                                                                        $identite_copie = null;
                                                                    }
                                                                    
                                                
                                                                    if (isset($Liste_des_poles_en_copie)) {
                                                                        $objet_pole_copie = $Liste_des_poles_en_copie[0];
                                                                        $idpole_copie = $objet_pole_copie->id_pole;
                                                                    }else {
                                                                        $idpole_copie = null;
                                                                    } 

                                                                    if (is_null($identite_copie) && is_null($idpole_copie)) {
                                                                        $lien = "";
                                                                        $test_copie_courrier = "pas possible";
                                                                        $message="erreur  le destinataire '. $nom_copie .' mentionné en copie n\'est pas reconnu comme une entité de la banque";
                                                                        

                                                                    } 
                                                                    
                                                                    

                                                                        }

                                                                        
                                                                        

                                                                    }

                                                                }


                                                                // Enregistrement de courrier 

                                                                if ($test_nombre_fichiers_joins ==="Possible" && $test_copie_courrier ==="Possible" ) {
                                                                    # code...

                                                                    $etatCourrier = 'envoyé';
                                                                    $idcourrierdepart = insererCourrierDepart($numeroOrdre,$TypeDoc,$etat_inter_exter,  
                                                                    $etat_plis_ferme,$categorie,$dateEnreg,null,$reference,
                                                                    $liencourrier,$objet,$matricule,$idReponse,$etatExpedition,$expediteur,$destinataire,$identite_dest,$idpole_dest,
                                                                    $nombre_fichiers_joins,$etatCourrier
                                                                    );
                                                    
                                                                    //Mise à jour de l'historique de ce courrier
                                                                    insertHistorique("enregistrement du courrier",$idcourrierdepart,$nom_entite,"courrier départ",$matricule);
                                                                    //---------------------------------------------Insertion des copies de courriers dans la base de données----------------------------- 
                                                    
                                                                    if (!in_array(null,$TableauNomDestinataireCopie)) {
                                                                         entrerLesCopies($TableauNomDestinataireCopie,$liencourrier,$idcourrierdepart,null); 
                                                                        
                                                                    }
                                                    
                                                    
                                                                    //---------------------------------------------Insérer les fichiers joins - ----------------------------------------
                                                    
                                                                    if ($nombre_fichiers_joins ===count($liens_fichiers_joins)) {
                                                                        foreach ($liens_fichiers_joins as $lien) {
                                                                            insererFichierJoin($lien,$idcourrierdepart,null);
                                                                        }
                                                                        
                                                                    }
                                                    


                                                                                        //--------------------------------------------insertion automatique du courrier arrivé de ce destinataire----------------------------

                                                                                                    if ($expediteur !== $destinataire) {

                                                                                                        $expediteur = $nom_entite;

                                                                                                        // echo " expediteur : $expediteur  ddestinataire: $destinataire";

                                                                                                        $etatCourrier = 'reçu';
                                                                                                    $idcourrierArrive = insererCourrierArriveV2($numeroOrdre,$TypeDoc,$etat_inter_exter,
                                                                                                    $etat_plis_ferme,$categorie,$dateEnreg,null,$reference,
                                                                                                    $liencourrier,$objet,$matricule,$idReponse,$expediteur,$destinataire,$identite_dest,$idpole_dest,
                                                                                                    $nombre_fichiers_joins,$etatCourrier
                                                                                                    );


                                                                                                    //Mise à jour de l'historique de ce courrier
                                                                                                    insertHistorique("enregistrement du courrier",$idcourrierArrive,$nom_entite,"courrier arrivé",$matricule);
                                                                                                    //--------------------------------------------insertion automatique du courrier arrivé de ce destinataire----------------------------

                                                                                                    if ($nombre_fichiers_joins ===count($liens_fichiers_joins_arrives )) {
                                                                                                        foreach ($liens_fichiers_joins_arrives  as $lien) {
                                                                                                            insererFichierJoin($lien,null,$idcourrierArrive);
                                                                                                        }
                                                                                                        
                                                                                                    }

                                                                                                    }
                                                                                                    $lien = "../../public/page/tableau-bord";
                                                                                                    $message = "Courrier enregistré avec succès";
                                                                                                    




                                                                }   





                                                             }
                                    
                                                        }

                                                    // ----------------------- Gestion des destinataire --------------------

                                                    

                                                }

                                                // ------------------------------- Plis fermés -----------------------------------

                                                elseif ($etat_plis_ferme === "oui" ) {

                                                    
                                                      
                                                    
                                                    $formatCourrier = null;
                                                   

                                                    if (strlen($destinataire)==0) {
                                                        $lien = "";
                                                        $message = "Veuillez entrer un destinataire.";
                                                        echo $message;
                                                        var_dump($message);
                                
                                                    }
                                                    elseif (strlen($destinataire) != 0  ) {
                                                        $Liste_entite_destinataire = recupererLigneSpecifique('entite_banque','nom_entite',$destinataire);
                                                        $Liste_pole_destinataire = recupererLigneSpecifique('pole','nom_pole',$destinataire);
                                                        
                                                        
                                
                                
                                
                                                        //on récupère le nom et le format du fichier dans un tableau
                                                        
                                                        
                                                        // Récupération de l'id du destinataire du courrier 
                                                        if (isset($Liste_entite_destinataire)) {
                                                            $objet_entite_banque = $Liste_entite_destinataire[0];
                                                            $identite_dest = $objet_entite_banque->id_entite;
                                                        } else {
                                                            $identite_dest = null;
                                                        }
                                                        
                                                        
                                                        if (isset($Liste_pole_destinataire)) {
                                                            $objet_pole_dest = $Liste_pole_destinataire[0];
                                                            $idpole_dest = $objet_pole_dest->id_pole;
                                                        }else {
                                                            $idpole_dest = null;
                                                        } 

                                                        if (is_null($identite_dest) && is_null($idpole_dest)) {
                                                            # Si on entre ici cela veut dire qu'il n'a pas entrer un destinataire interne à la banque
                                                            $lien = "";
                                                            $message = "erreur  le destinataire n\'est pas une reconnu comme une entité de la banque";
                                                         
                                                        } 
                                                        
                                                        else {

                                                                



                                                            try {
                                                                // Récupérer et nettoyer le champ 'nombre_joins'
                                                                $nombre_fichiers_joins = trim($_POST['nombre_joins']);
                                            
                                                                // Cas où 'nombre_joins' est vide et aucun fichier n'est joint
                                                                if (empty($nombre_fichiers_joins) && count($liens_fichiers_joins) == 0) {
                                                                    // Aucun fichier joint et pas de nombre renseigné => Le programme continue normalement.
                                                                    $nombre_fichiers_joins = 0;

                                                                    
                                                                }
                                                                // Cas où 'nombre_joins' est renseigné
                                                                elseif (!empty($nombre_fichiers_joins)) {
                                                                    // Vérifier que 'nombre_joins' est un entier

                                                                    if (!ctype_digit($nombre_fichiers_joins)) {
                                                                        $lien = "";
                                                                        $message =  "Le nombre de fichiers joints est impérativement un nombre entier positif, veuillez entrer un nombre entier.";
                                                                      
                                                                       $test_nombre_fichiers_joins = "pas Possible"; // Cette variable permet de savoir si on peut enregistrer si 

                                                                    
                                                                    }
                                            
                                                                    // Convertir en entier
                                                                    $nombre_fichiers_joins = (int)$nombre_fichiers_joins;
                                                                    
                                                                    // Si le nombre renseigné ne correspond pas au nombre de fichiers joints
                                                                    if ($nombre_fichiers_joins != count($liens_fichiers_joins)) {
                                                                        $lien = "";
                                                                        $test_nombre_fichiers_joins = "pas Possible";
                                                                        $message = "Le nombre de fichiers joints renseigné ($nombre_fichiers_joins) ne correspond pas au nombre de fichiers sélectionnés (" . count($liens_fichiers_joins) . "). Veuillez entrer le bon nombre de fichiers.";
                                                                        
                                                                   
                                                                    }
                                                                }
                                                                // Cas où 'nombre_joins' n'est pas renseigné mais des fichiers sont joints
                                                                elseif (empty($nombre_fichiers_joins) && count($liens_fichiers_joins) > 0) {
                                                                    $lien = "";
                                                                    $message = "Le nombre de fichiers joints doit être précisé, veuillez entrer un nombre.";
                                                                   
                                                                    $test_nombre_fichiers_joins = "pas Possible";
                                                                }
                                                                // Cas où 'nombre_joins' est renseigné mais aucun fichier n'est joint
                                                                elseif ($nombre_fichiers_joins > 0 && count($liens_fichiers_joins) == 0) {
                                                                    $lien = "";
                                                                    $message = "Vous avez précisé un nombre de fichiers joints, mais aucun fichier n'a été sélectionné.";
                                                                  
                                                                    $test_nombre_fichiers_joins = "pas Possible";
                                                                }
                                            
                                                                // Si les deux sont renseignés et sont égaux, ou si aucun des deux n'est renseigné (et pas de fichiers joints), le programme continue normalement
                                            
                                                            } catch (Exception $e) {
                                                                die("Une erreur s'est produite : " . $e->getMessage());
                                                            }







                                                            if (strlen($liste_copie_courrier)!=0) {
                                                                $TableauNomDestinataireCopie = explode(", ",$liste_copie_courrier) ;
                                                               
                                                                //On vérifie que les noms des copies existent bien dans la base de données
                                                                if (count($TableauNomDestinataireCopie)>0) {

                                                                    foreach ($TableauNomDestinataireCopie as $nom_copie) {

                                                                    $Liste_des_entites_en_copies = recupererLigneSpecifique('entite_banque','nom_entite',$nom_copie);
                                                                    $Liste_des_poles_en_copie = recupererLigneSpecifique('pole','nom_pole',$nom_copie);
                                        
                                                                if (isset($Liste_des_entites_en_copies)) {
                                                                    $objet_entite_banque_copie = $Liste_des_entites_en_copies[0];
                                                                    $identite_copie = $objet_entite_banque_copie->id_entite;
                                                                } else {
                                                                    $identite_copie = null;
                                                                }
                                                                
                                            
                                                                if (isset($Liste_des_poles_en_copie)) {
                                                                    $objet_pole_copie = $Liste_des_poles_en_copie[0];
                                                                    $idpole_copie = $objet_pole_copie->id_pole;
                                                                }else {
                                                                    $idpole_copie = null;
                                                                } 

                                                                if (is_null($identite_copie) && is_null($idpole_copie)) {
                                                                    $lien = "";
                                                                    $test_copie_courrier = "pas possible";
                                                                    $message="erreur  le destinataire '. $nom_copie .' mentionné en copie n\'est pas reconnu comme une entité de la banque";
                                                                    

                                                                } 
                                                                
                                                                

                                                                    }

                                                                    
                                                                    

                                                                }

                                                            }


                                                            // Enregistrement de courrier 

                                                            
                                                                # code...

                                                         

                                                                $etatCourrier = 'envoyé';
                                                                $idcourrierdepart = insererCourrierDepart($numeroOrdre,$TypeDoc,$etat_inter_exter,  
                                                                $etat_plis_ferme,$categorie,$dateEnreg,null,$reference,
                                                                $liencourrier,$objet,$matricule,$idReponse,$etatExpedition,$expediteur,$destinataire,$identite_dest,$idpole_dest,
                                                                $nombre_fichiers_joins,$etatCourrier
                                                                );
                                                
                                                                //Mise à jour de l'historique de ce courrier
                                                                insertHistorique("enregistrement du courrier",$idcourrierdepart,$nom_entite,"courrier départ",$matricule);
                                                                //---------------------------------------------Insertion des copies de courriers dans la base de données----------------------------- 
                                                                
                                                                // if (count($TableauNomDestinataireCopie)==0) {
                                                                //     # code...
                                                                //     if (!in_array(null,$TableauNomDestinataireCopie)) {

                                                                //         entrerLesCopies($TableauNomDestinataireCopie,$liencourrier,$idcourrierdepart,null); 
                                                                       
                                                                //    }
                                                                // }
                                   
                                                
                                                
                                                                //---------------------------------------------Insérer les fichiers joins - ----------------------------------------
                                                
                                                                if ($nombre_fichiers_joins ===count($liens_fichiers_joins)) {
                                                                    foreach ($liens_fichiers_joins as $lien) {
                                                                        insererFichierJoin($lien,$idcourrierdepart,null);
                                                                    }
                                                                    
                                                                }
                                                


                                                                                    //--------------------------------------------insertion automatique du courrier arrivé de ce destinataire----------------------------

                                                                                                if ($expediteur !== $destinataire) {

                                                                                                    $expediteur = $nom_entite;

                                                                                                    // echo " expediteur : $expediteur  ddestinataire: $destinataire";

                                                                                                    $etatCourrier = 'reçu';
                                                                                                $idcourrierArrive = insererCourrierArriveV2($numeroOrdre,$TypeDoc,$etat_inter_exter,
                                                                                                $etat_plis_ferme,$categorie,$dateEnreg,null,$reference,
                                                                                                $liencourrier,$objet,$matricule,$idReponse,$expediteur,$destinataire,$identite_dest,$idpole_dest,
                                                                                                $nombre_fichiers_joins,$etatCourrier
                                                                                                );


                                                                                                //Mise à jour de l'historique de ce courrier
                                                                                                insertHistorique("enregistrement du courrier",$idcourrierArrive,$nom_entite,"courrier arrivé",$matricule);
                                                                                                //--------------------------------------------insertion automatique du courrier arrivé de ce destinataire----------------------------

                                                                                                if ($nombre_fichiers_joins ===count($liens_fichiers_joins_arrives )) {
                                                                                                    foreach ($liens_fichiers_joins_arrives  as $lien) {
                                                                                                        insererFichierJoin($lien,null,$idcourrierArrive);
                                                                                                    }
                                                                                                    
                                                                                                }

                                                                                                }
                                                                                                $lien = "../../public/page/tableau-bord";
                                                                                                $message = "Courrier enregistré avec succès";
                                                                                                




                                                               





                                                         }

                                                    }

                                           
                                                    


                                                   

                                                }

                                            }

                                            }
                                
                                        }
        







                                    
                                    elseif (strlen($destinataire) != 0  ) {
                
                                     
                
                                        $Liste_entite_destinataire = recupererLigneSpecifique('entite_banque','nom_entite',$destinataire);
                                        $Liste_pole_destinataire = recupererLigneSpecifique('pole','nom_pole',$destinataire);
                                        
                                        
                
                
                
                                        //on récupère le nom et le format du fichier dans un tableau
                                        
                                        
                                        // Récupération de l'id du destinataire du courrier 
                                        if (isset($Liste_entite_destinataire)) {
                                            $objet_entite_banque = $Liste_entite_destinataire[0];
                                            $identite_dest = $objet_entite_banque->id_entite;
                                        } else {
                                            $identite_dest = null;
                                        }
                                        
                                        
                                        if (isset($Liste_pole_destinataire)) {
                                            $objet_pole_dest = $Liste_pole_destinataire[0];
                                            $idpole_dest = $objet_pole_dest->id_pole;
                                        }else {
                                            $idpole_dest = null;
                                        } 
                
                
                
                                       
                
                                         
                                            // if (is_null($identite_dest) && is_null($idpole_dest)) {
                                            //     # Si on entre ici cela veut dire qu'il n'a pas entrer un destinataire interne à la banque
                                            //     // $message = "erreur  le destinataire n\'est pas une reconnu comme une entité de la banque";
                                            //     // echo $message;
                                            // } 
                                             {
                                                    // -------------------------- Gestion des copies des courrier ---------------------------
                                        
                                        if (strlen($liste_copie_courrier)!=0) {
                                            $TableauNomDestinataireCopie = explode(",",$liste_copie_courrier) ;
                        
                                            //On vérifie que les noms des copies existent bien dans la base de données
                        
                                            if (count($TableauNomDestinataireCopie)>0) {
                                            foreach ($TableauNomDestinataireCopie as $nom_copie) {
                                                $Liste_des_entites_en_copies = recupererLigneSpecifique('entite_banque','nom_entite',$nom_copie);
                                                $Liste_des_poles_en_copie = recupererLigneSpecifique('pole','nom_pole',$nom_copie);
                                            
                                                if (isset($Liste_des_entites_en_copies)) {
                                                    $objet_entite_banque_copie = $Liste_des_entites_en_copies[0];
                                                    $identite_copie = $objet_entite_banque_copie->id_entite;
                                                } else {
                                                    $identite_copie = null;
                                                }
                                                
                                                
                                                if (isset($Liste_des_poles_en_copie)) {
                                                    $objet_pole_copie = $Liste_des_poles_en_copie[0];
                                                    $idpole_copie = $objet_pole_copie->id_pole;
                                                }else {
                                                    $idpole_copie = null;
                                                } 
                                            
                
                
                                                
                                                if (is_null($identite_copie) && is_null($idpole_copie)) {
                                                    $message="erreur  le destinataire '. $nom_copie .' mentionné en copie n\'est pas reconnu comme une entité de la banque";
                                                    echo $message;
                                                }
                                                
                
                                                
                                                // ici tout est bon au niveau des destinataire de ce courrier 
                                                else { 
                
                                                     //------------------------------------------Gestion des fichiers de courriers --------------------------------------------------------
                                                    
                                                     if ($etat_plis_ferme==="non") {
                
                                                        if (strlen($_FILES['fichier']['name'])==0) {
                                                        $message= "Vous n'avez pas choisi un fichier";
                                                        echo $message;
                                                        } 
                                            
                                                        // Ici tout est correcte pour le fichier
                                                
                                                    else {
                
                                                $liendossier = creerListeDossiersCourrierDepart($etat_inter_exter,$destinataire);
                                                $liencourrier = deposerFichierDansDossier($liendossier,$fichier);
                                                $nom_balise_fichiers_join ="fichiers_joints"; //Cette variable récupère la valeur de l'attribut name spécifié dans la balise html qui envoi les fichiers annexes
                                                
                                                $chemin_fichiers_joins = $liendossier."/FichierAnnexes";//Cette variable repésente le lien du dossier où on doit stocker les fichiers annexes
                                
                                                $liens_fichiers_joins = get_uploaded_files_paths($chemin_fichiers_joins,$nom_balise_fichiers_join);
                                
                                                // print_r($liens_fichiers_joins);
                                
                                                $liens_fichiers_joins_arrives = get_uploaded_files_pathsarrive($chemin_fichiers_joins,$nom_balise_fichiers_join);
                                
                                                if (isset($fichier)) {
                                                $formatCourrier = pathinfo($fichier['name'],PATHINFO_FILENAME); # code...
                                                }   
                                                else{
                                                $formatCourrier = null;
                                                }
                
                
                                              
                
                                            
                                            }                            
                            
                                            }
                
                                            else  {
                                                $liendossier = '';
                                                $liencourrier = "";
                                            }
                                                    
                                                }
                
                                            }
                                        }
                                        }
                                
                                        
                                        
                
                                            }
                
                
                                       
                
                
                
                
                
                
                
                                           
                
                
                
                
                
                
                
                                                
                                                
                                                
                                            
                                    } 
                                }




                        }


                        // -----------------------Fin gestion du courrier ---------------------------------------
                    
                    
                    

                }  



                    elseif ($test_type_courrier==='courrier arrivé') {

                    
                    if (!is_null($expediteur_courrierArv)) {
                        $Liste_entite_expediteur = recupererLigneSpecifique('entite_banque','nom_entite',$expediteur_courrierArv);
                        $Liste_pole_expediteur = recupererLigneSpecifique('pole','nom_pole',$expediteur_courrierArv);
                        
                            // Récupération de l'id du destinataire du courrier 
                    if (isset($Liste_entite_expediteur)) {
                        $objet_entite_banque = $Liste_entite_expediteur[0];
                        $identite_expediteur = $objet_entite_banque->id_entite;
                    } else {
                        $identite_expediteur = null;
                    }
                    
                    if (isset($Liste_pole_expediteur)) {
                        $objet_pole_expediteur = $Liste_pole_expediteur[0];
                        $idpole_expediteur = $objet_pole_expediteur->id_pole;
                    }else {
                        $idpole_expediteur = null;
                    } 
                    
                    }
                    else {
                        // die('veuillez entrer un destinataire');
                    $message = "Veuillez entrer un expéditeur.";
                    echo $message;
                    
                        // die('<script>
                        //         alert("Veuillez entrer un destinataire.");
                        //        setTimeout(function(){
                        //             window.location.href = "../../public/page/courrier-interne.php";
                        //         }, 500); 
                        //   </script>');
                        
                    }

                    }
                





                //     if (strlen($liste_copie_courrier)!=0) {
                //     $TableauNomDestinataireCopie = explode(",",$liste_copie_courrier) ;

                //     //On vérifie que les noms des copies existent bien dans la base de données

                //     if (count($TableauNomDestinataireCopie)>0) {
                //     foreach ($TableauNomDestinataireCopie as $nom_copie) {
                //         $Liste_des_entites_en_copies = recupererLigneSpecifique('entite_banque','nom_entite',$nom_copie);
                //         $Liste_des_poles_en_copie = recupererLigneSpecifique('pole','nom_pole',$nom_copie);
                    
                //         if (isset($Liste_des_entites_en_copies)) {
                //             $objet_entite_banque_copie = $Liste_des_entites_en_copies[0];
                //             $identite_copie = $objet_entite_banque_copie->id_entite;
                //         } else {
                //             $identite_copie = null;
                //         }
                        
                        
                //         if (isset($Liste_des_poles_en_copie)) {
                //             $objet_pole_copie = $Liste_des_poles_en_copie[0];
                //             $idpole_copie = $objet_pole_copie->id_pole;
                //         }else {
                //             $idpole_copie = null;
                //         } 
                    
                //         if (is_null($identite_copie) && is_null($idpole_copie)) {
                //             die('<script>alert("erreur  le destinataire '. $nom_copie .' mentionné en copie n\'est pas reconnu comme une entité de la banque")</script>');

                //         }
                //     }
                // }
                // }





                //------------------------------------fin controle des nom destinataires---------------------------------------






                // if ($etat_plis_ferme==="non") {

                // $liendossier = creerListeDossiersCourrierDepart($etat_inter_exter,$destinataire);
                // $liencourrier = deposerFichierDansDossier($liendossier,$fichier);
                // $nom_balise_fichiers_join ="fichiers_joints"; //Cette variable récupère la valeur de l'attribut name spécifié dans la balise html qui envoi les fichiers annexes
                
                // $chemin_fichiers_joins = $liendossier."/FichierAnnexes";//Cette variable repésente le lien du dossier où on doit stocker les fichiers annexes

                // $liens_fichiers_joins = get_uploaded_files_paths($chemin_fichiers_joins,$nom_balise_fichiers_join);

                // // print_r($liens_fichiers_joins);

                // $liens_fichiers_joins_arrives = get_uploaded_files_pathsarrive($chemin_fichiers_joins,$nom_balise_fichiers_join);

                // if (isset($fichier)) {
                //     $formatCourrier = pathinfo($fichier['name'],PATHINFO_FILENAME); # code...
                // }else{
                //     $formatCourrier = null;
                // }

                // } else  {
                //     $liendossier = '';
                //     $liencourrier = "";
                // }




                //-------------------------------------------Controle du numero d'ordre--------------------------------

                // Ici On récupère le pole ou l'entité à laquelle appartient un utilisateur  




                #Récupération du nom de l'entité à laquelle est relié un utilisateur
                // $requete = " select e.id_entite, e.nom_entite
                // from entite_banque e inner join utilisateur u on 
                // e.id_entite = u.id_entite
                // where u.Matricule = ?;";

                // $sql1 = " select p.id_pole, p.nom_pole
                // from pole p inner join utilisateur u on 
                // p.id_pole = u.id_pole
                // where u.Matricule = ?;";

                // $sql2=" select e.id_entite, e.nom_entite
                // from entite_banque e inner join utilisateur u on 
                // e.id_entite = u.id_entite
                // where u.Matricule = ?;";

                // $infos_entite_utilisateur = recupererIdEntiteOuIdPolePourUnUtilisateur($sql2,$matricule);

                // $infos_pole_utilisateur = recupererIdEntiteOuIdPolePourUnUtilisateur($sql1,$matricule);


                // if ( $test_type_courrier==='courrier départ') {
                //         if (isset($infos_pole_utilisateur['id_pole'])) {
                        
                //             $nom_entite = recupererNomEntiteParIdUtilisateur($sql1,$matricule);
                //             $expediteur = $nom_entite;
                            
                //             #On récupère le numéro d'ordre qu'on doit entré en fonction de l'entité
                //             $num_a_entrer = verifierNumeoOrdreParPole($nom_entite);
                //             $numeroOrdrePrefix = explode('/', $numeroOrdre)[0];  // On récupère juste la partie avant le "/"
                            
                //             // On compare le numéro d'ordre entré à celui qui est attendu en fonction de l'entité
                //             if ($numeroOrdrePrefix != $num_a_entrer) {
                //                     $message="Le numéro d'ordre pour le pole  $nom_entite attendu est : $num_a_entrer";
                //                     echo $message;
                //             }
                            
                            
                //             }
                        
                //         elseif (isset($infos_entite_utilisateur['id_entite'])) {
                //             $nom_entite = recupererNomEntiteParIdUtilisateur($sql2,$matricule);
                //             $expediteur = $nom_entite;
                        
                //         #On récupère le numéro d'ordre qu'on doit entré en fonction de l'entité
                //         $num_a_entrer = verifierNumeoOrdreParEntiteV2($nom_entite);
                //         $numeroOrdrePrefix = explode('/', $numeroOrdre)[0];  // On récupère juste la partie avant le "/"
                        
                //         // On compare le numéro d'ordre entré à celui qui est attendu en fonction de l'entité
                //         if ($numeroOrdrePrefix != $num_a_entrer) {
                //             die("Le numéro d'ordre pour l'entité $nom_entite attendu est : $num_a_entrer");
                //         }
                //     }


                //     } 


                        elseif ($test_type_courrier==='courrier arrivé') {
                            if (isset($infos_pole_utilisateur['id_pole'])) {
                        
                                $nom_entite = recupererNomEntiteParIdUtilisateur($sql1,$matricule);
                                $destinataire = $nom_entite;
                                
                                #On récupère le numéro d'ordre qu'on doit entré en fonction de l'entité

                                // echo '';
                                // $num_a_entrer = getNextNumeroOrdreCourrierArriveInterne($expediteur_courrierArv,$destinataire);
                                // $numeroOrdrePrefix = explode('/', $numeroOrdre)[0];  // On récupère juste la partie avant le "/"
                                
                                // // On compare le numéro d'ordre entré à celui qui est attendu en fonction de l'entité
                                // if ($numeroOrdrePrefix != $num_a_entrer) {
                                //     die("Le numéro d'ordre pour  $expediteur_courrierArv attendu est : $num_a_entrer");
                                // }
                                
                                
                                }
                                
                            elseif (isset($infos_entite_utilisateur['id_entite'])) {
                                    $nom_entite = recupererNomEntiteParIdUtilisateur($sql2,$matricule);
                                    $destinataire = $nom_entite;
                                
                                #On récupère le numéro d'ordre qu'on doit entré en fonction de l'entité
                                // $num_a_entrer = getNextNumeroOrdreCourrierArriveInterne($expediteur_courrierArv,$destinataire);

                                // $numeroOrdrePrefix = explode('/', $numeroOrdre)[0];  // On récupère juste la partie avant le "/"
                                
                                // // On compare le numéro d'ordre entré à celui qui est attendu en fonction de l'entité
                                // if ($numeroOrdrePrefix != $num_a_entrer) {
                                //     die("Le numéro d'ordre pour l'entité $expediteur_courrierArv attendu est : $num_a_entrer");
                                // }
                            }
                        
                    }




                // $nom_entite = recupererNomEntiteParIdUtilisateur($requete,$matricule);
                // $expediteur = $nom_entite;

                //-------------------------------------fin controle numero d'ordre-------------------------------------



                // ---------------------------------------Ici nous vérifions si le fichier à été envoyé  

                // if ($etat_plis_ferme==="non") {
                //     if (strlen($_FILES['fichier']['name'])==0) {
                //         die("Vous n'avez pas choisi un fichier");
                //     }
                
                // }



                //---------------------------------------Controle des destinataires internes--------------------------------

                if ($test_type_courrier==='courrier départ') {
                 


                } 
                
                elseif ($test_type_courrier==='courrier arrivé') {
                    
                    if (is_null($expediteur_courrierArv)) {
                        
                        die("Vous n'avez pas saisi d'expiditeur");
                        
                    }
                    else{
                        if ($etat_inter_exter==="courrier interne") {
                            if (is_null($identite_expediteur) && is_null($idpole_expediteur)) {
                                # Si on entre ici cela veut dire qu'il n'a pas entrer un destinataire interne à la banque

                                echo 'Je suis bien entré ';
                                die('<script>alert("erreur  l\'expéditeur n\'est pas une reconnu comme une entité de la banque")</script>');
                            }
                            
                        }elseif ($etat_inter_exter==="courrier externe") {
                            if (!is_null($identite_expediteur) || !is_null($idpole_expediteur)) {
                                die("Vous avez défini une entité interieure à la banque comme destinataire d'un courrier externe ");
                            }
                        }
                        
                    }

                }









                //-------------------------------------Fin controle des destinataires internes------------------------------


                //------------------------------------Controle des valeurs énum------------------------------------------------

                #Ici on vérifie si une valeure entrée correspond à celle attendue dans la base de données
                $etat_inter_exter = verifierValeurEnum($etat_inter_exter,['courrier interne','courrier externe'],'etat interne externe');
                $etat_plis_ferme = verifierValeurEnum($etat_plis_ferme,['oui','non'],'etat plis fermé');
                //$etatExpedition = verifierValeurEnum($etatExpedition,['oui','non'],'etat expédition');
                $categorie = verifierValeurEnum($categorie,['urgent','normal'],'catégorie');



                //---------------------------------------Controle du nombre de fichiers joins par rapport----------------------------

                try {
                    // Récupérer et nettoyer le champ 'nombre_joins'
                    $nombre_fichiers_joins = trim($_POST['nombre_joins']);

                    // Cas où 'nombre_joins' est vide et aucun fichier n'est joint
                    if (empty($nombre_fichiers_joins) && count($liens_fichiers_joins) == 0) {
                        // Aucun fichier joint et pas de nombre renseigné => Le programme continue normalement.
                        $nombre_fichiers_joins = 0;
                        
                    }
                    // Cas où 'nombre_joins' est renseigné
                    elseif (!empty($nombre_fichiers_joins)) {
                        // Vérifier que 'nombre_joins' est un entier
                        if (!ctype_digit($nombre_fichiers_joins)) {
                            die("Le nombre de fichiers joints est impérativement un nombre entier positif, veuillez entrer un nombre entier.");
                        }

                        // Convertir en entier
                        $nombre_fichiers_joins = (int)$nombre_fichiers_joins;
                        
                        // Si le nombre renseigné ne correspond pas au nombre de fichiers joints
                        if ($nombre_fichiers_joins != count($liens_fichiers_joins)) {
                            die("Le nombre de fichiers joints renseigné ($nombre_fichiers_joins) ne correspond pas au nombre de fichiers sélectionnés (" . count($liens_fichiers_joins) . "). Veuillez entrer le bon nombre de fichiers.");
                        }
                    }
                    // Cas où 'nombre_joins' n'est pas renseigné mais des fichiers sont joints
                    elseif (empty($nombre_fichiers_joins) && count($liens_fichiers_joins) > 0) {
                        die("Le nombre de fichiers joints doit être précisé, veuillez entrer un nombre.");
                    }
                    // Cas où 'nombre_joins' est renseigné mais aucun fichier n'est joint
                    elseif ($nombre_fichiers_joins > 0 && count($liens_fichiers_joins) == 0) {
                        die("Vous avez précisé un nombre de fichiers joints, mais aucun fichier n'a été sélectionné.");
                    }

                    // Si les deux sont renseignés et sont égaux, ou si aucun des deux n'est renseigné (et pas de fichiers joints), le programme continue normalement

                } catch (Exception $e) {
                    die("Une erreur s'est produite : " . $e->getMessage());
                }


                // if (is_null($_POST['Objet_du_courrier'])) {
                //     die('<script>alert("erreur  Objet post non renseigné")</script>');
                // }
                // if (strlen($objet)==0) {
                //     die('<script>alert("erreur  Objet non renseigné")</script>');
                // } 
                // if (strlen($numeroOrdre)==0) {
                //     die("Vous n'avez pas renseigné un numéro d'ordre pour votre courrier");
                // } 
                // if (strlen($dateEnreg)==0) {
                //     die("Vous n'avez pas renseigné une date d'enregistrement d'ordre pour votre courrier");
                // }
                if (strlen($TypeDoc)==0 && $etat_plis_ferme==="non") {
                    die("Vous n'avez pas renseigné un type de document pour votre courrier");
                }
                if (!isset($fichier) && $etat_plis_ferme==="non" ) {
                    die("vous n'avez pas choisi de fichier");
                }






                // if ($test_type_courrier==='courrier départ') {

                // if ($numeroOrdrePrefix === $num_a_entrer) {
                //     # code...
                // }

                // $etatCourrier = 'envoyé';
                // $idcourrierdepart = insererCourrierDepart($numeroOrdre,$TypeDoc,$etat_inter_exter,$ //----------------------------------------------Fin controle-----------------------------------------------
                
                // $etat_plis_ferme,$categorie,$dateEnreg,null,$reference,
                // $liencourrier,$objet,$matricule,$idReponse,$etatExpedition,$expediteur,$destinataire,$identite_dest,$idpole_dest,
                // $nombre_fichiers_joins,$etatCourrier
                // );

                // //Mise à jour de l'historique de ce courrier
                // insertHistorique("enregistrement du courrier",$idcourrierdepart,$nom_entite,"courrier départ",$matricule);
                // //---------------------------------------------Insertion des copies de courriers dans la base de données----------------------------- 

                // if (!in_array(null,$TableauNomDestinataireCopie)) {
                //      entrerLesCopies($TableauNomDestinataireCopie,$liencourrier,$idcourrierdepart,null); 
                    
                // }


                // //---------------------------------------------Insérer les fichiers joins - ----------------------------------------

                // if ($nombre_fichiers_joins ===count($liens_fichiers_joins)) {
                //     foreach ($liens_fichiers_joins as $lien) {
                //         insererFichierJoin($lien,$idcourrierdepart,null);
                //     }
                    
                // }






                // //--------------------------------------------insertion automatique du courrier arrivé de ce destinataire----------------------------

                // if ($expediteur !== $destinataire) {

                //     $expediteur = $nom_entite;

                //     // echo " expediteur : $expediteur  ddestinataire: $destinataire";

                //     $etatCourrier = 'reçu';
                // $idcourrierArrive = insererCourrierArriveV2($numeroOrdre,$TypeDoc,$etat_inter_exter,
                // $etat_plis_ferme,$categorie,$dateEnreg,null,$reference,
                // $liencourrier,$objet,$matricule,$idReponse,$expediteur,$destinataire,$identite_dest,$idpole_dest,
                // $nombre_fichiers_joins,$etatCourrier
                // );


                // //Mise à jour de l'historique de ce courrier
                // insertHistorique("enregistrement du courrier",$idcourrierArrive,$nom_entite,"courrier arrivé",$matricule);
                // //--------------------------------------------insertion automatique du courrier arrivé de ce destinataire----------------------------

                // if ($nombre_fichiers_joins ===count($liens_fichiers_joins_arrives )) {
                //     foreach ($liens_fichiers_joins_arrives  as $lien) {
                //         insererFichierJoin($lien,null,$idcourrierArrive);
                //     }
                    
                // }

                // }


                // die( '<script>
                // alert("Votre action a été effectuée avec succès.");
                // setTimeout(function(){
                //     window.location.href = "../../public/page/courrier-interne.php";
                // }, 500); 
                // </script>'
                // );


                // }





                // if ($test_type_courrier==='courrier arrivé') {
                    

                // $etatCourrier = 'reçu';
                // $idcourrierArrive = insererCourrierArriveV2($numeroOrdre,$TypeDoc,$etat_inter_exter,
                // $etat_plis_ferme,$categorie,$dateEnreg,null,$reference,
                // $liencourrier,$objet,$matricule,$idReponse,$expediteur_courrierArv,$destinataire,$identite_dest,$idpole_dest,
                // $nombre_fichiers_joins,$etatCourrier
                // );


                // //Mise à jour de l'historique de ce courrier
                // insertHistorique("enregistrement du courrier",$idcourrierArrive,$nom_entite,"courrier arrivé",$matricule);
                // //--------------------------------------------insertion automatique du courrier arrivé de ce destinataire----------------------------

                // if ($nombre_fichiers_joins ===count($liens_fichiers_joins_arrives )) {
                //     foreach ($liens_fichiers_joins_arrives  as $lien) {
                //         insererFichierJoin($lien,null,$idcourrierArrive);
                //     }
                    
                // }





                // }




                // die( '<script>
                // alert("Votre action a été effectuée avec succès.");
                // setTimeout(function(){
                //     window.location.href = "../../public/page/courrier-interne.php";
                // }, 500); 
                // </script>'
                // );

            // }
}



?>



