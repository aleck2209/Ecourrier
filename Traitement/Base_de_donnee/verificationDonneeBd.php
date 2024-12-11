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


?>