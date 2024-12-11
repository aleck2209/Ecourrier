document.addEventListener('DOMContentLoaded', alertSave(), regNumOrdre())

// AUTOCOMPLETION CAS DU DESTINATAIRE
function completion() {
    const data = ["DSI", "DPMG", "DRHF", "DOF", "DAI", "DCBCG", "DOP"];
    const destinataire = document.getElementById('destinataire');
    const list = document.getElementById('listAutocompleteDestinataire');

    const value = destinataire.value.toUpperCase();
    list.innerHTML = "";
    if (value) {
        data.forEach(item => {
            if(item.startsWith(value)) {
                const div = document.createElement('div');
                div.textContent = item;
                div.className = "autocomplete-list__item"
                div.onclick = () => {
                    destinataire.value = item;
                    list.innerHTML = "";
                }
                list.appendChild(div)
            }
        })
    }

    document.addEventListener('click', (e) => {
        if (e.target !== destinataire) {
            list.innerHTML = "";
        }
    })
}

function completionCopie() {
    const data = ["DSI", "DPMG", "DRHF", "DOF", "DAI", "DCBCG", "DOP"];
    const copie = document.getElementById('copie');
    const list = document.getElementById('listAutocompleteCopie');

    const currentValue = copie.value;
    const lastValue = currentValue.split(',').pop().trim();
    if (lastValue === "") {
        list.innerHTML = ""; // Pas de suggestion si la saisie est vide
        return;
    }

    const filteredSuggestions = data.filter(item =>
        item.toLowerCase().startsWith(lastValue.toLowerCase())
    );

    list.innerHTML = filteredSuggestions
        .map(item => `<div class="autocomplete-list__item">${item}</div>`)
        .join('');

    const items = document.querySelectorAll('.autocomplete-list__item');
    items.forEach(suggestion => {
        suggestion.addEventListener('click', () => {
            const existingValues = currentValue.split(',').slice(0, -1).join(',');
            copie.value = existingValues
                ? `${existingValues}, ${suggestion.textContent}`
                : suggestion.textContent;
            list.innerHTML = "";
            copie.focus();
        });
    });

    document.addEventListener('click', (e) => {
        if (e.target !== copie) {
            list.innerHTML = "";
        }
    })
}

// ALERTE AU CAS OU ON QUITTE LA PAGE SANS ENREGISTRER
function alertSave() {
    const forms = document.querySelectorAll('.form-save-mail');
    const alertBox = document.getElementById('alert-box');
    const confirmExit = document.getElementById('confirm-exit');
    const cancelExit = document.getElementById('cancel-exit');
    const navigationLinks = document.querySelectorAll('a');
    let isFormDirty = false;
    let redirectUrl = null; //STOCKE URL CIBLE APRES CONFIRMATION DE CHANGEMENT DE PAGE

    // MARQUER LE FORMULAIRE COMME MODIFIE LORSQU'UN CHAMP CHANGE
    forms.forEach(form => {  
        form.addEventListener('change', () => {
            isFormDirty = true;
        });
    })

    // GESTION DES CLICS SUR LES LIENS INTERNES DE APPLICATION
    navigationLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            if(isFormDirty) {
                e.preventDefault(); //EMPECHE LA NAVIGATION IMMEDIATE
                redirectUrl = e.target.href; //CAPTURE URL IMMEDIATE
                alertBox.classList.remove('alert-hidden');
            }
        });
    });

    // ACTION DE ALERTE
    confirmExit.addEventListener('click', () => {
        alertBox.classList.add("alert-hidden");
        if(redirectUrl) {
            window.location.href = redirectUrl //REDIRIGE VERS LA PAGE DEMANDEE
        }
    });

    cancelExit.addEventListener('click', () => {
        alertBox.classList.add("alert-hidden");
        redirectUrl = null; //REINITIALISE URL CIBLE SI UTILISATEUR ANNULE
    })
}

// AFFICHER PLI FERME
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

// REGEX DU NUMERO D'ORDRE
function regNumOrdre() {
    const input = document.getElementById('numeroOrdre');
    const form = document.getElementById('form-save-mail');
    // ANNEE ENCOURS
    const currentYear = new Date().getFullYear();
    
    // AJOUT DU PLACEHOLDER
    input.setAttribute('placeholder', `Ex: 152/DGE/DSI/${currentYear}`);
    
    
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const regex = /^\d{1,4}(?:\/[A-Z]{1,10})+\/\d{4}$/
        if (regex.test(input.value)) {
            form.submit();
            alert('Donnés soumis')
        } else {
            alert("veuillez respecter le format attendu, exemple: 25/DGE/DSI/2024.")
        }
    });
}