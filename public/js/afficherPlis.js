function afficherPlis() {
    // RECUPERATION DES VARIABLES
    const infoSupp = document.getElementById('infoSupp');
    const pli = document.getElementById('plisFerme');

    // CONDITION D'AFFICHAGE
    if(pli.value == 1) {
        infoSupp.style.display = 'none';
    } else {
        infoSupp.style.display = 'flex';
    }
}

function afficherPlisArrive() {
    // RECUPERATION DES VARIABLES
    const infoSupp = document.getElementById('infoSuppArrive');
    const pli = document.getElementById('plisFermeArrive');

    // CONDITION D'AFFICHAGE
    if(pli.value == 1) {
        infoSupp.style.display = 'none';
    } else {
        infoSupp.style.display = 'flex';
    }
}

