<?php
// Cette fonction vérifie si le contenu d'une variable figure dans la liste des valeurs attendues entrée d
function verifierValeurEnum($value, $liste_de_valeurs,$param){
if (in_array($value,$liste_de_valeurs)) {
    return $value;
}else{
    die("Vous n'avez pas saisi une valeur attendue dans le champs $param");
}

}
?>