<?php
#Ici sont gérées les fonctions liées à l'insertion des copies du courrier

//Cette fonction enregistre une liste de d'objets dans la table copie_courrier
function entrerLesCopies($listedestinataire,$lienfichier,$idCourrierdep,$idCourrierarv){
       
    try {
            if (!in_array(null,$listedestinataire) && !is_null($lienfichier)  ) {
                #Si on est ici c'est que la liste des destinataires n'est vide et que le lien existe
                foreach ($listedestinataire as $destinataire) {
            $Liste_entite_destinataire = recupererLigneSpecifique('entite_banque','nom_entite',$destinataire);
            $Liste_pole_destinataire = recupererLigneSpecifique('pole','nom_pole',$destinataire);
            //on récupère le nom et le format du fichier dans un tableau
            //$TableauNomFormatCourrier = explode("/",$_FILES['fichier']['type']) ;
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
        
            
            if (!is_null($identite_dest) && !is_null($idpole_dest) ) {

            }else {
                $copie = insererUneCopieCourrier($destinataire,$lienfichier,$idCourrierdep,$idCourrierarv,$identite_dest,$idpole_dest);

            }
            
                }
            }else {
                die("erreur le destinataire n'existe pas ou le lien du fichier est vide ");
            }

//code...
    } catch (Exception $e) {
        die('erreur au niveau de la copie'.$e->getMessage());
    }
   
}

?>