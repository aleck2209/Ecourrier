<?php
function verifierValeurNulle($value){
if (!isset($value)) {
    $value = null;
    return $value;
} else {
    return $value;
}

}
?>