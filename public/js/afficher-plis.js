function afficherPlis() {
    // RECUPERATION DES VARIABLES
    const infoSupp = document.getElementById('infoSupp');
    const pli = document.getElementById('plisFerme');
    const typeDocument = document.getElementById('typeDocument');

    // CONDITION D'AFFICHAGE
    if(pli.value == 1) {
        infoSupp.style.display = 'none';
        typeDocument.removeAttribute('required');
    } else {
        infoSupp.style.display = 'flex';
        typeDocument.setAttribute('required', 'required');
    }
}

function afficherPlisArrive() {
    // RECUPERATION DES VARIABLES
    const infoSupp = document.getElementById('infoSuppArrive');
    const pli = document.getElementById('plisFermeArrive');
    const typeDocument = document.getElementById('typeDocumentArrive');

    // CONDITION D'AFFICHAGE
    if(pli.value == 1) {
        infoSupp.style.display = 'none';
        typeDocument.removeAttribute('required');
    } else {
        infoSupp.style.display = 'flex';
        typeDocument.setAttribute('required', 'required');
    }
}

