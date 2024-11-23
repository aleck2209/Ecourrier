// AU CHARGEMENT DE LA PAGE
document.addEventListener('DOMContentLoaded', afficherPlis())


function afficherPlis() {
    // RECUPERATION DES VARIABLES
    var infoSupp = document.getElementById('infoSupp');
    var pli = document.getElementById('plisFerme')

    // CONDITION D'AFFICHAGE
    if(pli.value == 1) {
        infoSupp.style.display = 'none'
    } else {
        infoSupp.style.display = 'flex'
    }
}

